@extends('layouts.app')

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; letter-spacing: 2px; background: #ffffff; padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(218, 165, 32, 0.25);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.3);">Home</a></li>
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.4);">Terms & Conditions</li>
                </ul>
            </div>
        </div>
    </section>

<!-- policy-section -->
<section class="policy-section pt_100 pb_100" style="background: #fdfdfd;">
    <div class="auto-container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto 60px;">
            <h2 style="font-size: 48px; font-weight: 900; color: #0f172a; letter-spacing: -2px; line-height: 1.1;">Terms &<span style="color: #bbd700;">Conditions.</span></h2>
            <p style="color: #64748b; font-size: 18px; margin-top: 20px;">The foundational legal framework orchestrating a harmonious relationship with our community.</p>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div style="background: #ffffff; padding: 60px; border-radius: 40px; box-shadow: 0 15px 45px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; border-top: 4px solid #bbd700;">
                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">01. Contractual Agreement</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">By engaging with our digital platform, you acknowledge your acceptance of these protocols, forming a legally binding infrastructure for all transactional interactions. We mandate the ethical and responsible utilization of our chemical solutions in all environments.</p>
                    </div>
                    
                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">02. Intellectual Property</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">All proprietary formulations, designs, and content are the exclusive intellectual property of Molikule, protected under international copyright legislation. Unauthorized replication of our molecular framework is strictly prohibited.</p>
                    </div>

                    <div class="mb_50">
                        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">03. Jurisdiction & Governance</h3>
                        <p style="color: #64748b; font-size: 16px; line-height: 1.8;">All legal disputes are governed by the courts of Dharmapuri, Tamil Nadu, ensuring a localized legal anchor. We maintain a dynamic policy framework, updating our terms to reflect evolving service standards and sustainable innovation.</p>
                    </div>

                    <div class="btn-box mt_40">
                        {{-- <a href="{{ route('shop') }}" class="theme-btn py-4 px-5" style="background: #0f172a; color: #ffffff; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; transition: all 0.4s;" onmouseover="this.style.background='#bbd700'; this.style.color='#0f172a'" onmouseout="this.style.background='#0f172a'; this.style.color='#ffffff'">Explore Shop</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
