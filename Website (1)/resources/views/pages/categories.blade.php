@extends('layouts.app')

@section('title', 'Categories - Molikule')

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 60px 0 40px; background: #fdfdfd;">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 8px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; background: #ffffff; padding: 8px 20px; border-radius: 50px; box-shadow: 0 10px 25px rgba(218, 165, 32, 0.15);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a;">Home</a></li>
                    {{-- <li style="color: #0f172a;">/</li> --}}
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.2);">Product Categories</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Category Header (Same as Brands) -->
    <section class="" style="background: #fdfdfd;">
        <div class="auto-container">
            <div style="text-align: center; max-width: 800px; margin: 0 auto;">
                <div style="display: inline-flex; align-items: center; gap: 10px; padding: 10px 25px; background: rgba(187, 215, 0, 0.1); border-radius: 50px; margin-bottom: 25px;">
                    <i class="fas fa-th-large" style="color: #bbd700; font-size: 14px;"></i>
                    <span style="color: #0f172a; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 12px;">Discover Our Ranges</span>
                </div>
                {{-- <h2 style="font-size: 56px; font-weight: 900; color: #0f172a; letter-spacing: -2.5px; line-height: 1; margin-bottom: 25px;">Product <span style="color: #bbd700; text-shadow: 2px 2px 5px rgba(0,0,0,0.05);">Categories.</span></h2> --}}
                <p style="color: #64748b; font-size: 20px; line-height: 1.6; font-weight: 500;">Explore our wide range of high-quality automotive parts and molecularly advanced cleaning solutions.</p>
            </div>
        </div>
    </section>

    <!-- category-section -->
    <section class="category-page-section pb_100" style="background: #fdfdfd;">
        <div class="auto-container">
            <div style=" border-radius: 80px; padding: 100px 60px;  border: 1px solid rgba(187, 215, 0, 0.1); position: relative; overflow: hidden;">
                <!-- Decorative Glows (Consistent with Brands) -->
                <div style="position: absolute; top: -10%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(187, 215, 0, 0.1) 0%, transparent 70%);"></div>
                <div style="position: absolute; bottom: -10%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(26, 159, 212, 0.05) 0%, transparent 70%);"></div>

                <div class="row clearfix g-5">
                    @foreach($categories as $category)
                        <div class="col-lg-3 col-md-4 col-sm-6 category-block">
                            <div class="category-block-one">
                                <a href="{{ $category->shop_url }}" style="display: block; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); padding: 40px 25px; border-radius: 50px; box-shadow: 0 15px 35px rgba(0,0,0,0.03); border: 1px solid rgba(255, 255, 255, 0.6); text-align: center; transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); height: 100%; position: relative;" onmouseover="this.style.transform='translateY(-15px)'; this.style.boxShadow='0 40px 80px rgba(187, 215, 0, 0.15)'; this.style.borderColor='rgba(187, 215, 0, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.03)'; this.style.borderColor='rgba(255, 255, 255, 0.6)';">
                                    <div style="height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; background: #ffffff; border-radius: 30px; box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);">
                                        <img src="{{ $category->image_full_url }}" alt="{{ $category->category_name }}" style="max-width: 80%; max-height: 80%; object-fit: contain; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.05));">
                                    </div>
                                    <h4 style="font-weight: 900; color: #0f172a; margin-bottom: 12px; font-size: 20px; letter-spacing: -0.5px;">{{ $category->category_name }}</h4>
                                    <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 15px; background: #ffffff; border-radius: 50px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;">
                                        <div style="width: 6px; height: 6px; background: #bbd700; border-radius: 50%;"></div>
                                        <span style="color: #64748b; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">{{ $category->products_count }} Items</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Custom Colorful Pagination (Same as Brands) -->
                <div class="pagination-wrapper centred mt_80">
                    {{ $categories->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Force 'Outfit' font for all elements */
        * {
            font-family: 'Outfit', sans-serif !important;
        }

        /* Vibrant Pagination Design (Consistent with Brands) */
        .pagination {
            display: flex !important;
            justify-content: center !important;
            list-style: none !important;
            gap: 15px !important;
            padding: 0 !important;
        }

        .pagination li a, 
        .pagination li span {
            width: 55px !important;
            height: 55px !important;
            line-height: 55px !important;
            text-align: center !important;
            display: block !important;
            border: 2px solid #ffffff !important;
            border-radius: 20px !important;
            color: #0f172a !important;
            font-weight: 900 !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            font-size: 16px !important;
            text-decoration: none !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.04) !important;
        }

        .pagination li.active span, 
        .pagination li.active a {
            background: #bbd700 !important;
            color: #ffffff !important;
            border-color: #bbd700 !important;
            transform: scale(1.1) translateY(-5px) !important;
            box-shadow: 0 20px 40px rgba(187, 215, 0, 0.3) !important;
        }

        .pagination li a:hover {
            background: #ffffff !important;
            color: #bbd700 !important;
            border-color: #bbd700 !important;
            transform: translateY(-5px) !important;
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }

        @media (max-width: 768px) {
            .pagination li a, .pagination li span {
                width: 45px !important;
                height: 45px !important;
                line-height: 45px !important;
                border-radius: 15px !important;
            }
        }
    </style>
@endsection
