<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | Molikule Enterprise - Premium Hygiene & Cleaning Solutions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Centralized management dashboard for Molikule hygiene and cleaning solutions." name="description" />
    <meta content="Molikule IT Team" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- nouislider css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/nouislider/nouislider.min.css') }}">

    <!-- Select2 & Dropzone CSS -->
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* Global Premium Select2 Theme */
        .select2-container--default .select2-selection--single {
            height: 40px !important;
            padding: 5px 10px !important;
            border: 1px solid #ced4da !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            color: #495057 !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #34c38f !important;
            box-shadow: 0 0 0 0.15rem rgba(52, 195, 143, 0.25) !important;
        }
        .select2-dropdown {
            border-radius: 8px !important;
            border: 1px solid #ced4da !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            z-index: 1060;
        }
    </style>

    <!-- plugin css -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"
        rel="stylesheet" type="text/css" />

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    
    <!-- GridJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
    @yield('css')
</head>

<body data-topbar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('layouts.header')

        @include('layouts.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('layouts.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script
        src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>

    @if(Request::is('/'))
        <!-- Chart scripts only for dashboard -->
        <script src="{{ asset('assets/js/pages/allchart.js') }}"></script>
        <!-- dashboard init -->
        <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    @endif

    <!-- GridJS & JustValidate -->
    <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/just-validate@4.2.0/dist/just-validate.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Global initialization for any select with .select2-auto
            $('.select2-auto').select2({ width: '100%' });

            // Global handler for Select2 inside Modals
            $(document).on('shown.bs.modal', function (e) {
                $(e.target).find('.select2-auto, select[data-trigger="select2"]').select2({
                    dropdownParent: $(e.target),
                    width: '100%'
                });
            });
        });
    </script>

    @if(Request::is('products*') || Request::is('categories*') || Request::is('brands*') || Request::is('stocks*') || Request::is('low-stock*') || Request::is('banners*') || Request::is('product-variants*') || Request::is('add-product*'))
        <!-- nouislider js and other inventory plugins -->
        <script src="{{ asset('assets/libs/nouislider/nouislider.min.js') }}"></script>
        <script src="{{ asset('assets/libs/wnumb/wNumb.min.js') }}"></script>
        <script src="{{ asset('assets/libs/dropzone/min/dropzone.min.js') }}"></script>
    @endif

    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Custom Script for Sidebar Icon/Text Toggle Fix -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('vertical-menu-btn');
            const body = document.body;

            // Function to update sidebar text visibility
            function updateSidebarTextVisibility(isExpanded) {
                // Handle dropdown spans (like Orders submenu)
                const spans = document.querySelectorAll('#sidebar-menu a span');
                spans.forEach(function (span) {
                    span.style.display = isExpanded ? 'inline-block' : 'none';
                });

                // Handle text nodes in other links (Dashboard, Products, etc.)
                const links = document.querySelectorAll('#sidebar-menu a');
                links.forEach(function (link) {
                    // Find text nodes (excluding spans and i elements)
                    const childNodes = Array.from(link.childNodes);
                    childNodes.forEach(function (node) {
                        if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                            // Create a span wrapper if it doesn't exist
                            if (!node.parentElement.querySelector('.menu-text')) {
                                const span = document.createElement('span');
                                span.className = 'menu-text';
                                span.textContent = node.textContent.trim();
                                node.parentElement.insertBefore(span, node);
                                node.parentElement.removeChild(node);
                            }
                        }
                    });
                });

                // Update all menu text spans
                const allTextSpans = document.querySelectorAll('#sidebar-menu a .menu-text, #sidebar-menu a span:not([class])');
                allTextSpans.forEach(function (span) {
                    span.style.display = isExpanded ? 'inline-block' : 'none';
                });
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    // Check if sidebar is currently enabled (meaning it's expanded)
                    const isCurrentlyExpanded = body.classList.contains('sidebar-enable');

                    // Immediately update visibility - when sidebar-enable is present, it's collapsed
                    updateSidebarTextVisibility(!isCurrentlyExpanded);

                    // Re-initialize icons after transition
                    setTimeout(function () {
                        feather.replace();
                    }, 300);
                });
            }

            // Initialize on load
            const isInitiallyExpanded = !body.classList.contains('sidebar-enable');
            updateSidebarTextVisibility(isInitiallyExpanded);

            // Handle window resize for responsive changes
            window.addEventListener('resize', function () {
                setTimeout(function () {
                    feather.replace();
                    // Re-apply visibility after resize
                    const isResizedExpanded = !body.classList.contains('sidebar-enable');
                    updateSidebarTextVisibility(isResizedExpanded);
                }, 200);
            });

            // Force initial icon replacement
            setTimeout(function () {
                feather.replace();
            }, 100);
        });
    </script>
    @stack('scripts')
</body>

</html>