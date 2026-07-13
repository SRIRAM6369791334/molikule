<style>
    .highlights-section {
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0;
    }
    .highlights-section .single-item h5 {
        color: #0f172a !important;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .highlights-section .single-item .icon-box i {
        color: #1a9fd4 !important;
    }
    .main-footer {
        background: linear-gradient(150deg, #f8fafc 0%, #f1f5f9 100%) !important;
        color: #475569 !important;
        border-top: 1px solid #e2e8f0;
    }
    .main-footer p {
        color: #475569 !important;
        line-height: 1.7;
    }
    .main-footer .widget-title h4 {
        color: #0f172a !important;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    .main-footer .links-widget .links-list li a {
        color: #475569 !important;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    .main-footer .links-widget .links-list li a:hover {
        color: #1a9fd4 !important;
        padding-left: 5px;
    }
    .main-footer .contact-widget .info li a {
        color: #475569 !important;
        transition: color 0.3s ease;
        font-weight: 500;
    }
    .main-footer .contact-widget .info li a:hover {
        color: #1a9fd4 !important;
    }
</style>
<!-- highlights-section -->
        <section class="highlights-section pt_35 pb_5">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-6 col-sm-12 single-column">
                        <div class="single-item">
                            <div class="icon-box"><i class="icon-14"></i></div>
                            <h5>100% Customer Satisfaction</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 single-column">
                        <div class="single-item">
                            <div class="icon-box"><i class="icon-15"></i></div>
                            <h5>Help and access is our mission</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 single-column">
                        <div class="single-item">
                            <div class="icon-box"><i class="icon-16"></i></div>
                            <h5>100% quality Car Accessories</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 single-column">
                        <div class="single-item">
                            <div class="icon-box"><i class="icon-17"></i></div>
                            <h5>24/7 Support for Clients</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- highlights-section end -->
<!-- main-footer -->
        <footer class="main-footer">
            <div class="auto-container">
                <div class="widget-section">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget logo-widget" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="700">
                                <figure class="footer-logo"><a href=""><img src="{{ asset('assets/images/logo.png') }}" alt=""></a></figure>
                                <p>Stay informed about Molikule's upcoming events, innovations, and sustainable solutions.</p>
                                {{-- <div class="form-inner">
                                    <form method="post" action="{{ route('contact') }}">
                                        <div class="form-group">
                                            <input type="email" name="email" placeholder="Email Address" required>
                                            <button type="submit"><i class="icon-27"></i></button>
                                        </div>
                                    </form>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget links-widget" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="800">
                                <div class="widget-title">
                                    <h4>Resources</h4>
                                </div>
                                <div class="widget-content">
                                    <ul class="links-list clearfix">
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ route('shop') }}">Shop</a></li>
                                        <li><a href="{{ route('cart') }}">Cart</a></li>
                                        <li><a href="{{ route('brands')}}">Brands</a></li>
                                         <li><a href="{{ route('careers') }}">Careers</a></li>
                                        <li><a href="{{ route('blog') }}">Blog</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget links-widget" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="900">
                                <div class="widget-title">
                                    <h4>Support</h4>
                                </div>
                                <div class="widget-content">
                                    <ul class="links-list clearfix">
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                        <li><a href="{{ route('shipping-refund-policy') }}">Shipping & Refund Policy</a></li>
                                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                                        <li><a href="{{ route('terms-and-conditions') }}">Terms & Conditions</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-lg-2 col-md-6 col-sm-12 footer-column">-->
                        <!--    <div class="footer-widget links-widget" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1000">-->
                        <!--        <div class="widget-title">-->
                        <!--            <h4>Shop Collections</h4>-->
                        <!--        </div>-->
                        <!--        <div class="widget-content">-->
                        <!--            <ul class="links-list clearfix">-->
                        <!--                <li><a href="{{ route('shop') }}">Best Seller</a></li>-->
                        <!--                <li><a href="{{ route('shop') }}">Top Sold Items</a></li>-->
                        <!--                <li><a href="{{ route('shop') }}">New Arrivals</a></li>-->
                        <!--                <li><a href="{{ route('shop') }}">Flash Sale</a></li>-->
                        <!--                <li><a href="{{ route('shop') }}">Discount Products</a></li>-->
                        <!--            </ul>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget contact-widget" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1100">
                                <div class="widget-title">
                                    <h4>Contact Info</h4>
                                </div>
                                <div class="widget-content">
                                    <p>Plot. No. 4, SIDCO Industrial Estate, Selliampatti ( PO ), Dharmapuri – 636 809, Tamilnadu.</p>
                                    <ul class="info mb_25 clearfix">
                                        <li><a href="mailto:mgc@molikule.com">mgc@molikule.com</a></li>
                                        <li><a href="tel:+919698066332">+91 9698066332</a></li>
                                    </ul>
                                    <!--<ul class="social-links">-->
                                    <!--    <li><a href="#"><i class="icon-18"></i></a></li>-->
                                    <!--    <li><a href="#"><i class="icon-19"></i></a></li>-->
                                    <!--    <li><a href="#"><i class="icon-20"></i></a></li>-->
                                    <!--    <li><a href="#"><i class="icon-21"></i></a></li>-->
                                    <!--</ul>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="footer-bottom">
                    <div class="bottom-inner">
                        <div class="copyright"><p>Copyright &copy; 2026 <a href="/">Molikule Green Care</a>, Inc. All Rights Reserved | Designed by <a href="https://saitechnosolutions.com/" target="_blank" rel="noopener noreferrer">Sai techno solutions</a></p></div>
                        <!-- <ul class="footer-card clearfix">
                            <li><a href="#"><img src="{{ asset('assets/images/resource/footer-card-1.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/images/resource/footer-card-2.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/images/resource/footer-card-3.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/images/resource/footer-card-4.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/images/resource/footer-card-5.png') }}" alt=""></a></li>
                            <li><a href="#"><img src="{{ asset('assets/images/resource/footer-card-6.png') }}" alt=""></a></li>
                        </ul> -->
                    </div>
                </div> 
            </div>
        </footer>
        <!-- main-footer end -->
