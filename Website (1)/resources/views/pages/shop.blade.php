@extends('layouts.app')

@php
    $currentCategorySlug = request('category');
    
    // Default theme fallback
    $activeTheme = [
        'primary' => '#bbd700',
        'light' => '#f1f5f9',
        'overlay' => '#e2e8f0',
        'radius' => '24px',
        'bg_image' => null
    ];

    if ($currentCategorySlug) {
        $activeCatModel = isset($categories) ? collect($categories)->firstWhere('slug', $currentCategorySlug) : \App\Models\Category::where('slug', $currentCategorySlug)->first();
        
        if ($activeCatModel) {
            $activeTheme['primary'] = $activeCatModel->theme_primary_color ?: $activeTheme['primary'];
            $activeTheme['light'] = $activeCatModel->theme_primary_color ?: $activeTheme['light'];
            $activeTheme['overlay'] = $activeCatModel->theme_light_color ?: $activeTheme['overlay'];
            
            $radius = $activeCatModel->theme_border_radius;
            if ($radius !== null && $radius !== '') {
                $activeTheme['radius'] = is_numeric($radius) ? $radius . 'px' : $radius;
            }
            
            $activeTheme['bg_image'] = $activeCatModel->theme_bg_image ?: null;
        }
    }
    
    $bgImageUrl = $activeTheme['bg_image'] 
        ? (\Illuminate\Support\Str::startsWith($activeTheme['bg_image'], ['http://', 'https://']) ? $activeTheme['bg_image'] : rtrim(env('MAIN_URL'), '/') . '/uploads/' . ltrim($activeTheme['bg_image'], '/')) 
        : rtrim(env('MAIN_URL'), '/uploads/') . '/assets/images/banner/banner-2.jpg';
@endphp

@section('content')
    <style>
        :root {
            --theme-primary: {{ $activeTheme['primary'] }};
            --theme-light: {{ $activeTheme['light'] }};
            --theme-overlay: {{ $activeTheme['overlay'] }};
            --theme-radius: {{ $activeTheme['radius'] }};
        }
    </style>
    <!-- Exact Contact Page UI Design for Header -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(rgba(239, 246, 255, 0), rgb(239 246 255 / 0%)), url('{{ $bgImageUrl }}') center/cover fixed !important;">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; letter-spacing: 2px; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 30px var(--theme-overlay);">
                    <li><a href="{{ route('home') }}" style="color: var(--theme-primary);">Home</a></li>
                    <li style="color: #0f172a;">Shop</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="shop-page-section shop-style-two pt_80 pb_100" style="background: linear-gradient(rgba(239, 246, 255, 0), rgb(239 246 255 / 0%)), url('{{ $bgImageUrl }}') center/cover fixed;">
        <div class="auto-container">
            <div class="row clearfix">
                <!-- Sidebar Side -->
                <div class="col-lg-3 col-md-12 col-sm-12 sidebar-side">
                    <div class="shop-sidebar modern-sidebar" style="background: linear-gradient(135deg, var(--theme-light) 0%, var(--theme-overlay) 100%); padding: 30px; border-radius: var(--theme-radius); box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid var(--theme-overlay);">
                        
                        <!-- Search Widget -->
                        <div class="sidebar-widget search-widget mb_40">
                            <div class="widget-title mb_20">
                                <h4 style="font-weight: 800; font-size: 18px; color: #0f172a;">Search Product</h4>
                            </div>
                            <div class="search-inner" style="position: relative;">
                                <form action="{{ route('shop') }}" method="GET">
                                    <input type="text" name="search" placeholder="Type here..." value="{{ request('search') }}" style="width: 100%; background: #f1f5f9; border: none; padding: 15px 20px; border-radius: 12px; font-weight: 600; font-size: 14px;">
                                    <button type="submit" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--theme-primary);"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="category-widget sidebar-widget pb_30 mb_30" style="border-bottom: 1px solid #f1f5f9;">
                            <div class="widget-title mb_20">
                                <h4 style="font-weight: 800; font-size: 18px; color: #0f172a;">Collection</h4>
                            </div>
                            <div class="widget-content">
                                <ul class="category-list clearfix">
                                    @forelse($categories ?? [] as $cat)
                                        <li style="margin-bottom: 12px;">
                                            <label class="modern-checkbox" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: 0.3s; padding: 8px 12px; border-radius: var(--theme-radius); background: {{ request('category') == $cat->slug ? 'var(--theme-overlay)' : 'transparent' }};">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <input
                                                        type="checkbox"
                                                        class="d-none"
                                                        onchange="applyFilter('category', '{{ $cat->slug }}', this.checked)"
                                                        {{ request('category') == $cat->slug ? 'checked' : '' }}
                                                    >
                                                    <span style="font-weight: 700; color: {{ request('category') == $cat->slug ? 'var(--theme-primary)' : '#64748b' }}; font-size: 14px;">{{ $cat->category_name }}</span>
                                                </div>
                                                <span style="font-size: 12px; font-weight: 800; color: #94a3b8; background: #fff; padding: 2px 8px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">{{ $cat->products_count ?? 0 }}</span>
                                            </label>
                                        </li>
                                    @empty
                                        <li><span class="text-muted">Empty...</span></li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <!-- Price Filter -->
                        <div class="filter-widget sidebar-widget pb_30 mb_30" style="border-bottom: 1px solid #f1f5f9;">
                            <div class="widget-title mb_20">
                                <h4 style="font-weight: 800; font-size: 18px; color: #0f172a;">Price Threshold</h4>
                            </div>
                            <div class="price-range-slider modern-slider">
                                <div id="slider-range" class="range-bar mb_20"
                                     data-min="{{ $priceRange['min'] }}" 
                                     data-max="{{ $priceRange['max'] }}"
                                     data-val-min="{{ request('min_price', $priceRange['min']) }}"
                                     data-val-max="{{ request('max_price', $priceRange['max']) }}"
                                     style="height: 6px; background: #f1f5f9; border-radius: 3px; border: none;"></div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                    <div style="font-size: 13px; font-weight: 800; color: #64748b;">
                                        Min: <span id="min-price-val" style="color: #0f172a;">₹{{ request('min_price', $priceRange['min']) }}</span>
                                    </div>
                                    <div style="font-size: 13px; font-weight: 800; color: #64748b;">
                                        Max: <span id="max-price-val" style="color: #0f172a;">₹{{ request('max_price', $priceRange['max']) }}</span>
                                    </div>
                                </div>

                                <div class="btn-box" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <button type="button" class="clear-btn" style="padding: 12px; border: none; border-radius: 15px; font-weight: 900; font-size: 13px; background: #f1f5f9; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-decoration: none !important;">RESET</button>
                                    <button type="button" class="filter-btn" style="padding: 12px; border: none; border-radius: 15px; font-weight: 900; font-size: 13px; background: var(--theme-primary); color: #fff; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; box-shadow: 0 4px 10px var(--theme-overlay); text-decoration: none !important;">APPLY</button>
                                </div>
                            </div>
                        </div>

                        @if(isset($brands) && $brands->count() > 0)
                            <div class="brand-widget sidebar-widget">
                                <div class="widget-title mb_20">
                                    <h4 style="font-weight: 800; font-size: 18px; color: #0f172a;">Elite Brands</h4>
                                </div>
                                <div class="widget-content">
                                    <ul class="category-list clearfix">
                                        @foreach($brands as $brand)
                                            <li style="margin-bottom: 12px;">
                                                <label class="modern-checkbox" style="display: flex; align-items: center; gap: 10px; cursor: pointer; transition: 0.3s; padding: 8px 12px; border-radius: var(--theme-radius); background: {{ request('brand') == ($brand->slug ?: $brand->brand_id) ? 'var(--theme-overlay)' : 'transparent' }};">
                                                    <input
                                                        type="checkbox"
                                                        class="d-none"
                                                        onchange="applyFilter('brand', '{{ $brand->slug ?: $brand->brand_id }}', this.checked)"
                                                        {{ request('brand') == ($brand->slug ?: $brand->brand_id) ? 'checked' : '' }}
                                                    >
                                                    <span style="font-weight: 700; color: {{ request('brand') == ($brand->slug ?: $brand->brand_id) ? 'var(--theme-primary)' : '#64748b' }}; font-size: 14px;">{{ $brand->brand_name }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Content Side -->
                <div class="col-lg-9 col-md-12 col-sm-12 content-side">
                    <div class="our-shop">
                        <!-- Top Bar -->
                        <div class="item-shorting mb_40" style="position: relative; z-index: 100; background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 15px 25px; border-radius: var(--theme-radius); box-shadow: 0 5px 20px var(--theme-overlay); border: 1px solid var(--theme-overlay); display: flex; align-items: center; justify-content: space-between;">
                            <div class="left-column">
                                <div class="text" style="font-weight: 700; color: var(--theme-primary); font-size: 14px; display: flex; align-items: center; gap: 5px;">
                                    Discovering <span style="color: var(--theme-primary); font-weight: 900; background: var(--theme-overlay); padding: 2px 10px; border-radius: 8px; font-size: 15px;">{{ $products->total() }}</span> Products
                                </div>
                            </div>
                            <div class="right-column" style="display: flex; align-items: center; gap: 20px;">
                                <div class="short-box" style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-size: 11px; font-weight: 800; color: var(--theme-primary); text-transform: uppercase; letter-spacing: 2px;">SORT BY</span>
                                    <div class="select-box">
                                        <select class="wide sort-by-select" onchange="applySort(this.value)" style="border: 1px solid var(--theme-overlay); background: var(--theme-overlay); font-weight: 800; font-size: 13px; border-radius: 12px; padding: 8px 20px; color: var(--theme-primary); cursor: pointer; outline: none; transition: 0.3s;">
                                            <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending Now</option>
                                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Lowest first</option>
                                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: Highest first</option>
                                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Grid -->
                        <div class="wrapper grid">
                            <div class="shop-grid-content">
                                <div class="row clearfix" id="shop-product-grid">
                                    @forelse($products as $product)
                                        <div class="col-lg-4 col-md-6 col-sm-12 shop-block-vibe" style="margin-bottom: 30px; opacity: 0; transform: translateY(20px);">
                                            <x-product-card :product="$product" style="featured" :noCol="true" />
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5" style="background: linear-gradient(135deg, var(--theme-overlay) 0%, #ffffff 100%); border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid var(--theme-overlay);">
                                            <i class="fas fa-search mb-3" style="font-size: 48px; color: var(--theme-primary);"></i>
                                            <h3 style="font-weight: 900; color: #0f172a; margin-bottom: 10px;">No Matches Found</h3>
                                            <p style="color: #94a3b8; font-weight: 600;">We couldn't find any products matching your specific filters.</p>
                                            <a href="{{ route('shop') }}" class="btn-main mt_20" style="display: inline-block; background: var(--theme-primary); color: #fff; padding: 12px 30px; border-radius: var(--theme-radius); font-weight: 900; transition: 0.3s; box-shadow: 0 10px 20px var(--theme-overlay);">Clear All Filters</a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper pt_60 centred" style="display: flex; justify-content: center;">
                            {{ $products->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Premium Pagination Styling */
        .pagination-wrapper .pagination {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .pagination-wrapper .pagination li a,
        .pagination-wrapper .pagination li span {
            width: 50px !important;
            height: 50px !important;
            line-height: 50px !important;
            border-radius: var(--theme-radius) !important;
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            transition: 0.3s !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02) !important;
        }
        .pagination-wrapper .pagination li a:hover,
        .pagination-wrapper .pagination li a.current {
            background: var(--theme-primary) !important;
            border-color: var(--theme-primary) !important;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px var(--theme-overlay) !important;
        }
        .pagination-wrapper .pagination li.disabled span {
            opacity: 0.5;
            background: #f8fafc !important;
        }
        /* Modern UI Overrides */
        .modern-checkbox:hover {
            background: var(--theme-overlay) !important;
            transform: translateX(5px);
        }
        
        .modern-checkbox input:checked + span {
            color: var(--theme-primary) !important;
        }

        /* Slider Customization */
        .modern-slider .ui-slider-range {
            background: var(--theme-primary) !important;
            border-radius: 3px;
        }
        .modern-slider .ui-slider-handle {
            width: 18px !important;
            height: 18px !important;
            border: 4px solid var(--theme-primary) !important;
            background: #fff !important;
            border-radius: 50% !important;
            top: -7px !important;
            cursor: grab !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
            transition: transform 0.2s;
        }
        .modern-slider .ui-slider-handle:hover {
            transform: scale(1.2);
        }

        .clear-btn:hover {
            background: #e2e8f0 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.08) !important;
        }
        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px var(--theme-overlay) !important;
        }

        /* Product Card Stagger Animation Classes */
        .shop-block-vibe {
            position: relative;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* Ensure hovered columns stay on top of the grid */
        .shop-block-vibe:hover {
            z-index: 50 !important;
            position: relative;
        }

        /* Dynamic Product Card Overrides specific to Shop Page */
        .shop-page-section .shop-block-one .inner-box,
        .shop-page-section .shop-block-two .inner-box,
        .shop-page-section .shop-block-list .inner-box {
            border-radius: var(--theme-radius) !important;
            border-color: var(--theme-overlay) !important;
            background: #ffffff !important;
        }

        .shop-page-section .shop-block-one:hover .inner-box,
        .shop-page-section .shop-block-two:hover .inner-box,
        .shop-page-section .shop-block-list:hover .inner-box {
            border-color: var(--theme-primary) !important;
            box-shadow: 0 12px 30px var(--theme-overlay) !important;
        }

        .shop-page-section .inner-box h4 a,
        .shop-page-section .inner-box h6 a,
        .shop-page-section .inner-box .text,
         {
            color: var(--theme-primary) !important;
        }

        .shop-page-section .inner-box h5 {
            color: var(--theme-primary) !important;
        }

        .shop-page-section .product-stock i, 
        .shop-page-section .product-stock {
            color: var(--theme-primary) !important;
        }

        .shop-page-section .add-to-cart-btn {
            background: var(--theme-primary) !important;
            color: #fff !important;
            border-radius: var(--theme-radius) !important;
        }

        .shop-page-section .add-to-cart-btn:hover {
            box-shadow: 0 8px 20px var(--theme-overlay) !important;
        }

        .shop-page-section .option-list li a:hover,
        .shop-page-section .option-list-two li a:hover,
        .shop-page-section .add-to-wishlist-btn:hover,
        .shop-page-section .add-to-wishlist-btn.active {
            color: var(--theme-primary) !important;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        function applySort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            window.location.href = url.toString();
        }

        function applyFilter(type, value, checked) {
            const url = new URL(window.location.href);
            if (checked) {
                url.searchParams.set(type, value);
            } else {
                url.searchParams.delete(type);
            }
            window.location.href = url.toString();
        }

        $(document).ready(function() {
            // GSAP Entrance Animation
            gsap.to(".shop-block-vibe", {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.1,
                ease: "power4.out",
                delay: 0.5
            });

            // Hover effects for cards
            $(document).on('mouseenter', '.shop-block-one', function() {
                gsap.to($(this).find('.option-list-two'), { right: '15px', duration: 0.3 });
                gsap.to($(this).find('figure img'), { scale: 1.05, duration: 0.5 });
                gsap.to($(this).find('.overlay-content'), { opacity: 1, visibility: 'visible', y: 0, duration: 0.4, ease: "power2.out" });
            }).on('mouseleave', '.shop-block-one', function() {
                gsap.to($(this).find('.option-list-two'), { right: '-50px', duration: 0.3 });
                gsap.to($(this).find('figure img'), { scale: 1, duration: 0.5 });
                gsap.to($(this).find('.overlay-content'), { opacity: 0, visibility: 'hidden', y: 20, duration: 0.4, ease: "power2.in" });
            });
// Header Particles removed for cleaner Floating Blob aesthetic

            // Price Slider Logic
            $('.filter-btn').on('click', function() {
                if ($("#slider-range").length) {
                    var min = $( "#slider-range" ).slider( "values", 0 );
                    var max = $( "#slider-range" ).slider( "values", 1 );
                    const url = new URL(window.location.href);
                    url.searchParams.set('min_price', min);
                    url.searchParams.set('max_price', max);
                    window.location.href = url.toString();
                }
            });

            $("#slider-range").on("slide", function(event, ui) {
                $("#min-price-val").text("₹" + ui.values[0]);
                $("#max-price-val").text("₹" + ui.values[1]);
            });

            $('.clear-btn').on('click', function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('min_price');
                url.searchParams.delete('max_price');
                url.searchParams.delete('category');
                url.searchParams.delete('brand');
                url.searchParams.delete('search');
                url.searchParams.delete('sort');
                window.location.href = url.toString();
            });
        });
    </script>
@endpush
