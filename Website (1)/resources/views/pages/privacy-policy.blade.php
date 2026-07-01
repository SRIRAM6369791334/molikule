@extends('layouts.app')

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; letter-spacing: 2px; background: #ffffff; padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(218, 165, 32, 0.25);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.3);">Home</a></li>
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.4);">Privacy Policy</li>
                </ul>
            </div>
        </div>
    </section>

<!-- policy-section -->
<section class="policy-section pt_100 pb_100" style="background: #fdfdfd;">
    <div class="auto-container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto 60px;">
            <h2 style="font-size: 48px; font-weight: 900; color: #0f172a; letter-spacing: -2px; line-height: 1.1;">Privacy <span style="color: #bbd700;">Policy.</span></h2>
            <p style="color: #64748b; font-size: 18px; margin-top: 20px;">Your privacy is our molecular priority. Learn how we handle and protect your digital identity.</p>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div style="background: #ffffff; padding: 60px; border-radius: 40px; box-shadow: 0 15px 45px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; border-top: 4px solid #bbd700;">
                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">01. Information Governance</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">We collect only the essential data required to orchestrate your experience—ranging from identity metadata to logistical coordinates for order fulfillment. This information is used exclusively to improve our molecular green care services.</p>
                    </div>
                    
                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">02. Cyber Security Protocols</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">Our infrastructure utilizes 256-bit encryption and PCI-DSS compliant gateways like <strong>Razorpay</strong> to ensure your fiscal integrity is never compromised. We employ state-of-the-art security layers to protect your personal portal.</p>
                    </div>

                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">03. Data Retention & Rights</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">You have the right to access, rectify, or delete your personal information at any time. We retain data only as long as necessary to fulfill the purposes outlined in this legal framework.</p>
                    </div>

                    <div class="btn-box mt_40">
                        {{-- <a href="{{ route('contact') }}" class="theme-btn py-4 px-5" style="background: #0f172a; color: #ffffff; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; transition: all 0.4s;" onmouseover="this.style.background='#bbd700'; this.style.color='#0f172a'" onmouseout="this.style.background='#0f172a'; this.style.color='#ffffff'">Contact Data Protection Officer</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- policy-section end -->

@endsection
