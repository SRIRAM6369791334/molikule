<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use App\Models\Order;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('pincode')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->text('notes')->nullable();
            $table->integer('version')->default(1);
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_verify_payment_falls_back_to_payment_fetch_when_signature_is_missing(): void
    {
        session()->start();

        Order::create([
            'order_number' => 'MOL-TEST-1001',
            'payment_status' => 'pending',
            'payment_method' => 'online',
            'status' => 'pending',
            'razorpay_order_id' => 'order_test_1001',
        ]);

        $paymentApi = Mockery::mock();
        $paymentApi->shouldReceive('fetch')
            ->once()
            ->with('pay_test_1001')
            ->andReturn((object) [
                'order_id' => 'order_test_1001',
                'status' => 'captured',
            ]);

        $razorpay = new class($paymentApi)
        {
            public $payment;
            public $utility;

            public function __construct($payment)
            {
                $this->payment = $payment;
                $this->utility = Mockery::mock();
            }
        };

        $controller = new PaymentController($razorpay);

        $response = $controller->verifyPayment(new Request([
            'order_number' => 'MOL-TEST-1001',
            'razorpay_payment_id' => 'pay_test_1001',
        ]));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('success', data_get($response->getData(true), 'status'));

        $order = Order::where('order_number', 'MOL-TEST-1001')->firstOrFail();

        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('pay_test_1001', $order->razorpay_payment_id);
        $this->assertSame('order_test_1001', $order->razorpay_order_id);
        $this->assertSame('MOL-TEST-1001', session('order_number'));
    }

    public function test_create_order_returns_serializable_razorpay_payload(): void
    {
        Order::create([
            'order_number' => 'MOL-TEST-2001',
            'payment_status' => 'pending',
            'payment_method' => 'online',
            'status' => 'pending',
        ]);

        $orderApi = Mockery::mock();
        $orderApi->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function (array $payload) {
                return $payload['receipt'] === 'MOL-TEST-2001'
                    && $payload['amount'] === 49900
                    && data_get($payload, 'notes.order_number') === 'MOL-TEST-2001';
            }))
            ->andReturn(new class
            {
                public function toArray(): array
                {
                    return [
                        'id' => 'order_test_2001',
                        'amount' => 49900,
                        'currency' => 'INR',
                        'receipt' => 'MOL-TEST-2001',
                        'notes' => ['order_number' => 'MOL-TEST-2001'],
                    ];
                }
            });

        $razorpay = new class($orderApi)
        {
            public $order;
            public $payment;
            public $utility;

            public function __construct($order)
            {
                $this->order = $order;
                $this->payment = Mockery::mock();
                $this->utility = Mockery::mock();
            }
        };

        $controller = new PaymentController($razorpay);

        $response = $controller->createOrder(new Request([
            'order_number' => 'MOL-TEST-2001',
            'amount' => 499,
        ]));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('order_test_2001', data_get($response->getData(true), 'id'));

        $order = Order::where('order_number', 'MOL-TEST-2001')->firstOrFail();
        $this->assertSame('order_test_2001', $order->razorpay_order_id);
    }

    public function test_verify_payment_can_match_and_capture_authorized_payment_without_order_id(): void
    {
        session()->start();

        Order::create([
            'order_number' => 'MOL-TEST-3001',
            'customer_email' => 'buyer@example.com',
            'customer_phone' => '9876543210',
            'total_amount' => 499,
            'payment_status' => 'pending',
            'payment_method' => 'online',
            'status' => 'pending',
            'razorpay_order_id' => 'order_test_3001',
        ]);

        $paymentEntity = new class
        {
            public $order_id = null;
            public $status = 'authorized';
            public $amount = 49900;
            public $currency = 'INR';
            public $description = 'Order #MOL-TEST-3001';
            public $email = 'buyer@example.com';
            public $contact = '+91 9876543210';
            public $notes = [];

            public function toArray(): array
            {
                return [
                    'order_id' => $this->order_id,
                    'status' => $this->status,
                    'amount' => $this->amount,
                    'currency' => $this->currency,
                    'description' => $this->description,
                    'email' => $this->email,
                    'contact' => $this->contact,
                    'notes' => $this->notes,
                ];
            }

            public function capture(array $attributes)
            {
                if ($attributes['amount'] !== 49900 || $attributes['currency'] !== 'INR') {
                    throw new \RuntimeException('Unexpected capture payload.');
                }

                $this->status = 'captured';

                return new class
                {
                    public function toArray(): array
                    {
                        return ['status' => 'captured'];
                    }
                };
            }
        };

        $paymentApi = Mockery::mock();
        $paymentApi->shouldReceive('fetch')
            ->once()
            ->with('pay_test_3001')
            ->andReturn($paymentEntity);

        $razorpay = new class($paymentApi)
        {
            public $payment;
            public $utility;

            public function __construct($payment)
            {
                $this->payment = $payment;
                $this->utility = Mockery::mock();
            }
        };

        $controller = new PaymentController($razorpay);

        $response = $controller->verifyPayment(new Request([
            'order_number' => 'MOL-TEST-3001',
            'razorpay_payment_id' => 'pay_test_3001',
        ]));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('success', data_get($response->getData(true), 'status'));

        $order = Order::where('order_number', 'MOL-TEST-3001')->firstOrFail();
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('pay_test_3001', $order->razorpay_payment_id);
        $this->assertSame('order_test_3001', $order->razorpay_order_id);
    }

    public function test_verify_payment_triggers_shiprocket_sync_for_paid_online_order(): void
    {
        session()->start();

        Order::create([
            'order_number' => 'MOL-TEST-4001',
            'payment_status' => 'pending',
            'payment_method' => 'online',
            'status' => 'pending',
            'razorpay_order_id' => 'order_test_4001',
        ]);

        $paymentApi = Mockery::mock();
        $paymentApi->shouldReceive('fetch')
            ->once()
            ->with('pay_test_4001')
            ->andReturn((object) [
                'order_id' => 'order_test_4001',
                'status' => 'captured',
            ]);

        $razorpay = new class($paymentApi)
        {
            public $payment;
            public $utility;

            public function __construct($payment)
            {
                $this->payment = $payment;
                $this->utility = Mockery::mock();
            }
        };

        $controller = new class($razorpay) extends PaymentController
        {
            public bool $shiprocketSynced = false;

            protected function syncShiprocketOrder(Order $order): void
            {
                $this->shiprocketSynced = $order->order_number === 'MOL-TEST-4001'
                    && $order->payment_status === 'paid';
            }
        };

        $response = $controller->verifyPayment(new Request([
            'order_number' => 'MOL-TEST-4001',
            'razorpay_payment_id' => 'pay_test_4001',
        ]));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($controller->shiprocketSynced);
    }
}
