@extends('layouts.app')


@section('title', 'Contact Molikule Green Care | Connect With Us')
@section('meta_description', "Molikule Green Care � India's trusted provider of eco-conscious hygiene, laundry care, auto care, and specialty cleaning solutions for homes, institutions, and industries.")
@section('meta_keywords', 'sustainable hygiene solutions India, eco-friendly cleaning products, institutional hygiene products, healthcare disinfectants India, laundry care solutions for hotels, auto care chemicals India, green cleaning company India, specialty chemical company India, commercial cleaning products, facility management hygiene solutions')
@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; letter-spacing: 2px; background: #ffffff; padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(218, 165, 32, 0.25);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.3);">Home</a></li>
                    {{-- <li style="color: #bbd700;">•</li> --}}
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.4);">Contact Us</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- contact-info-section -->
    <section class="contact-info-section pt_100 pb_70" style="background: #fdfdfd;">
        <div class="auto-container">
            <div style="text-align: center; max-width: 700px; margin: 0 auto 60px;">
                <h2 style="font-size: 48px; font-weight: 900; color: #0f172a; letter-spacing: -2px; line-height: 1.1;">Get in <span style="color: #bbd700;">Touch.</span></h2>
                <p style="color: #64748b; font-size: 18px; margin-top: 15px;">Have questions about our molecular green care? Our team is ready to assist you.</p>
            </div>
            <div class="row clearfix">
                <div class="col-lg-4 col-md-6 col-sm-12 info-column mb_30">
                    <div style="background: #ffffff; padding: 40px; border-radius: 35px; box-shadow: 0 20px 40px rgba(0,0,0,0.06), 0 5px 15px rgba(0,0,0,0.03); height: 100%; border: 1px solid #f1f5f9; transition: all 0.3s ease; text-align: center;">
                        <div style="width: 80px; height: 80px; background: rgba(187, 215, 0, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                            <i class="fas fa-map-marker-alt" style="color: #bbd700; font-size: 26px;"></i>
                        </div>
                        <h4 style="font-weight: 800; color: #0f172a; margin-bottom: 15px;">Our Location</h4>
                        <p style="color: #64748b; line-height: 1.7;">Plot. No. 4, SIDCO Industrial Estate, Selliampatti ( PO ), Dharmapuri – 636 809, Tamilnadu.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 info-column mb_30">
                    <div style="background: #ffffff; padding: 40px; border-radius: 35px; box-shadow: 0 20px 40px rgba(0,0,0,0.06), 0 5px 15px rgba(0,0,0,0.03); height: 100%; border: 1px solid #f1f5f9; transition: all 0.3s ease; text-align: center;">
                        <div style="width: 80px; height: 80px; background: rgba(26, 159, 212, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                            <i class="fas fa-envelope-open-text" style="color: #1a9fd4; font-size: 26px;"></i>
                        </div>
                        <h4 style="font-weight: 800; color: #0f172a; margin-bottom: 15px;">Email Address</h4>
                        <p style="color: #64748b; line-height: 1.7;">
                            <a href="mailto:mgc@molikule.com" style="color: #64748b; transition: color 0.3s;">mgc@molikule.com</a><br />
                            <!-- <a href="mailto:support@molikule.com" style="color: #64748b; transition: color 0.3s;">support@molikule.com</a> -->
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 info-column mb_30">
                    <div style="background: #ffffff; padding: 40px; border-radius: 35px; box-shadow: 0 20px 40px rgba(0,0,0,0.06), 0 5px 15px rgba(0,0,0,0.03); height: 100%; border: 1px solid #f1f5f9; transition: all 0.3s ease; text-align: center;">
                        <div style="width: 80px; height: 80px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                            <i class="fas fa-headset" style="color: #f59e0b; font-size: 26px;"></i>
                        </div>
                        <h4 style="font-weight: 800; color: #0f172a; margin-bottom: 15px;">Phone Number</h4>
                        <p style="color: #64748b; line-height: 1.7;">
                            Customer Care<br />
                            <a href="tel:+919677828332" style="font-weight: 700; color: #0f172a;">+91 96778 28332</a> 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="contact-form-section pb_100 pt_50" style="background: #ffffff;">
        <div class="auto-container">
            <div style="background: #bbd700;border-radius: 60px;padding: 60px;box-shadow: 0 40px 80px rgba(0,0,0,0.05);border: 1px solid rgba(187, 215, 0, 0.2);max-width: 1000px;margin: 0 auto;">
                <div style="text-align: center; max-width: 700px; margin: 0 auto 50px;">
                    <span style="display: inline-block; padding: 8px 25px; background: #0f172a; color: #bbd700; border-radius: 50px; font-weight: 800; font-size: 13px; text-transform: uppercase; margin-bottom: 20px; box-shadow: 0 10px 20px rgba(15,23,42,0.1);">Message Center</span>
                    <h2 style="font-size: 42px; font-weight: 900; color: #0f172a; line-height: 1.1;">Send us a <span style="color: #ffffff; text-shadow: 2px 2px 5px rgba(0,0,0,0.1);"> Message.</span></h2>
                </div>
                
                <style>
                    #contact-form input::placeholder,
                    #contact-form textarea::placeholder {
                        color: #0f172a29 !important;
                    }
                </style>
                <form method="post" action="{{ route('contact') }}" id="contact-form" style="padding: 0 20px;">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 form-group mb_25">
                            <input type="text" name="username" placeholder="Full Name" required style="width: 100%; padding: 20px 30px; border-radius: 20px; border: 1px solid rgba(15,23,42,0.05); background: #ffffff; font-weight: 600; color: #0f172a; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 form-group mb_25">
                            <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 20px 30px; border-radius: 20px; border: 1px solid rgba(15,23,42,0.05); background: #ffffff; font-weight: 600; color: #0f172a; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 form-group mb_25">
                            <input type="text" name="phone" placeholder="Phone Number" required style="width: 100%; padding: 20px 30px; border-radius: 20px; border: 1px solid rgba(15,23,42,0.05); background: #ffffff; font-weight: 600; color: #0f172a; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 form-group mb_25">
                            <input type="text" name="subject" placeholder="Subject" required style="width: 100%; padding: 20px 30px; border-radius: 20px; border: 1px solid rgba(15,23,42,0.05); background: #ffffff; font-weight: 600; color: #0f172a; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group mb_40">
                            <textarea name="message" placeholder="How can we help you? Describe your requirements..." style="width: 100%; padding: 25px 30px; border-radius: 25px; border: 1px solid rgba(15,23,42,0.05); background: #ffffff; font-weight: 600; color: #0f172a; min-height: 180px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);"></textarea>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn centred">
                            <button type="submit" style="background: #0f172a; color: #ffffff; padding: 20px 50px; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; border: none; box-shadow: 0 20px 40px rgba(15,23,42,0.2); cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 15px;">
                                Send Message <i class="fas fa-paper-plane" style="color: #bbd700; font-size: 18px;"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section pb_100 pt_50" style="background: #f8fafc;">
        <div class="auto-container">
            <div style="text-align: center; max-width: 700px; margin: 0 auto 50px;">
                <h2 style="font-size: 42px; font-weight: 900; color: #0f172a;">Visit our <span style="color: #1a9fd4;">Facility.</span></h2>
                <p style="color: #64748b; font-size: 18px;">See where the molecular green care happens. Our industrial facility is located in Dharmapuri.</p>
            </div>
            <div style="height: 600px; border-radius: 60px; overflow: hidden; box-shadow: 0 40px 80px rgba(0,0,0,0.12); border: 15px solid #ffffff;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3707.0984680235442!2d78.1156152!3d12.1755887!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bac17fe96d0b0cf%3A0xd591f88c6adecd95!2sMolikule%20Technologies%20pvt%20ltd!5e1!3m2!1sen!2sin!4v1780290400028!5m2!1sen!2sin" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
    </section>
@endsection
