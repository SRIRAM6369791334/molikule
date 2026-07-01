@extends('layouts.app')

@section('content')
    <!-- banner-section -->
    <section class="banner-section p_relative">
        <div class="banner-carousel owl-theme owl-carousel owl-nav-none dots-style-one" data-aos="fade-in">
            @foreach($banners as $banner)
                <div class="slide-item p_relative">
                    <div class="bg-layer" style="background-image: url({{ $banner->image_full_url }});">
                    </div>
                    <div class="auto-container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                                <div class="content-box p_relative d_block z_5">
                                    <span class="upper-text">Sustainable Future</span>
                                    <h2 class="p_relative d_block">{{ $banner->title }}</h2>
                                    <p class="p_relative d_block">{{ $banner->subtitle }}</p>
                                    <div class="btn-box">
                                        <a href="" class="theme-btn">Read
                                            More<span></span><span></span><span></span><span></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                                <div class="image-box p_relative">
                                    <div class="badge"><img src="{{ asset('assets/images/icons/badge-1.png') }}" alt=""></div>
                                    <figure class="image clearfix"><img src="{{ $banner->image_full_url }}"
                                            alt="{{ $banner->title }}"></figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- <div class="slide-item p_relative">
                            <div class="bg-layer" style="background-image: url({{ asset('assets/images/banner/banner-1.jpg') }});"></div>
                            <div class="auto-container">
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                                        <div class="content-box p_relative d_block z_5">
                                            <span class="upper-text">Laundry Experts</span>
                                            <h2 class="p_relative d_block">Smarter Laundry, Softer Fabrics</h2>
                                            <p class="p_relative d_block">Our unique formulation gives every stain a fight till they disappear, while taking ultimate care of your skin and garments.</p>
                                            <div class="btn-box">
                                                <a href="" class="theme-btn">Read More<span></span><span></span><span></span><span></span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                                        <div class="image-box p_relative">
                                            <figure class="image clearfix"><img src="{{ asset('assets/images/resource/competitors/britishclean/ld14_laundry.png') }}" alt=""></figure>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="slide-item p_relative">
                            <div class="bg-layer" style="background-image: url({{ asset('assets/images/banner/banner-1.jpg') }});"></div>
                            <div class="auto-container">
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                                        <div class="content-box p_relative d_block z_5">
                                            <span class="upper-text">Auto Detailing</span>
                                            <h2 class="p_relative d_block">Sustainable Science. Superior Shine.</h2>
                                            <p class="p_relative d_block">High-performance results with eco-conscious formulations. Engineered to elevate your automobile care experience.</p>
                                            <div class="btn-box">
                                                <a href="" class="theme-btn">Read More<span></span><span></span><span></span><span></span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                                        <div class="image-box p_relative">
                                            <figure class="image clearfix"><img src="{{ asset('assets/images/resource/competitors/vista/4in1dresser.jpg') }}" alt=""></figure>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div> -->
        </div>
    </section>
    <!-- banner-section end -->


    {{-- ═══════════════════════════════════════════════════════
    2. ANNOUNCEMENT TICKER
    ═══════════════════════════════════════════════════════ --}}
    <div class="hw-ticker">
        <div class="hw-ticker__track">
            @for($i = 0; $i < 4; $i++)
                <span>Free delivery on orders above ₹499</span>
                <span class="hw-ticker__dot"></span>
                <span>Eco-certified formulations</span>
                <span class="hw-ticker__dot"></span>
                <span>Trusted by 10,000+ customers</span>
                <span class="hw-ticker__dot"></span>
                <span>30-day hassle-free returns</span>
                <span class="hw-ticker__dot"></span>
            @endfor
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════
    3. EDITORIAL AD BANNERS (BENTO GRID REDESIGN)
    ═══════════════════════════════════════════════════════ --}}
    <section class="hw-section hw-ad">
        <div class="auto-container">
            <div class="hw-ad__grid">

                {{-- Bento Card 1: Air Fresheners (Hero: Large visual block, premium typography) --}}
                <div class="hw-bento-card hw-bento-card--freshener" data-aos="fade-up">
                    <div class="hw-bento-card__bg"
                        style="background-image: url('{{ asset('assets/images/banners/ad-freshener.png') }}');"></div>
                    <div class="hw-bento-card__overlay"></div>
                    <div class="hw-bento-card__content">
                        <span class="hw-bento-card__tag">Air Fresheners</span>
                        <h2 class="hw-bento-card__title" style="color: #ffffff !important;">Breathe <em>Clean.</em><br>Drive Cool.</h2>
                        <p class="hw-bento-card__desc">Premium long-lasting fragrances engineered for your cabin.</p>
                        <a href="{{ route('shop', ['category' => 'air-fresheners']) }}"
                            class="hw-btn hw-btn--dark hw-btn--sm">
                            Shop Now
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                                <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                    <div class="hw-bento-card__num">01</div>
                </div>

                {{-- Bento Card 2: UV Protection --}}
                <div class="hw-bento-card hw-bento-card--summer" data-aos="fade-up" data-aos-delay="100">
                    <div class="hw-bento-card__bg"
                        style="background-image: url('{{ asset('assets/images/banners/ad-summer.png') }}');"></div>
                    <div class="hw-bento-card__overlay"></div>
                    <div class="hw-bento-card__content">
                        <span class="hw-bento-card__tag hw-bento-card__tag--light">Sun & UV Protection</span>
                        <h2 class="hw-bento-card__title hw-bento-card__title--light" style="color: #ffffff !important;">Summer-Proof<br><em>Your Ride.</em>
                        </h2>
                        <p class="hw-bento-card__desc hw-bento-card__desc--light">Shield paint & interior — own every
                            season.</p>
                        <a href="{{ route('shop') }}" class="hw-btn hw-btn--primary hw-btn--sm">
                            Shop Now
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                                <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                    <div class="hw-bento-card__num hw-bento-card__num--light">02</div>
                </div>

                {{-- Bento Card 3: Bike + Car Combo (Double Width) --}}
                <div class="hw-bento-card hw-bento-card--combo" data-aos="fade-up" data-aos-delay="200">
                    <div class="hw-bento-card__bg"
                        style="background-image: url('{{ asset('assets/images/banners/ad-combo.png') }}');"></div>
                    <div class="hw-bento-card__overlay"></div>
                    <div class="hw-bento-card__content">
                        <span class="hw-bento-card__tag hw-bento-card__tag--light">Premium Combos</span>
                        <h2 class="hw-bento-card__title hw-bento-card__title--light" style="color: #ffffff !important;">Precision for<br><strong>Two & Four
                                Wheels</strong></h2>
                        <p class="hw-bento-card__desc hw-bento-card__desc--light">Showroom results every single time,
                            tailored for your specific vehicle.</p>
                        <div class="hw-bento-card__buttons">
                            <a href="{{route('shop') }}"
                                class="hw-btn hw-btn--outline-light hw-btn--sm">Shop Bike Care</a>
                            <!--<a href="{{ route('shop') }}"-->
                            <!--    class="hw-btn hw-btn--primary hw-btn--sm">Shop Car Care</a>-->
                        </div>
                    </div>
                    <div class="hw-bento-card__num hw-bento-card__num--light">03</div>
                </div>

                {{-- Bento Strips (Dynamic Mini Ad Banners for First Three Categories) --}}
                <div class="hw-bento-strips">
                    @php
                        // Premium pre-curated catchphrases for the first three categories
                        $catchphrases = [
                            0 => "Rider's Obsession, <em>Showroom Shine.</em>",
                            1 => "Pure Cleanliness, <em>Every Single Day.</em>",
                            2 => "Ultimate Protection, <em>Fresh Spaces.</em>"
                        ];
                        $labels = [
                            0 => 'Bestseller',
                            1 => 'New Arrivals',
                            2 => 'Value Deals'
                        ];
                    @endphp
                    @foreach($categories->take(3) as $index => $category)
                        <a href="{{ route('shop', ['category' => $category->slug]) }}" class="hw-bento-strip"
                            data-aos="fade-up" data-aos-delay="{{ 300 + ($index * 100) }}">
                            <div class="hw-bento-strip__bg"
                                style="background-image: url('{{ $category->image_full_url ?? 'https://images.unsplash.com/photo-1545171245-5929d9461bfe?q=80&w=600&auto=format&fit=crop' }}');">
                            </div>
                            <div class="hw-bento-strip__overlay"></div>
                            <div class="hw-bento-strip__body">
                                <span class="hw-bento-strip__label">{{ $labels[$index] ?? 'Featured' }}</span>
                                <h3 class="hw-bento-strip__title" style="color: #ffffff !important;">{{ $category->category_name }}</h3>
                                <span class="hw-bento-strip__catchphrase">{!! $catchphrases[$index] ?? 'Premium <em>Care Range</em>' !!}</span>
                            </div>
                            <div class="hw-bento-strip__arrow-wrap">
                                <span class="hw-bento-strip__arrow">→</span>
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    <div class="hw-rule"></div>
    <div class="hw-ticker">
        <div class="hw-ticker__track">
            @for($i = 0; $i < 4; $i++)
                <span>Free delivery on orders above ₹499</span>
                <span class="hw-ticker__dot"></span>
                <span>Eco-certified formulations</span>
                <span class="hw-ticker__dot"></span>
                <span>Trusted by 10,000+ customers</span>
                <span class="hw-ticker__dot"></span>
                <span>30-day hassle-free returns</span>
                <span class="hw-ticker__dot"></span>
            @endfor
        </div>
    </div>


    <!-- category-section -->
    <section class="category-section pt_90 pb_100 bg-lime-light p_relative" data-aos="fade-up">
        <!-- Next Level: Floating Blobs -->
        <div class="floating-blob blob-1"></div>
        <div class="floating-blob blob-2"></div>
        <div class="auto-container">
            <div class="sec-title mb_30">
                <h2>Popular Categories</h2>
                <a href="{{ route('categories') }}">View All Categories</a>
            </div>
            <div class="category-carousel owl-carousel owl-theme owl-dots-none owl-nav-none">
                @forelse($categories as $category)
                    <div class="category-block-one">
                        <div class="inner-box">
                            <figure class="image-box"><a href="{{ route('shop', ['category' => $category->slug]) }}"><img
                                        src="{{ $category->image_full_url }}" alt="{{ $category->category_name }}"
                                        style="object-fit: contain;"></a></figure>
                            <h4><a
                                    href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->category_name }}</a>
                            </h4>
                            <span class="text">{{ $category->products_count ?? 0 }} items</span>
                        </div>
                    </div>
                @empty
                    <p class="centred">No categories available.</p>
                @endforelse
            </div>
        </div>
    </section>
    <!-- category-section end -->


    <!-- popular-picks-section -->
    <section class="shop-style-two pb_100 bg-blue-light p_relative">
        <div class="floating-blob blob-3"></div>
        <!-- SVG Wave Separator -->
        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    class="shape-fill"></path>
            </svg>
        </div>
        <div class="auto-container">
            <div class="sec-title mb_35" data-text="Popular">
                <h2>Today's popular picks</h2>
            </div>
            <div class="inner-container">
                <div class="items-container row clearfix">
                    @forelse($featuredProducts as $product)
                        <x-product-card :product="$product" style="featured" />
                    @empty
                        <div class="col-12 text-center py-5">
                            <p>New featured products coming soon!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <!-- popular-picks-section end -->





    <!-- brand-section -->
    <section class="brand-section pt_100 pb_100 bg-gray-light p_relative">
        <div class="floating-blob blob-4"></div>
        <!-- SVG Wave Separator -->
        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    style="fill: #eff6ff;"></path>
            </svg>
        </div>
        <div class="pattern-layer">
            <div class="pattern-1" style="background-image: url(assets/images/shape/shape-2.png);"></div>
            <div class="pattern-2" style="background-image: url(assets/images/shape/shape-3.png);"></div>
        </div>
        <div class="auto-container">
            <div class="sec-title mb_30" data-text="Brands">
                <h2>Shop by Brands</h2>
                <a href="{{ route('brands') }}">View All Brands</a>
            </div>
            <div class="inner-container">
                <div class="row clearfix">
                    @forelse($brands as $brand)
                        <div class="col-lg-2 col-md-4 col-sm-12 brand-block">
                            <div class="brand-block-one">
                                <div class="inner-box">
                                    <a href="{{ route('shop', ['brand' => $brand->slug ?? $brand->brand_id]) }}">
                                        <figure class="image"><img src="{{ $brand->logo_full_url }}"
                                                alt="{{ $brand->brand_name }}" style="height: 60px; object-fit: contain;">
                                        </figure>
                                        <span>{{ $brand->brand_name }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="centred w-100">Top brands coming soon.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <!-- brand-section end -->


    <!-- trending-section -->
    <section class="shop-style-two pb_150 bg-orange-light p_relative" style="overflow: hidden;">
        <div class="floating-blob blob-5"></div>
        <!-- SVG Wave Separator -->
        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    style="fill: #fafafa;"></path>
            </svg>
        </div>
        <div class="auto-container">
            <div class="sec-title mb_35" data-text="Trending">
                <h2>Trending Products</h2>
            </div>
            <div class="inner-container">
                <div class="items-container row clearfix">
                    @forelse($trendingProducts->take(8) as $product)
                        <x-product-card :product="$product" style="featured" />
                    @empty
                        <div class="col-12 text-center py-5">
                            <p>Trending selections following soon.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <!-- trending-section end -->





    <!-- shop-style-two -->
    <!-- shop-style-two -->
    <section class="shop-style-two pt_150 pb_150 bg-purple-light p_relative">
        <div class="floating-blob"
            style="top: -50px; right: 10%; background: radial-gradient(circle, #f5f3ff 0%, transparent 70%);"></div>
        <!-- SVG Wave Separator -->
        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    style="fill: #fff7ed;"></path>
            </svg>
        </div>
        <div class="pattern-layer">
            <div class="pattern-1" style="background-image: url({{ asset('assets/images/shape/shape-4.png') }});"></div>
            <div class="pattern-2" style="background-image: url({{ asset('assets/images/shape/shape-5.png') }});"></div>
        </div>
        <div class="auto-container">
            <div class="sortable-masonry">
                <div class="title-box mb_30">
                    <div class="sec-title" data-text="Products">
                        <h2>Latest Products</h2>
                    </div>
                    <ul class="filter-tabs filter-btns clearfix">
                        <li class="active filter" data-role="button" data-filter=".all">All</li>
                        @foreach($categories->take(5) as $cat)
                            <li class="filter" data-role="button"
                                data-filter=".cat-{{ \Illuminate\Support\Str::slug($cat->category_name) }}">
                                {{ $cat->category_name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="inner-container">
                    <div class="items-container row clearfix">
                        @forelse($latestProducts as $product)
                            <x-product-card :product="$product" style="masonry" />
                        @empty
                            <div class="col-12 text-center py-5">
                                <p>Products coming soon. Check back shortly!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- No Results Message (Visible only when filtering results in zero items) -->
                    <div id="no-products-found" class="text-center py-5 mt-4"
                        style="display: none; background: rgba(255,255,255,0.5); border-radius: 20px; border: 2px dashed var(--brand-primary);">
                        <div class="inner-box">
                            <i class="icon-60 mb-3" style="font-size: 50px; color: var(--brand-primary); opacity: 0.5;"></i>
                            <h3 style="font-weight: 800; color: var(--brand-dark);">Category Coming Soon</h3>
                            <p style="font-size: 16px; color: #64748b;">We are currently moving stock into this category.
                                <br>Please check back in a few days!</p>
                            <button type="button" class="theme-btn mt-3"
                                onclick="$('.filter-tabs li[data-filter=\'.all\']').click();">Show All Products</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Wait for Isotope to be ready
            const checkIsotope = setInterval(() => {
                const $container = $('.sortable-masonry .items-container');
                if ($container.length && $container.data('isotope')) {
                    clearInterval(checkIsotope);

                    $('.filter-tabs li').on('click', function () {
                        setTimeout(() => {
                            const visibleItems = $container.data('isotope').filteredItems.length;
                            const $noMsg = $('#no-products-found');

                            if (visibleItems === 0) {
                                $container.css('height', '0px');
                                $noMsg.fadeIn(400);
                            } else {
                                $noMsg.fadeOut(200);
                            }
                        }, 600); // Wait for transition to complete
                    });
                }
            }, 500);
        });
    </script>
    <!-- shop-style-two end -->




    <!-- news-section -->
    @if($latestBlogs->count() > 0)
        <section class="news-section pt_100 pb_100 bg-teal-light p_relative">
            <div class="floating-blob"
                style="bottom: -50px; left: 10%; background: radial-gradient(circle, #ecfdf5 0%, transparent 70%);"></div>
            <!-- SVG Wave Separator -->
            <div class="wave-divider">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path
                        d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                        style="fill: #f5f3ff;"></path>
                </svg>
            </div>
            <div class="auto-container">
                <div class="sec-title mb_30" data-text="News">
                    <h2>Latest News</h2>
                    <a href="{{ route('blog') }}">View All News</a>
                </div>
                <div class="row clearfix">
                    @foreach($latestBlogs as $blog)
                        <div class="col-lg-4 col-md-6 col-sm-12 news-block">
                            <div class="news-block-one wow fadeInUp animated" data-wow-delay="{{ $loop->index * 300 }}ms"
                                data-wow-duration="1500ms">
                                <div class="inner-box">
                                    <div class="image-box">
                                        <figure class="image"><a href="{{ route('blog-details', $blog->slug) }}"><img
                                                    src="{{ $blog->image_full_url }}" alt="{{ $blog->title }}"></a></figure>
                                    </div>
                                    <div class="lower-content">
                                        <span class="category">{{ $blog->category->name ?? 'News' }}</span>
                                        <h3><a href="{{ route('blog-details', $blog->slug) }}">{{ $blog->title }}</a></h3>
                                        <ul class="post-info">
                                            <li>By <a
                                                    href="{{ route('blog', ['author' => $blog->author_id]) }}">{{ $blog->author->name ?? 'Admin' }}</a>
                                            </li>
                                            <li>{{ $blog->created_at->format('M d, Y') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- news-section end -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Geist:wght@300;400;500;600;700&display=swap');

        /* ── Full Bleed Hero Banner Custom Styles ── */
        .banner-section {
            background-color: var(--paper-warm);
            padding: 0 !important;
        }

        .banner-carousel .slide-item {
            min-height: 580px;
            height: 55vh;
            padding: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 991px) {
            .banner-carousel .slide-item {
                min-height: 400px;
                height: 45vh;
            }
        }

        @media (max-width: 576px) {
            .banner-carousel .slide-item {
                min-height: 260px;
                height: 32vh;
            }
        }

        .banner-carousel .slide-item .bg-layer {
            background-position: center center !important;
            background-size: cover !important;
            width: 100%;
            height: 100%;
        }

        /* Hide text/image columns for pure full-bleed image display */
        .banner-carousel .content-column,
        .banner-carousel .image-column {
            display: none !important;
        }

        .shop-block-two .inner-box .image-box .image img {
            width: 117px;
            height: 117px;
            object-fit: contain;
            transition: all 500ms ease;
        }

        .news-block-one .image-box .image img {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }

        /* ── Modern Premium UI Configuration (Distinct from White Theme) ── */
        :root {
            --brand-primary: #bbd700;
            --brand-dark: #111416;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.6);
            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.04);
            --shadow-hover: 0 12px 30px rgba(0, 0, 0, 0.08);
            --radius-main: 20px;

            /* Bento Grid & Ticker Custom Properties */
            --lime: #c8de00;
            --lime-dark: #9bac00;
            --lime-pale: #f4f9d0;
            --ink: #0f1014;
            --ink-mid: #444750;
            --ink-light: #8b8f98;
            --paper: #ffffff;
            --paper-warm: #f9f8f5;
            --paper-gray: #f2f2ef;
            --border: #e8e8e4;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 24px;
            --font-display: 'Cormorant Garamond', Georgia, serif;
            --font-body: 'Geist', -apple-system, sans-serif;
            --ease: cubic-bezier(.4, 0, .2, 1);
        }

        /* ── Hide messy elements ── */
        .wave-divider {
            display: none !important;
        }

        .sec-title h2::before {
            display: none !important;
        }

        /* Hide the watermark text */

        /* ── Soften floating blobs for ambiance ── */
        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(20px, 30px) scale(1.05);
            }
        }

        .floating-blob {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            pointer-events: none;
            animation: float 25s ease-in-out infinite;
            opacity: 0.15;
            /* Much softer than before */
        }

        .blob-1 {
            top: -100px;
            right: -50px;
            background: radial-gradient(circle, #bbd700 0%, transparent 70%);
        }

        .blob-2 {
            bottom: -100px;
            left: -100px;
            background: radial-gradient(circle, #1a9fd4 0%, transparent 70%);
        }

        .blob-3 {
            top: 10%;
            left: 5%;
            background: radial-gradient(circle, #8b5cf6 0%, transparent 70%);
        }

        .blob-4 {
            bottom: 0%;
            right: 0%;
            background: radial-gradient(circle, #10b981 0%, transparent 70%);
        }

        .blob-5 {
            top: -50px;
            left: 20%;
            background: radial-gradient(circle, #f97316 0%, transparent 70%);
        }

        /* ── Refined Card Styles (Clean Glassmorphism) ── */
        .inner-box {
            position: relative;
            background: var(--glass-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: var(--radius-main) !important;
            padding: 20px;
            border: 1px solid var(--glass-border) !important;
            box-shadow: var(--shadow-soft) !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            z-index: 2;
            overflow: hidden;
            background-size: unset !important;
            animation: none !important;
            /* Removed dizzying gradient animation */
        }

        .inner-box:hover {
            transform: translateY(-8px) !important;
            box-shadow: var(--shadow-hover) !important;
            border-color: rgba(187, 215, 0, 0.4) !important;
            z-index: 10;
            /* Removed the aggressive 3D rotateX/Y for a premium feel */
        }

        /* Keep overlay content visible for shop items */
        .shop-block-one .inner-box,
        .shop-block-two .inner-box {
            overflow: visible !important;
        }

        /* ── Section Backgrounds: Ultra-Subtle Pastel Overlays ── */
        /* Increased overlay opacity to 0.97 for a very clean, slightly tinted look */
        .bg-lime-light {
            background: linear-gradient(rgba(247, 254, 231, 0.97), rgba(247, 254, 231, 0.97)), url('https://images.unsplash.com/photo-1582735689369-4fe89db7114c?q=80&w=2070&auto=format&fit=crop') center/cover scroll;
        }

        .bg-blue-light {
            background: linear-gradient(rgba(239, 246, 255, 0.97), rgba(239, 246, 255, 0.97)), url('https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=2070&auto=format&fit=crop') center/cover scroll;
        }

        .bg-gray-light {
            background: linear-gradient(rgba(250, 250, 250, 0.97), rgba(250, 250, 250, 0.97)), url('https://images.unsplash.com/photo-1545173168-9f1947eebb7f?q=80&w=2071&auto=format&fit=crop') center/cover scroll;
        }

        .bg-orange-light {
            background: linear-gradient(rgba(255, 247, 237, 0.97), rgba(255, 247, 237, 0.97)), url('https://images.unsplash.com/photo-1563453392212-326f5e854473?q=80&w=2070&auto=format&fit=crop') center/cover scroll;
        }

        .bg-purple-light {
            background: linear-gradient(rgba(245, 243, 255, 0.97), rgba(245, 243, 255, 0.97)), url('https://images.unsplash.com/photo-1521566652839-697aa473761a?q=80&w=2071&auto=format&fit=crop') center/cover scroll;
        }

        .bg-teal-light {
            background: linear-gradient(rgba(236, 253, 245, 0.97), rgba(236, 253, 245, 0.97)), url('https://images.unsplash.com/photo-1551733510-46603a118e69?q=80&w=2071&auto=format&fit=crop') center/cover scroll;
        }

        /* ── Typography Polish ── */
        .sec-title h2 {
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--brand-dark);
            position: relative;
            z-index: 2;
        }

        .sec-title a {
            font-weight: 600;
            color: var(--brand-dark);
            border-bottom: 2px solid var(--brand-primary);
            padding-bottom: 2px;
            transition: color 0.2s;
        }

        .sec-title a:hover {
            color: #9ab800;
        }

        /* ── Advanced Hover Effects ── */
        .category-block-one .inner-box .image-box {
            transition: all 0.5s ease;
        }

        .category-block-one .inner-box:hover .image-box {
            transform: scale(1.08) translateY(-5px);
        }

        /* ── Fix for Shop Items Overlapping Next Section ── */
        .shop-block-one,
        .shop-block-two {
            margin-bottom: 50px;
        }

        /* ── Ensure sections stack naturally ── */
        body {
            overflow-x: hidden;
        }

        /* ── Buttons ── */
        .theme-btn {
            border-radius: 50px !important;
            padding: 12px 35px !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            background: var(--brand-primary) !important;
            color: var(--brand-dark) !important;
            box-shadow: 0 8px 20px rgba(187, 215, 0, 0.2) !important;
            transition: all 0.3s ease !important;
        }

        .theme-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(187, 215, 0, 0.3) !important;
        }

        /* ── Bento Grid & Ticker Unique Styles ── */
        .hw-ticker {
            background: var(--ink);
            color: rgba(255, 255, 255, .7);
            overflow: hidden;
            padding: 11px 0;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .5px;
        }

        .hw-ticker__track {
            display: flex;
            gap: 32px;
            align-items: center;
            white-space: nowrap;
            animation: hw-ticker 30s linear infinite;
            width: max-content;
        }

        .hw-ticker__dot {
            width: 4px;
            height: 4px;
            background: var(--lime);
            border-radius: 50%;
            flex-shrink: 0;
        }

        @keyframes hw-ticker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        /* Bento Layout */
        .hw-ad {
            padding: 90px 0;
            background: var(--paper-warm);
        }

        .hw-ad__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: minmax(280px, auto);
            gap: 24px;
        }

        /* Bento Cards */
        .hw-bento-card {
            position: relative;
            /* border-radius: var(--radius-lg); */
            overflow: hidden;
            padding: 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            background-size: cover;
            background-position: center;
            border: 1px solid var(--border);
            transition: all 0.5s var(--ease);
            min-height: 380px;
        }

        .hw-bento-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .hw-bento-card__bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 8s var(--ease);
            z-index: 1;
        }

        .hw-bento-card:hover .hw-bento-card__bg {
            transform: scale(1.05);
        }

        .hw-bento-card__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 16, 20, 0) 0%, rgba(15, 16, 20, 0.85) 100%);
            z-index: 2;
            transition: opacity 0.5s var(--ease);
        }

        .hw-bento-card--freshener .hw-bento-card__overlay {
            background: linear-gradient(180deg, rgba(244, 249, 208, 0) 0%, rgba(244, 249, 208, 0.95) 100%);
        }

        .hw-bento-card:hover .hw-bento-card__overlay {
            opacity: 0.95;
        }

        .hw-bento-card__content {
            position: relative;
            z-index: 3;
            max-width: 440px;
        }

        .hw-bento-card__num {
            position: absolute;
            top: 40px;
            right: 48px;
            font-family: var(--font-display);
            font-size: 80px;
            font-weight: 700;
            color: rgba(15, 16, 20, 0.05);
            line-height: 1;
            user-select: none;
            z-index: 2;
            transition: all 0.5s var(--ease);
        }

        .hw-bento-card__num--light {
            color: rgba(255, 255, 255, 0.05);
        }

        .hw-bento-card:hover .hw-bento-card__num {
            transform: scale(1.1) translateY(-5px);
        }

        /* Specific Sizes & Layouts */
        .hw-bento-card--freshener {
            grid-column: span 3;
        }

        .hw-bento-card--summer {
            grid-column: span 3;
        }

        .hw-bento-card--combo {
            grid-column: span 2;
        }

        .hw-bento-strips {
            grid-column: span 1;
            display: flex;
            flex-direction: column;
            gap: 16px;
            justify-content: space-between;
        }

        /* Typography & Tags */
        .hw-bento-card__tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--lime-dark);
            background: rgba(200, 222, 0, .2);
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 20px;
        }

        .hw-bento-card__tag--light {
            color: var(--lime);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .hw-bento-card__title {
            font-family: var(--font-display);
            font-size: clamp(28px, 3.5vw, 42px);
            font-weight: 600;
            line-height: 1.1;
            color: var(--ink);
            margin-bottom: 16px;
            letter-spacing: -.5px;
        }

        .hw-bento-card__title em {
            font-style: italic;
            color: var(--lime-dark);
        }

        .hw-bento-card__title--light {
            color: #ffffff;
        }

        .hw-bento-card__title--light strong {
            color: var(--lime);
        }

        .hw-bento-card__desc {
            font-size: 14px;
            color: white !important;
            line-height: 1.65;
            margin-bottom: 32px;
        }

        .hw-bento-card__desc--light {
            color: rgba(255, 255, 255, 0.65);
        }

        .hw-bento-card__buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Buttons */
        .hw-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 50px;
            text-decoration: none;
            transition: all .25s var(--ease);
            cursor: pointer;
            border: none;
            outline: none;
        }

        .hw-btn--primary {
            background: var(--lime);
            color: var(--ink);
            box-shadow: 0 4px 18px rgba(200, 222, 0, .3);
        }

        .hw-btn--primary:hover {
            background: var(--lime-dark);
            color: var(--ink);
            transform: translateY(-2px);
            box-shadow: 0 8px 26px rgba(200, 222, 0, .38);
        }

        .hw-btn--outline {
            background: transparent;
            color: var(--ink);
            border: 1.5px solid var(--border);
        }

        .hw-btn--outline:hover {
            border-color: var(--ink);
            transform: translateY(-2px);
        }

        .hw-btn--dark {
            background: var(--ink);
            color: var(--paper);
        }

        .hw-btn--dark:hover {
            background: #2a2d35;
            transform: translateY(-2px);
        }

        /* Outline light button */
        .hw-btn--outline-light {
            background: transparent;
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
        }

        .hw-btn--outline-light:hover {
            border-color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Bento Strips (Mini Ad Banners Redesign) */
        .hw-bento-strip {
            position: relative;
            display: flex;
            align-items: flex-end;
            text-decoration: none;
            /*border-radius: var(--radius-lg);*/
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: var(--ink);
            padding: 24px 28px;
            min-height: 140px;
            flex: 1;
            transition: all 0.4s var(--ease);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .hw-bento-strip:hover {
            border-color: var(--lime);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12), 0 0 20px rgba(200, 222, 0, 0.15);
            transform: translateY(-4px);
        }

        .hw-bento-strip__bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 6s var(--ease);
            z-index: 1;
        }

        .hw-bento-strip:hover .hw-bento-strip__bg {
            transform: scale(1.08);
        }

        .hw-bento-strip__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15, 16, 20, 0.9) 0%, rgba(15, 16, 20, 0.4) 60%, rgba(15, 16, 20, 0.8) 100%);
            z-index: 2;
            transition: background 0.4s var(--ease);
        }

        .hw-bento-strip:hover .hw-bento-strip__overlay {
            background: linear-gradient(90deg, rgba(15, 16, 20, 0.95) 0%, rgba(15, 16, 20, 0.5) 60%, rgba(15, 16, 20, 0.95) 100%);
        }

        .hw-bento-strip__body {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
        }

        .hw-bento-strip__label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--lime);
            margin-bottom: 4px;
        }

        .hw-bento-strip__title {
            font-family: var(--font-body);
            font-size: 18px;
            font-weight: 700;
            color: #ffffff !important;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .hw-bento-strip__catchphrase {
            font-family: var(--font-display);
            font-size: 14px;
            font-style: italic;
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 400;
        }

        .hw-bento-strip__catchphrase em {
            color: var(--lime) !important;
            font-style: italic;
        }

        .hw-bento-strip__arrow-wrap {
            position: relative;
            z-index: 3;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s var(--ease);
            backdrop-filter: blur(5px);
            margin-left: 16px;
            align-self: center;
        }

        .hw-bento-strip:hover .hw-bento-strip__arrow-wrap {
            background: var(--lime);
            border-color: var(--lime);
            box-shadow: 0 0 15px rgba(200, 222, 0, 0.4);
        }

        .hw-bento-strip__arrow {
            font-size: 16px;
            color: #ffffff;
            transition: transform 0.3s var(--ease);
        }

        .hw-bento-strip:hover .hw-bento-strip__arrow {
            transform: translateX(3px);
            color: var(--ink);
        }

        /* Rule */
        .hw-rule {
            width: 100%;
            height: 1px;
            background: var(--border);
            margin: 0;
        }

        .hw-section {
            position: relative;
            z-index: 1;
        }

        /* Responsive Breakdown */
        @media (max-width: 1200px) {
            .hw-ad__grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hw-bento-card--freshener,
            .hw-bento-card--summer {
                grid-column: span 1;
            }

            .hw-bento-card--combo {
                grid-column: span 2;
            }

            .hw-bento-strips {
                grid-column: span 2;
                flex-direction: row;
            }
        }

        @media (max-width: 768px) {
            .hw-ad__grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .hw-bento-card--freshener,
            .hw-bento-card--summer,
            .hw-bento-card--combo,
            .hw-bento-strips {
                grid-column: span 1;
            }

            .hw-bento-strips {
                flex-direction: column;
            }

            .hw-bento-card {
                padding: 36px 30px;
                min-height: 320px;
            }
        }
    </style>




@endsection