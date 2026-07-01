@extends('layouts.app')

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; letter-spacing: 2px; background: #ffffff; padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(218, 165, 32, 0.25);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.3);">Home</a></li>
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.4);">Shopping Cart</li>
                </ul>
            </div>
        </div>
    </section>



    <style>
        @media (max-width: 767px) {
            .cart-coupon-box {
                flex-direction: column !important;
            }
            .cart-coupon-box input, .cart-coupon-box button {
                width: 100% !important;
            }
            .cart-total-box {
                margin-top: 20px !important;
                padding: 20px !important;
            }
            .page-title .bread-crumb {
                font-size: 12px !important;
                padding: 10px 20px !important;
            }
        }
    </style>

    <!-- cart section -->
    <section class="cart-section pt_80 pb_80" style="background: #fdfdfd;">
        <div class="auto-container">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: 4px solid #bbd700; border-radius: 20px; box-shadow: 0 15px 45px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 50px;">
                <div class="table-outer" style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
                    <table class="cart-table" style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead style="background: #fcfdf9; border-bottom: 2px solid #f1f5f9;">
                            <tr>
                                <th style="padding: 25px 30px; text-align: left; font-weight: 800; color: #0f172a; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px;">Product</th>
                                <th style="padding: 25px 30px; text-align: left; font-weight: 800; color: #0f172a; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px;">Variant</th>
                                <th style="padding: 25px 30px; text-align: left; font-weight: 800; color: #0f172a; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px;">Price</th>
                                <th style="padding: 25px 30px; text-align: left; font-weight: 800; color: #0f172a; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px;">Quantity</th>
                                <th style="padding: 25px 30px; text-align: left; font-weight: 800; color: #0f172a; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px;">Subtotal</th>
                                <th style="padding: 25px 30px;">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cartItems as $item)
                            <tr id="cart-item-{{ $item['item_key'] }}" style="border-bottom: 1px solid #f1f5f9; transition: background 0.3s;" onmouseover="this.style.background='#fcfdf9'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 30px;">
                                    <div style="display: flex; align-items: center; gap: 20px;">
                                        <div style="width: 80px; height: 80px; border: 1px solid #f1f5f9; border-radius: 12px; overflow: hidden; padding: 8px; background: #ffffff; flex-shrink: 0;">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['item_name'] }}" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                        <div>
                                            <a href="{{ route('shop.show', $item['product']->slug) }}" style="font-weight: 800; color: #0f172a; font-size: 16px; display: block; margin-bottom: 4px; white-space: normal; line-height: 1.4;">{{ $item['product']->name }}</a>
                                            <span style="font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">SKU: {{ optional($item['variant'])->sku ?? ($item['product']->sku ?? 'MK-' . str_pad($item['product']->product_id ?? $item['product']->id, 4, '0', STR_PAD_LEFT)) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px;">
                                    @if($item['variant'])
                                        <span style="display: inline-block; padding: 4px 12px; background: #f1f5f9; border-radius: 6px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; white-space: nowrap;">{{ $item['variant']->variant_label }}</span>
                                    @else
                                        <span style="font-size: 14px; color: #cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 30px; color: #0f172a; font-weight: 600; font-size: 16px;">{{ formatPrice($item['price']) }}</td>
                                <td style="padding: 30px;">
                                    <div style="display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 12px; width: fit-content; background: #ffffff; overflow: hidden; height: 48px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                        <button type="button" onclick="decrementQty('{{ $item['item_key'] }}')" 
                                                style="width: 45px; height: 100%; border: none; background: #ffffff; color: #0f172a; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; border-right: 1px solid #f1f5f9;"
                                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                            <i class="fas fa-minus" style="font-size: 11px;"></i>
                                        </button>
                                        <input type="number" id="qty-{{ $item['item_key'] }}" value="{{ $item['quantity'] }}" min="1" 
                                               onchange="updateCart('{{ $item['item_key'] }}', this.value)"
                                               style="width: 55px; border: none; text-align: center; font-weight: 800; color: #0f172a; -moz-appearance: textfield; appearance: textfield; background: transparent; padding: 0; font-size: 16px;">
                                        <button type="button" onclick="incrementQty('{{ $item['item_key'] }}')" 
                                                style="width: 45px; height: 100%; border: none; background: #ffffff; color: #0f172a; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; border-left: 1px solid #f1f5f9;"
                                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                            <i class="fas fa-plus" style="font-size: 11px;"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="row-total" style="padding: 30px; font-weight: 800; color: #0f172a; font-size: 18px;">{{ formatPrice($item['row_total']) }}</td>
                                <td style="padding: 30px; text-align: right;">
                                    <button onclick="removeFromCart('{{ $item['item_key'] }}')" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #94a3b8; border: 1px solid #f1f5f9; background: #ffffff; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.color='#ef4444'; this.style.borderColor='#fee2e2'; this.style.background='#fffafb'" onmouseout="this.style.color='#94a3b8'; this.style.borderColor='#f1f5f9'; this.style.background='#ffffff'">
                                        <i class="icon-38" style="font-size: 14px;"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="padding: 100px 30px; text-align: center; color: #64748b;">
                                    <div style="margin-bottom: 20px;">
                                        <i class="fas fa-shopping-basket" style="font-size: 60px; color: #f1f5f9;"></i>
                                    </div>
                                    <p style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Your cart is empty.</p>
                                    <p style="margin-bottom: 30px;">Looks like you haven't added anything yet.</p>
                                    <a href="{{ route('shop') }}" style="display: inline-block; background: #0f172a; color: #ffffff; padding: 15px 40px; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">Start Shopping</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($cartItems) > 0)
                <div style="padding: 40px; background: #fcfdf9; border-top: 2px solid #f1f5f9;">
                    <div class="row clearfix" style="align-items: flex-start;">
                        <div class="col-lg-6 col-md-12 col-sm-12 mb-5 mb-lg-0">
                            <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Apply Coupon</h4>
                            <div class="cart-coupon-box" style="display: flex; gap: 12px; background: #ffffff; padding: 8px; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                                <input type="text" id="coupon-code" placeholder="Enter coupon code" 
                                       value="{{ session('coupon.code') }}" {{ session()->has('coupon') ? 'disabled' : '' }}
                                       style="flex: 1; padding: 12px 20px; border: none; font-weight: 600; color: #0f172a;">
                                <button type="button" id="apply-coupon-btn" onclick="applyCoupon()" 
                                        style="{{ session()->has('coupon') ? 'display: none;' : '' }} background: #bbd700; color: #0f172a; padding: 12px 30px; border-radius: 10px; font-weight: 800; border: none; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.background='#0f172a'; this.style.color='#ffffff'" onmouseout="this.style.background='#bbd700'; this.style.color='#0f172a'">
                                    Apply
                                </button>
                                <button type="button" id="remove-coupon-btn" onclick="removeCoupon()" 
                                        style="{{ session()->has('coupon') ? '' : 'display: none;' }} background: #ef4444; color: #ffffff; padding: 12px 30px; border-radius: 10px; font-weight: 800; border: none; text-transform: uppercase; letter-spacing: 1px;">
                                    Remove
                                </button>
                            </div>
                            <div id="coupon-message" style="margin-top: 15px; font-size: 14px; font-weight: 600;"></div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="cart-total-box" style="max-width: 450px; margin-left: auto; background: #ffffff; padding: 35px; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px;">
                                    <span style="color: #64748b; font-weight: 600;">Subtotal</span>
                                    <span class="cart-subtotal" style="font-weight: 800; color: #0f172a;">{{ formatPrice($cartTotal) }}</span>
                                </div>
                                <div id="coupon-row" style="{{ session()->has('coupon') ? '' : 'display: none;' }} display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px; color: #10b981; font-weight: 700;">
                                    <span>Discount (<span id="coupon-applied-code">{{ session('coupon.code') }}</span>)</span>
                                    <span id="coupon-discount-amount">-{{ formatPrice(session('coupon.discount', 0)) }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-top: 25px; padding-top: 25px; border-top: 2px dashed #f1f5f9;">
                                    <span style="font-size: 12px;font-weight: 900;color: #0f172a;text-transform: uppercase;letter-spacing: 1px;">Grand Total</span>
                                    <span class="cart-total" style="font-size: 22px;font-weight: 900;color: #bbd700;letter-spacing: -1px;">{{ formatPrice($cartTotal - session('coupon.discount', 0)) }}</span>
                                </div>
                                <a href="{{ route('checkout') }}" style="display: block; width: 100%; background: #0f172a; color: #ffffff; text-align: center; padding: 22px; border-radius: 15px; font-weight: 900; margin-top: 35px; text-transform: uppercase; letter-spacing: 2px; transition: all 0.4s; box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);" onmouseover="this.style.background='#bbd700'; this.style.color='#0f172a'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='#0f172a'; this.style.color='#ffffff'; this.style.transform='translateY(0)'">
                                    Checkout Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>



        <!-- cart section end -->
@endsection

@push('scripts')
<script>
function incrementQty(itemKey) {
    const input = document.getElementById(`qty-${itemKey}`);
    const newVal = parseInt(input.value) + 1;
    input.value = newVal;
    updateCart(itemKey, newVal);
}

function decrementQty(itemKey) {
    const input = document.getElementById(`qty-${itemKey}`);
    const currentVal = parseInt(input.value);
    if (currentVal > 1) {
        const newVal = currentVal - 1;
        input.value = newVal;
        updateCart(itemKey, newVal);
    }
}

function updateCart(itemKey, quantity) {
    if (quantity < 1) return;
    
    $.ajax({
        url: "{{ route('cart.update') }}",
        method: "PATCH",
        data: {
            _token: "{{ csrf_token() }}",
            item_key: itemKey,
            quantity: quantity
        },
        success: function(response) {
            if (response.success) {
                // Live DOM updates
                const row = $(`#cart-item-${itemKey}`);
                row.find('.row-total').text(response.item_total);
                
                $('.cart-subtotal').text(response.subtotal);
                $('.cart-total').text(response.grandTotal);
                
                if (response.discount !== '₹0.00' && response.discount !== '0.00') {
                    // Update discount row if visible
                    $('.text-success h5').text('-' + response.discount);
                }

                // Update cart count in header if it exists
                $('.cart-count').text(response.cartCount);
            } else {
                Swal.fire('Oops...', response.message || 'Error updating cart', 'error');
            }
        }
    });
}

function removeFromCart(itemKey) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Remove this item from your cart?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('cart.remove') }}",
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                    item_key: itemKey
                },
                success: function(response) {
                    if (response.success) {
                        if (response.isEmpty) {
                            location.reload(); // Show empty cart view
                        } else {
                            $(`#cart-item-${itemKey}`).fadeOut(300, function() {
                                $(this).remove();
                                $('.cart-subtotal').text(response.subtotal);
                                $('.cart-total').text(response.grandTotal);
                                $('.cart-count').text(response.cartCount);
                            });
                        }
                    }
                }
            });
        }
    });
}

function applyCoupon() {
    const code = $('#coupon-code').val();
    if (!code) return;

    $.ajax({
        url: "{{ route('cart.coupon.apply') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            code: code
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Coupon Applied!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                // Live Update
                $('#coupon-code').val(code).prop('disabled', true);
                $('#apply-coupon-btn').hide();
                $('#remove-coupon-btn').show();
                
                $('#coupon-applied-code').text(code);
                $('#coupon-discount-amount').text('-' + response.discount);
                $('#coupon-row').fadeIn();
                
                $('.cart-total').text(response.grandTotal);
            } else {
                Swal.fire('Oops...', response.message, 'error');
            }
        }
    });
}

function removeCoupon() {
    $.ajax({
        url: "{{ route('cart.coupon.remove') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function(response) {
            if (response.success) {
                // Live Update
                $('#coupon-code').val('').prop('disabled', false);
                $('#apply-coupon-btn').show();
                $('#remove-coupon-btn').hide();
                
                $('#coupon-row').fadeOut();
                $('.cart-total').text(response.grandTotal);
            }
        }
    });
}


</script>
@endpush
