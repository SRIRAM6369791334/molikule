<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>@yield('title', 'Molikule Green Care | Sustainable Hygiene & Cleaning Solutions')</title>
    <meta name="description" content="@yield('meta_description', 'Molikule Green Care — India\'s trusted provider of eco-conscious hygiene, laundry care, auto care, and specialty cleaning solutions for homes, institutions, and industries.')">
    <meta name="keywords" content="@yield('meta_keywords', 'sustainable hygiene solutions India, eco-friendly cleaning products, institutional hygiene products, healthcare disinfectants India, laundry care solutions for hotels, auto care chemicals India, green cleaning company India, specialty chemical company India, commercial cleaning products, facility management hygiene solutions')">
    @stack('schema')
<!-- Fav Icon -->
<link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">

<!-- Stylesheets -->
<link href="{{ asset('assets/css/font-awesome-all.css') }}?v=1.1" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="{{ asset('assets/css/flaticon.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/owl.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/nice-select.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/elpath.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/jquery-ui.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/switcher-style.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/color.css') }}" id="jssDefault" rel="stylesheet">
<link href="{{ asset('assets/css/rtl.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/header.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/banner.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/category.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/shop.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/feature.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/deals.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/brand.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/apps.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/news.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/highlights.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/footer.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/about.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/account.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/ads.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/blog-details.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/blog-sidebar.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/cart.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/checkout.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/clients.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/contact.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/cta.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/error.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/flash-sales.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/instagram.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/page-title.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/search.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/shop-details.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/shop-sidebar.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/sign.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/sweetalert-custom.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<link href="{{ asset('assets/css/module-css/testimonial.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/module-css/video.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">

@stack('css')

</head>


<!-- page wrapper -->
<body class="{{ Request::routeIs('home') ? 'home-page' : 'internal-page' }}">
    <style>
        :root {
            --brand-primary: #bbd700;
            --premium-black: #1a1a1a;
        }

        /* Global Typography for Internal Pages */
        .internal-page, .internal-page h1,  .internal-page h3, 
        .internal-page h4, .internal-page h5, .internal-page h6,
        .internal-page p, .internal-page li{
            color: #333;
        }
        .internal-page span,.internal-page h2{
            color: #ffffff;
        }
        .internal-page h1, .internal-page h2, .internal-page h3, 
        .internal-page h4, .internal-page h5, .internal-page h6 {
            color: var(--premium-black) !important;
        }
        .current {
            color: black !important;
        }
        

        /* Apply the soft off-white background to all internal sections */
        /* .internal-page section {
            background: linear-gradient(rgba(250, 250, 250, 0.95), rgba(250, 250, 250, 0.95)) !important;
        } */

        /* Ensure breadcrumbs and titles follow the brand theme */
        .internal-page .page-title {
            background: #fff !important; /* Keep page title clean */
        }

        .internal-page .bread-crumb li a:hover, 
        .internal-page .bread-crumb li {
            color: var(--brand-primary) !important;
        }

        .internal-page .page-title .content-box .border-line {
            background-color: var(--brand-primary) !important;
        }

        /* Fix for buttons and primary elements */
        .theme-btn:hover {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
        }
    </style>

    <div class="boxed_wrapper ltr">


        <!-- preloader -->
        <div class="loader-wrap">
            <div class="preloader">
                <div class="preloader-close">close</div>
                <div id="handle-preloader" class="handle-preloader">
                    <div class="animation-preloader">
                        <div class="spinner"></div>
                        <div class="txt-loading" style="font-family: 'Michroma', sans-serif !important; font-weight: 400 !important; font-style: normal !important; font-size: 32px; text-transform: uppercase; letter-spacing: 2px;">
                            <span data-text-preloader="M" class="letters-loading">
                                M
                            </span>
                            <span data-text-preloader="O" class="letters-loading">
                                O
                            </span>
                            <span data-text-preloader="L" class="letters-loading">
                                L
                            </span>
                            <span data-text-preloader="I" class="letters-loading">
                                I
                            </span>
                            <span data-text-preloader="K" class="letters-loading">
                                K
                            </span>
                            <span data-text-preloader="U" class="letters-loading">
                                U
                            </span>
                            <span data-text-preloader="L" class="letters-loading">
                                L
                            </span>
                            <span data-text-preloader="E" class="letters-loading">
                                E
                            </span>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <!-- preloader end -->


        


        @include('layouts.header')


        <!-- Mobile Menu  -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <div class="close-btn"><i class="fas fa-times"></i></div>
            
            <nav class="menu-box">
                <div class="nav-logo"><a href=""><img src="{{ asset('assets/images/logo.png') }}" alt="" title=""></a></div>
                <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
                <div class="contact-info">
                    <h4>Contact Info</h4>
                    <ul>
                        <li>Chicago 12, Melborne City, USA</li>
                        <li><a href="tel:+8801682648101">+88 01682648101</a></li>
                        <li><a href="mailto:info@example.com">info@example.com</a></li>
                    </ul>
                </div>
                {{-- <div class="social-links">
                    <ul class="clearfix">
                        <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                        <li><a href="#"><span class="fab fa-facebook-square"></span></a></li>
                        <li><a href="#"><span class="fab fa-pinterest-p"></span></a></li>
                        <li><a href="#"><span class="fab fa-instagram"></span></a></li>
                        <li><a href="#"><span class="fab fa-youtube"></span></a></li>
                    </ul>
                </div> --}}
            </nav>
        </div><!-- End Mobile Menu -->


        @yield('content')


        @include('layouts.footer')



        <!-- Floating Social Buttons -->
        <style>
            .floating-social {
                position: fixed;
                left: 20px;
                bottom: 30px;
                z-index: 999;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .floating-social a {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 20px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                text-decoration: none;
                position: relative;
            }
            .floating-social a:hover {
                transform: translateY(-5px) scale(1.05);
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                color: #fff;
            }
            .floating-social a.social-wa { background: #25D366; }
            .floating-social a.social-in { background: #0077b5; }
            .floating-social a.social-ig { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); }
            .floating-social a.social-fb { background: #1877F2; }
            
            /* Force FontAwesome for icons */
            .floating-social a i {
                font-family: "Font Awesome 5 Brands" !important;
                font-weight: 400 !important;
                font-style: normal !important;
                font-variant: normal !important;
                text-rendering: auto;
                line-height: 1;
            }
            
            /* Tooltip on hover */
            .floating-social a::after {
                content: attr(data-tooltip);
                position: absolute;
                left: 55px;
                background: rgba(26,26,26,0.9);
                color: #fff;
                font-size: 13px;
                font-weight: 500;
                padding: 6px 12px;
                border-radius: 6px;
                white-space: nowrap;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                pointer-events: none;
                transform: translateX(-10px);
                font-family: 'Roboto', sans-serif;
            }
            .floating-social a:hover::after {
                opacity: 1;
                visibility: visible;
                transform: translateX(0);
            }
            
            /* Adjust position on small screens so it doesn't overlap excessively */
            @media (max-width: 768px) {
                .floating-social {
                    left: 15px;
                    bottom: 20px;
                    gap: 10px;
                }
                .floating-social a {
                    width: 40px;
                    height: 40px;
                    font-size: 18px;
                }
                .floating-social a::after {
                    display: none;
                }
            }
        </style>

         <div class="floating-social">
            <a href="https://wa.me/919698066332" target="_blank" class="social-wa" data-tooltip="Chat on WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="https://www.linkedin.com/company/molikulegreen-care/" target="_blank" class="social-in" data-tooltip="Follow on LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://www.instagram.com/molikulegreencare?igsh=aGRqdmt2a2ZsbzVn" target="_blank" class="social-ig" data-tooltip="Follow on Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/molikulegreencare" target="_blank" class="social-fb" data-tooltip="Follow on Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
        </div>
        <!-- End Floating Social Buttons -->

        <!--Scroll to top-->
        <div class="scroll-to-top">
            <div>
                <div class="scroll-top-inner">
                    <div class="scroll-bar">
                        <div class="bar-inner"></div>
                    </div>
                    <div class="scroll-bar-text">Go To Top</div>
                </div>
            </div>
        </div>
        <!-- Scroll to top end -->
        
    </div>


    <!-- jequery plugins -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.js') }}"></script>
    <script src="{{ asset('assets/js/wow.js') }}"></script>
    <script src="{{ asset('assets/js/validation.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('assets/js/appear.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.js') }}"></script>
    <script src="{{ asset('assets/js/parallax-scroll.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/language.js') }}"></script>
    <script src="{{ asset('assets/js/countdown.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/lenis.min.js') }}"></script>
    <script src="{{ asset('assets/js/bxslider.js') }}"></script>
    <script src="{{ asset('assets/js/gmaps.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.bootstrap-touchspin.js') }}"></script>
    <script src="{{ asset('assets/js/map-helper.js') }}"></script>
    <script src="{{ asset('assets/js/pagenav.js') }}"></script>
    <script src="{{ asset('assets/js/product-filter.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>

    <!-- main-js -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function() {
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: '<ul style="text-align: left; list-style: inside;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                });
            @endif


            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Note',
                    text: "{{ session('info') }}",
                });
            @endif

            // Global Add to Cart AJAX
            $(document).on('click', '.add-to-cart-btn', function(e) {
                e.preventDefault();
                let productId = $(this).data('id');
                let quantity = $('#quantity').val() || 1;
                // Priority: Specific button variant ID > Resolved Hidden ID > Checked radio
                let variantId = $(this).data('variant-id') || $('#resolved-variant-id').val() || $('input[name="variant_id"]:checked').val() || null;
                let shouldRedirect = $(this).data('redirect') || false;
                let btn = $(this);

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        quantity: quantity,
                        variant_id: variantId
                    },
                    success: function(response) {
                        if (response.success) {
                            if (shouldRedirect) {
                                window.location.href = "{{ route('cart') }}";
                                return;
                            }
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Added to Cart',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            }).then(() => {
                                location.reload(); // Update cart count in header
                            });
                        } else {
                             Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message || 'Something went wrong!',
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add product to cart.',
                        });
                    }
                });
            });

            // Global Wishlist Toggle AJAX
            $(document).on('click', '.add-to-wishlist-btn', function(e) {
                e.preventDefault();
                let productId = $(this).data('id');
                let btn = $(this);

                $.ajax({
                    url: "{{ route('wishlist.toggle') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.status === 'added') {
                                btn.addClass('active');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Wishlist',
                                    text: 'Added to wishlist',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true
                                });
                            } else {
                                btn.removeClass('active');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Wishlist',
                                    text: 'Removed from wishlist',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true
                                });
                            }
                            // Only reload if we need to update a count display that isn't reactive
                            // location.reload(); 
                        }
                    }
                });
            });
        });
    </script>


    @stack('scripts')

</body><!-- End of .page_wrapper -->
</html>
