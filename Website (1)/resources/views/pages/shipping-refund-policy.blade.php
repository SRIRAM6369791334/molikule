@extends('layouts.app')

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; letter-spacing: 2px; background: #ffffff; padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(218, 165, 32, 0.25);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.3);">Home</a></li>
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.4);">Shipping & Refund</li>
                </ul>
            </div>
        </div>
    </section>

<!-- policy-section -->
<section class="policy-section pt_100 pb_100" style="background: #fdfdfd;">
    <div class="auto-container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto 60px;">
            <h2 style="font-size: 48px; font-weight: 900; color: #0f172a; letter-spacing: -2px; line-height: 1.1;">Shipping & <span style="color: #bbd700;">Refund.</span></h2>
            <p style="color: #64748b; font-size: 18px; margin-top: 20px;">Precision logistics and transparent refunds—ensuring your molecular green care arrives safely.</p>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div style="background: #ffffff; padding: 60px; border-radius: 40px; box-shadow: 0 15px 45px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; border-top: 4px solid #bbd700;">
                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">01. Pan-India Shipping</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">We process all orders within 24-48 hours. Through our premium integration with <strong>Shiprocket</strong>, we offer free shipping across India with an average transit time of 3-7 business days. Real-time tracking coordinates are provided for every shipment.</p>
                    </div>
                    
                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">02. Satisfaction Commitment</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">Our 7-day return infrastructure ensures that your purchase is protected. If the product arrives damaged or does not meet our molecular standards, we provide seamless refunds to your original payment source following a brief quality verification.</p>
                    </div>

                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">03. Refund Orchestration</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">Approved refunds are processed instantly via our digital gateways (Razorpay). Depending on your banking institution, funds typically materialize within 5-7 business days. We prioritize financial transparency in every transaction.</p>
                    </div>

                    <div class="btn-box mt_40">
                        {{-- <a href="{{ route('shop') }}" class="theme-btn py-4 px-5" style="background: #0f172a; color: #ffffff; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; transition: all 0.4s;" onmouseover="this.style.background='#bbd700'; this.style.color='#0f172a'" onmouseout="this.style.background='#0f172a'; this.style.color='#ffffff'">Return to Shop</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
