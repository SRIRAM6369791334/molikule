@extends('layouts.app')

@push('css')
<style>
    .courier-card {
        border: 2px solid #f1f5f9;
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: left;
        position: relative;
        overflow: hidden;
    }
    .courier-card:hover {
        border-color: #bbd700;
        background: #fcfdf9;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(187, 215, 0, 0.1);
    }
    .courier-card.selected {
        border-color: #bbd700;
        background: #fcfdf9;
        box-shadow: 0 10px 25px rgba(187, 215, 0, 0.15);
    }
    .courier-card.selected::after {
        content: '\f058';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 15px;
        right: 15px;
        color: #bbd700;
        font-size: 20px;
    }
    .courier-card .partner-icon {
        width: 50px;
        height: 50px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        border: 1px solid #f1f5f9;
    }
    .courier-card .partner-icon i {
        color: #0f172a;
        font-size: 22px;
    }
    
    .billing-content, .order-summary {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-top: 4px solid #bbd700;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 45px rgba(0,0,0,0.05);
    }
    
    .form-group label {
        font-weight: 700 !important;
        color: #0f172a !important;
        margin-bottom: 10px !important;
        font-size: 14px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    .form-group input, .form-group select, .form-group textarea {
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 12px 20px !important;
        font-weight: 600 !important;
        transition: all 0.3s !important;
    }
    
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #bbd700 !important;
        box-shadow: 0 0 0 4px rgba(187, 215, 0, 0.1) !important;
        outline: none !important;
    }
    .swal2-title {
        font-size: 20px !important;
        font-weight: 700 !important;
        padding-top: 20px !important;
    }
    .swal2-html-container {
        padding: 0 1.5rem 1.5rem !important;
        margin: 1em 0 0 !important;
    }
    .payment-option .check-box input:disabled + label {
        color: #999;
        cursor: not-allowed;
    }
    
    @media (max-width: 991px) {
        #checkout-form {
            display: flex;
            flex-direction: column-reverse;
        }
        .order-column {
            margin-bottom: 30px;
        }
    }
</style>
@endpush

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; letter-spacing: 2px; background: #ffffff; padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(218, 165, 32, 0.25);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.3);">Home</a></li>
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.4);">Checkout</li>
                </ul>
            </div>
        </div>
    </section>
<!-- checkout-section -->
<section class="checkout-section pt_100 pb_80">
    <div class="auto-container">
        <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}" class="row clearfix">
            @csrf
            <div class="col-lg-7 col-md-12 col-sm-12 billing-column">
                <div class="billing-content">
                    <h3 style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px;">Billing Details</h3>
                    <div class="form-inner">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>First Name<span>*</span></label>
                                    <input type="text" name="fname" id="fname" value="{{ auth()->user()->name ?? old('fname') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Last Name<span>*</span></label>
                                    <input type="text" name="lname" id="lname" value="{{ old('lname') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Email Address<span>*</span></label>
                                    <input type="email" name="email" id="email" value="{{ auth()->user()->email ?? old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Phone Number<span>*</span></label>
                                    <input type="text" name="phone" id="phone" value="{{ auth()->user()->phone_number ?? old('phone') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Street Address<span>*</span></label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Flat, House no., Building, Company, Apartment" required>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Apartment, suite, unit, etc. (optional)</label>
                                    <input type="text" name="address_line_2" id="address_line_2" value="{{ old('address_line_2') }}" placeholder="Area, Street, Sector, Village">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Landmark (optional)</label>
                                    <input type="text" name="landmark" id="landmark" value="{{ old('landmark') }}" placeholder="Near by place">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Town / City<span>*</span></label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>State<span>*</span></label>
                                    <input type="text" name="state" id="state" value="{{ old('state') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 field-column">
                                <div class="form-group">
                                    <label>Postcode / ZIP<span>*</span></label>
                                    <div class="d-flex gap-2">
                                        <input type="text" name="zip" id="pincode" value="{{ old('zip') }}" maxlength="6" style="flex: 1;" required>
                                        <button type="button" id="open-courier-btn" class="theme-btn py-0 px-3" style="font-size: 14px; height: 53px; display: none;">Select Partner</button>
                                    </div>
                                    <div id="pincode-loader" class="mt-1 small text-primary"><i class="fas fa-spinner fa-spin"></i> Checking serviceability...</div>
                                    <div id="pincode-error" class="mt-1 small text-danger"></div>
                                    
                                    <!-- Selected Partner Preview -->
                                    <div id="selected-partner-preview" class="mt-3 p-3 border rounded bg-light" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong id="display-partner-name"></strong><br>
                                                <small id="display-partner-etd" class="text-muted"></small>
                                            </div>
                                            <div class="text-end">
                                                <span id="display-partner-rate" class="fw-bold"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="shipping_rate" id="shipping_rate" value="0">
                                    <input type="hidden" name="courier_id" id="courier_id" value="">
                                    <input type="hidden" name="courier_name" id="courier_name" value="">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 field-column" style="display:none">
                                <div class="form-group">
                                    <label>Order Notes (optional)</label>
                                    <textarea name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('order_notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5 col-md-12 col-sm-12 order-column">
                <div class="order-summary">
                    <h3 style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px;">Order Summary</h3>
                    <div class="order-info">
                        <div class="title-box">
                            <span class="text">Product</span>
                            <span class="text">Total</span>
                        </div>
                        <div class="order-product">
                            @foreach($cartItems as $item)
                            <div class="single-item">
                                <div class="product-box">
                                    <figure class="image-box"><img src="{{ $item['image'] }}" alt="{{ $item['product']->name }}" style="object-fit: contain;"></figure>
                                    <h6>
                                        {{ $item['product']->name }}
                                        @if($item['variant']) 
                                            <br><small class="text-muted">({{ $item['variant']->variant_label }})</small> 
                                        @endif 
                                        <span class="ms-1">x {{ $item['quantity'] }}</span>
                                    </h6>
                                </div>
                                <h4 class="price">{{ formatPrice($item['row_total']) }}</h4>
                            </div>
                            @endforeach
                        </div>
                        <ul class="cost-box mt_20">
                            <li><h4><span>Subtotal</span></h4><h4 id="summary-subtotal" data-value="{{ $cartTotal }}">{{ formatPrice($cartTotal) }}</h4></li>
                            @if($couponAmount > 0)
                            <li class="text-success"><h4><span>Coupon ({{ session('coupon.code') }})</span></h4><h4 id="summary-coupon" data-value="{{ $couponAmount }}">-{{ formatPrice($couponAmount) }}</h4></li>
                            @endif
                            <li><h4><span>Shipping</span></h4><h4 id="summary-shipping">--</h4></li>
                        </ul>

                        <div style="display: flex; justify-content: space-between; margin-top: 30px; padding-top: 30px; border-top: 2px dashed #f1f5f9;">
                            <span style="font-size: 20px; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: 1px;">Grand Total</span>
                            <span id="summary-total" style="font-size: 32px; font-weight: 900; color: #bbd700; letter-spacing: -1px;">{{ formatPrice($cartTotal - $couponAmount) }}</span>
                        </div>
                        <div class="payment-method mt_40">
                            <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Payment Method</h3>
                            <div class="payment-inner" style="background: #f8fafc; padding: 25px; border-radius: 15px; border: 1px solid #f1f5f9;">
                                <div class="check-box mb_15" style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                                    <input class="check" type="radio" id="payment_razorpay" name="payment_method" value="razorpay" checked style="margin-top: 5px; accent-color: #bbd700;">
                                    <label for="payment_razorpay" style="cursor: pointer;">
                                        <span style="font-weight: 800; color: #0f172a; font-size: 15px; display: block;">Prepaid (Razorpay)</span>
                                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Pay securely via UPI, Cards, or Netbanking.</span>
                                    </label>
                                </div>
                                <div class="check-box" id="cod-wrapper" style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; padding-top: 15px; border-top: 1px dashed #e2e8f0; margin-top: 15px;">
                                    <input class="check" type="radio" id="payment_cod" name="payment_method" value="cash" style="margin-top: 5px; accent-color: #bbd700;">
                                    <label for="payment_cod" style="cursor: pointer;">
                                        <span style="font-weight: 800; color: #0f172a; font-size: 15px; display: block;">Cash on Delivery</span>
                                        <span id="cod-availability-msg" style="font-size: 13px; color: #64748b; font-weight: 500;">Checking availability...</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="btn-box mt_40">
                            <button type="submit" id="submit-order-btn" class="theme-btn py-4 w-100" style="background: #0f172a; color: #ffffff; border-radius: 15px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; transition: all 0.4s; box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);" onmouseover="this.style.background='#bbd700'; this.style.color='#0f172a'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='#0f172a'; this.style.color='#ffffff'; this.style.transform='translateY(0)'">
                                Place Order Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$(document).ready(function() {
    const pincodeInput = $('#pincode');
    const courierWrapper = $('#courier-selection-wrapper');
    const courierList = $('#courier-list');
    const pincodeLoader = $('#pincode-loader');
    const pincodeError = $('#pincode-error');
    
    // Summary elements
    const subtotal = parseFloat($('#summary-subtotal').data('value'));
    const couponAmount = parseFloat($('#summary-coupon').data('value')) || 0;
    const shippingDisplay = $('#summary-shipping');
    const totalDisplay = $('#summary-total');
    const placeOrderBtn = $('#place-order-btn');

    const codRadio = $('#payment_cod');
    const codMsg = $('#cod-availability-msg');

    let currentCouriers = [];

    // Trigger check when 6 digits are entered
    pincodeInput.on('input', function() {
        const pin = $(this).val();
        if (pin.length === 6 && /^\d+$/.test(pin)) {
            checkServiceability(pin);
        } else {
            resetCouriers();
        }
    });

    function checkServiceability(pin) {
        pincodeLoader.show();
        pincodeError.text('');
        resetCouriers();

        $.ajax({
            url: "{{ route('shipping.check_couriers') }}",
            type: "GET",
            data: { 
                delivery_pincode: pin,
                cod_amount: subtotal 
            },
            success: function(response) {
                pincodeLoader.hide();
                if (response.success && response.couriers.length > 0) {
                    currentCouriers = response.couriers;
                    $('#open-courier-btn').fadeIn();
                    // Auto-open on first valid pincode entry
                    openCourierSelection();
                } else {
                    pincodeError.text('Shipping not available for this pincode.');
                }
            },
            error: function() {
                pincodeLoader.hide();
                pincodeError.text('Error checking serviceability. Please try again.');
            }
        });
    }

    $('#open-courier-btn').on('click', openCourierSelection);

    function openCourierSelection() {
        if (currentCouriers.length === 0) return;

        let courierHtml = '<div class="courier-selection-grid text-start" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">';
        currentCouriers.forEach((c) => {
            courierHtml += `
                <div class="courier-card" onclick="handleCourierPick(${c.courier_id}, '${c.courier_name}', ${c.rate}, '${c.etd}', ${c.cod_available})">
                    <div class="d-flex align-items-center">
                        <div class="partner-icon">
                            <i class="fas fa-truck-moving text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold" style="color: #333;">${c.courier_name}</h6>
                                <span class="fw-bold" style="color: #ff5722; font-size: 16px;">₹${c.rate}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted" style="font-size: 12px;"><i class="far fa-calendar-alt me-1"></i>Est: ${c.etd}</small>
                                ${c.cod_available ? '<span class="text-success fw-bold" style="font-size:10px;"><i class="fas fa-check-circle me-1"></i>COD Available</span>' : '<span class="text-muted" style="font-size:10px;"><i class="fas fa-info-circle me-1"></i>Prepaid Only</span>'}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        courierHtml += '</div>';

        Swal.fire({
            title: 'Select Delivery Partner',
            html: courierHtml,
            showConfirmButton: false,
            showCloseButton: true,
            width: '500px',
            padding: '1rem',
            customClass: {
                popup: 'rounded-4 shadow-lg'
            }
        });
    }

    // Define globally to be called from onclick in SWAL
    window.handleCourierPick = function(id, name, rate, etd, cod) {
        Swal.close();
        
        const courierData = {
            courier_id: id,
            courier_name: name,
            rate: rate,
            etd: etd,
            cod_available: cod == 1
        };
        
        selectCourier(courierData);
        
        // Update Preview
        $('#display-partner-name').text(name);
        $('#display-partner-etd').text('Estimated Delivery: ' + etd);
        $('#display-partner-rate').text('₹' + rate);
        $('#selected-partner-preview').fadeIn();

        // Second Success SweetAlert as requested
        Swal.fire({
            icon: 'success',
            title: 'Shipping Updated!',
            text: `${name} has been selected. Your total has been updated to ${totalDisplay.text()}.`,
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timerProgressBar: true
        });
    };

    function selectCourier(courier) {
        $('#shipping_rate').val(courier.rate);
        $('#courier_id').val(courier.courier_id);
        $('#courier_name').val(courier.courier_name);
        
        shippingDisplay.text('₹' + courier.rate);
        const finalTotal = (subtotal - couponAmount) + parseFloat(courier.rate);
        totalDisplay.text('₹' + Math.max(0, finalTotal).toFixed(2));

        
        // Update COD availability
        if (courier.cod_available) {
            codRadio.prop('disabled', false);
            codMsg.text('Available for this partner').removeClass('text-danger').addClass('text-muted');
        } else {
            codRadio.prop('disabled', true);
            codRadio.prop('checked', false);
            $('#payment_razorpay').prop('checked', true);
            codMsg.text('Not available for this partner').removeClass('text-muted').addClass('text-danger');
        }
        
        placeOrderBtn.prop('disabled', false);
    }

    function resetCouriers() {
        courierWrapper.hide();
        shippingDisplay.text('--');
        totalDisplay.text('₹' + (subtotal - couponAmount).toFixed(2));
        placeOrderBtn.prop('disabled', true);

    }

    // Ajax Form Submission
    $('#checkout-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $('#place-order-btn');
        submitBtn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: "{{ route('checkout.process') }}",
            type: "POST",
            dataType: "json",
            data: formData,
            success: function(response) {
                if (response.success) {
                    if (response.payment_method === 'razorpay') {
                        triggerRazorpay(response);
                    } else if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    alert(response.message || 'Error processing order');
                    submitBtn.prop('disabled', false).text('Place Order');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Internal Server Error';
                alert(msg);
                submitBtn.prop('disabled', false).text('Place Order');
            }
        });
    });

    function triggerRazorpay(data) {
        // Create the Razorpay Order first, then open the modal with the order_id
        $.ajax({
            url: "{{ route('razorpay.create_order') }}",
            type: "POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                order_number: data.order_number,
                amount: data.amount
            },
            success: function(rzpOrder) {
                if (!rzpOrder || !rzpOrder.id) {
                    alert('Failed to initialize payment. Razorpay order id is missing.');
                    $('#place-order-btn').prop('disabled', false).text('Place Order');
                    return;
                }

                // Capture the Razorpay order ID in a local variable so the
                // handler closure always has it — avoids empty-string / null issue
                const capturedRazorpayOrderId = rzpOrder.id;

                const options = {
                    "key": "{{ config('services.razorpay.key_id') }}",
                    "amount": data.amount * 100,
                    "currency": "INR",
                    "name": "Molikule Website",
                    "description": "Order #" + data.order_number,
                    "image": "{{ asset('assets/images/logo.png') }}",
                    "order_id": capturedRazorpayOrderId,
                    "notes": {
                        "order_number": data.order_number
                    },
                    "handler": function (response) {
                        // response.razorpay_order_id is returned by Razorpay SDK;
                        // fall back to capturedRazorpayOrderId which is always set.
                        verifyPayment(response, data.order_number, response.razorpay_order_id || capturedRazorpayOrderId);
                    },
                    "prefill": {
                        "name": data.customer.name,
                        "email": data.customer.email,
                        "contact": data.customer.phone
                    },
                    "theme": {
                        "color": "#ff5722"
                    },
                    "modal": {
                        "ondismiss": function() {
                            $('#place-order-btn').prop('disabled', false).text('Place Order');
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.open();
            },
            error: function() {
                alert('Failed to initialize payment. Please try COD.');
                $('#place-order-btn').prop('disabled', false).text('Place Order');
            }
        });
    }

    function verifyPayment(response, orderNumber, razorpayOrderId) {
        if (!response.razorpay_payment_id) {
            alert('Razorpay did not return a payment id. Please contact support if money was debited.');
            $('#place-order-btn').prop('disabled', false).text('Place Order');
            return;
        }

        // Build payload — only include razorpay_order_id and razorpay_signature
        // when they are real values; sending '' causes server-side resolution failures.
        const resolvedOrderId = response.razorpay_order_id || razorpayOrderId || null;
        const payload = {
            _token: "{{ csrf_token() }}",
            order_number: orderNumber,
            razorpay_payment_id: response.razorpay_payment_id
        };
        if (resolvedOrderId) payload.razorpay_order_id = resolvedOrderId;
        if (response.razorpay_signature) payload.razorpay_signature = response.razorpay_signature;

        $.ajax({
            url: "{{ route('razorpay.verify') }}",
            type: "POST",
            dataType: "json",
            data: payload,
            success: function(res) {
                if(res.status === 'success') {
                    window.location.href = "{{ route('checkout.success') }}";
                } else {
                    alert('Payment verification failed. Support Team will contact you.');
                    $('#place-order-btn').prop('disabled', false).text('Place Order');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Internal Server Error';
                alert('Error verifying payment: ' + msg);
                $('#place-order-btn').prop('disabled', false).text('Place Order');
            }
        });
    }
});
</script>
@endpush
