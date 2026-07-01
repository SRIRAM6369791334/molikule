{{-- Product card component used by home, shop, and related product sections. --}}
@props([
    'product',
    'style' => 'masonry',
    'noCol' => false,
])

@php
    $imageUrl = $product->image_full_url;
    $detailUrl = route('shop.show', $product->slug);
    
    // Senior Dev Fix: Ensure subcategory products match the top-level filter tabs on the homepage
    $mainCategory = $product->category;
    if ($mainCategory && $mainCategory->parent_id) {
        // Simple loop to find the root parent
        $rootCategory = $mainCategory;
        while($rootCategory->parent) {
            $rootCategory = $rootCategory->parent;
        }
        $categoryName = $rootCategory->category_name;
    } else {
        $categoryName = $mainCategory->category_name ?? '';
    }

    $catSlug = \Illuminate\Support\Str::slug($categoryName ?: 'all');
    $hasDiscount = $product->compare_price && $product->compare_price > $product->mrp_price;
    $discountPct = $hasDiscount
        ? round((($product->compare_price - $product->mrp_price) / $product->compare_price) * 100)
        : 0;
@endphp

@if($style === 'masonry')
    @if(!$noCol)
        <div class="col-lg-3 col-md-6 col-sm-12 masonry-item small-column all cat-{{ $catSlug }}">
    @endif
        <div class="shop-block-two">
            <div class="inner-box">
                <div class="image-box">
                    @if($product->badge)
                        <span class="{{ $product->badge_class }}">{{ $product->badge }}</span>
                    @endif
                    <ul class="option-list">
                        {{-- <li><a href="{{ $imageUrl }}" class="lightbox-image" data-fancybox="gallery"><i class="icon-12"></i></a></li> --}}
                        {{-- <li><a href="{{ $detailUrl }}"><i class="icon-1"></i></a></li> --}}
                        <li>
                            <button
                                type="button"
                                class="add-to-wishlist-btn {{ isInWishlist($product->product_id) ? 'active' : '' }}"
                                data-id="{{ $product->product_id }}"
                            >
                                <i class="icon-7"></i>
                            </button>
                        </li>
                    </ul>
                    <figure class="image">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="object-fit:contain;">
                    </figure>
                </div>
                <div class="content-box">
                    <h6><a href="{{ $detailUrl }}">{{ $product->name }}</a></h6>

                    @php /* Variant pills removed from shop page as requested */ @endphp

                    {{-- <ul class="rating">
                        @for($i = 0; $i < 5; $i++)
                            <li><i class="icon-41"></i></li>
                        @endfor
                        <li><span>({{ $product->stock_quantity > 0 ? rand(3, 8) : 0 }})</span></li>
                    </ul> --}}
                    <h5>
                        @if($hasDiscount)
                            <del>{{ formatPrice($product->compare_price) }}</del>
                        @endif
                        {{ formatPrice($product->mrp_price) }}
                    </h5>
                    <div class="cart-btn">
                        <button type="button" class="add-to-cart-btn" 
                            data-id="{{ $product->product_id }}"
                            data-variant-id="{{ $product->variants->first()->id ?? '' }}"
                            data-redirect="true"
                            {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                            {{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Add to Cart' }}<i class="icon-27"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @if(!$noCol)
        </div>
    @endif
@elseif($style === 'featured')
    @if(!$noCol)
        <div class="col-lg-3 col-md-6 col-sm-12 shop-block">
    @endif
        <div class="shop-block-one">
            <div class="inner-box">
                <div class="image-box">
                    @if($hasDiscount)
                        <span class="discount-product">{{ $discountPct }}% Off</span>
                    @elseif($product->badge)
                        <span class="{{ $product->badge_class }}">{{ $product->badge }}</span>
                    @endif

                    <ul class="option-list-two">
                        {{-- <li><a href="{{ $imageUrl }}" class="lightbox-image" data-fancybox="gallery"><i class="icon-12"></i></a></li> --}}
                        {{-- <li><a href="{{ $detailUrl }}"><i class="icon-1"></i></a></li> --}}
                        <li>
                            <button
                                type="button"
                                class="add-to-wishlist-btn {{ isInWishlist($product->product_id) ? 'active' : '' }}"
                                data-id="{{ $product->product_id }}"
                            >
                                <i class="icon-7"></i>
                            </button>
                        </li>
                    </ul>
                    <figure class="image">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="object-fit:contain;">
                    </figure>
                </div>
                <div class="lower-content">
                    <span class="text">{{ $categoryName }}</span>
                    <h4><a href="{{ $detailUrl }}">{{ $product->name }}</a></h4>
                    <h5>
                        @if($hasDiscount)
                            <del>{{ formatPrice($product->compare_price) }}</del>
                        @endif
                        {{ formatPrice($product->mrp_price) }}
                    </h5>
                    {{-- <ul class="rating">
                        @for($i = 0; $i < 5; $i++)
                            <li><i class="icon-41"></i></li>
                        @endfor
                        <li><span>(5)</span></li>
                    </ul> --}}
                    <span class="product-stock">
                        <i class="icon-39"></i>{!! $product->stock_quantity > 0 ? 'In Stock' : '<span class="text-danger">Stock Out</span>' !!}
                    </span>

                    <div class="overlay-content">
                        {{-- <div class="text-box mb_15">
                            <h6 style="font-size: 13px; line-height: 1.4; color: #64748b; font-weight: 600; margin-bottom: 0; text-transform: none !important; letter-spacing: 0 !important;">
                                {{ $product->short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 80) }}
                            </h6>
                        </div> --}}
                        <div class="cart-btn">
                            <button type="button" class="theme-btn add-to-cart-btn" 
                                data-id="{{ $product->product_id }}"
                                data-variant-id="{{ $product->variants->first()->id ?? '' }}"
                                data-redirect="true"
                                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                {{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Add to Cart' }}<span></span><span></span><span></span><span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @if(!$noCol)
        </div>
    @endif
@elseif($style === 'list')
    <div class="shop-block-list mb_30">
        <div class="inner-box">
            <div class="image-box">
                @if($hasDiscount)
                    <span class="discount-product">{{ $discountPct }}% Off</span>
                @elseif($product->badge)
                    <span class="{{ $product->badge_class }}">{{ $product->badge }}</span>
                @endif

                <ul class="option-list-two">
                    {{-- <li><a href="{{ $imageUrl }}" class="lightbox-image" data-fancybox="gallery"><i class="icon-12"></i></a></li> --}}
                    {{-- <li><a href="{{ $detailUrl }}"><i class="icon-1"></i></a></li> --}}
                    <li>
                        <button
                            type="button"
                            class="add-to-wishlist-btn {{ isInWishlist($product->product_id) ? 'active' : '' }}"
                            data-id="{{ $product->product_id }}"
                        >
                            <i class="icon-7"></i>
                        </button>
                    </li>
                </ul>
                <figure class="image">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="object-fit:contain;">
                </figure>
            </div>
            <div class="content-box">
                <span class="text">{{ $categoryName }}</span>
                <h4><a href="{{ $detailUrl }}">{{ $product->name }}</a></h4>
                <h5>
                    @if($hasDiscount)
                        <del>{{ formatPrice($product->compare_price) }}</del>
                    @endif
                    {{ formatPrice($product->mrp_price) }}
                </h5>
                {{-- <p>{{ \Illuminate\Support\Str::limit($product->short_description, 150) }}</p> --}}

                @php /* Variant pills removed from shop page as requested */ @endphp

                <ul class="rating mb_20">
                    @for($i = 0; $i < 5; $i++)
                        <li><i class="icon-41"></i></li>
                    @endfor
                </ul>
                <div class="cart-btn">
                    <button type="button" class="theme-btn add-to-cart-btn" 
                        data-id="{{ $product->product_id }}"
                        data-variant-id="{{ $product->variants->first()->id ?? '' }}"
                        data-redirect="true"
                        {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Add to Cart' }}<span></span><span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@else
    @if(!$noCol)
        <div class="col-lg-3 col-md-6 col-sm-12 shop-block">
    @endif
        <div class="shop-block-one">
            <div class="inner-box">
                <div class="image-box">
                    @if($hasDiscount)
                        <span class="discount-product">{{ $discountPct }}% Off</span>
                    @elseif($product->badge)
                        <span class="{{ $product->badge_class }}">{{ $product->badge }}</span>
                    @endif

                    <ul class="option-list">
                        {{-- <li><a href="{{ $imageUrl }}" class="lightbox-image" data-fancybox="gallery"><i class="icon-12"></i></a></li> --}}
                        <li><a href="{{ $detailUrl }}"><i class="icon-13"></i></a></li>
                        <li>
                            <button
                                type="button"
                                class="add-to-wishlist-btn {{ isInWishlist($product->product_id) ? 'active' : '' }}"
                                data-id="{{ $product->product_id }}"
                            >
                                <i class="icon-7"></i>
                            </button>
                        </li>
                    </ul>
                    <figure class="image">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="object-fit:contain;">
                    </figure>
                </div>
                <div class="lower-content">
                    <span class="text">{{ $categoryName }}</span>
                    <h4><a href="{{ $detailUrl }}">{{ $product->name }}</a></h4>
                    <h5>
                        @if($hasDiscount)
                            <del>{{ formatPrice($product->compare_price) }}</del>
                        @endif
                        {{ formatPrice($product->mrp_price) }}
                    </h5>

                    @php /* Variant pills removed from shop page as requested */ @endphp

                    {{-- <ul class="rating">
                        @for($i = 0; $i < 5; $i++)
                            <li><i class="icon-41"></i></li>
                        @endfor
                    </ul> --}}
                    <span class="product-stock">
                        <i class="icon-39"></i>{{ $product->stock_label }}
                    </span>
                </div>
            </div>
        </div>
    @if(!$noCol)
        </div>
    @endif
@endif
<style>
    :root {
        --brand-primary: #bbd700;
        --brand-dark: #111416;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.6);
        --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.04);
        --shadow-hover: 0 12px 30px rgba(0, 0, 0, 0.08);
        --radius-main: 20px;
    }

    .shop-block-one .inner-box,
    .shop-block-two .inner-box,
    .shop-block-list .inner-box {
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
        overflow: visible !important;
        text-align: center;
    }

    .lower-content {
        position: relative !important;
    }

    .shop-block-one:hover,
    .shop-block-two:hover,
    .shop-block-list:hover {
        z-index: 10 !important;
    }

    .shop-block-one:hover .inner-box,
    .shop-block-two:hover .inner-box,
    .shop-block-list:hover .inner-box {
        transform: translateY(-8px) !important;
        box-shadow: var(--shadow-hover) !important;
        border-color: rgba(187, 215, 0, 0.4) !important;
        z-index: 11 !important;
    }

    .inner-box h4 a,
    .inner-box h6 a {
        color: var(--brand-dark) !important;
        font-weight: 800 !important;
        font-size: 18px !important;
        line-height: 1.3 !important;
    }
    .inner-box h4 a:hover,
    .inner-box h6 a:hover {
        color: var(--brand-primary) !important;
    }
    .inner-box h5 {
        color: var(--brand-primary) !important;
        font-weight: 800 !important;
        font-size: 20px !important;
    }
    .product-stock i, .product-stock {
        color: var(--brand-primary) !important;
        font-weight: 700;
        font-size: 12px;
    }
    .add-to-cart-btn {
        background: var(--brand-primary) !important;
        color: var(--brand-dark) !important;
        border-radius: 50px !important;
        font-weight: 900 !important;
        font-size: 13px !important;
        padding: 12px 25px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        transition: all 0.3s !important;
        border: none !important;
        width: 100%;
    }
    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(187, 215, 0, 0.3) !important;
    }
    .overlay-content {
        position: absolute !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 100% !important;
        background: var(--glass-bg) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        padding: 15px !important;
        border-radius: 0 0 var(--radius-main) var(--radius-main) !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transform: translateY(20px) !important;
        z-index: 5 !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        pointer-events: none !important; /* Prevent blocking clicks when hidden */
    }

    .shop-block-one:hover .overlay-content,
    .shop-block-two:hover .overlay-content,
    .shop-block-list:hover .overlay-content {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
        pointer-events: all !important;
    }

    .option-list li a:hover,
    .option-list-two li a:hover,
    .add-to-wishlist-btn:hover,
    .add-to-wishlist-btn.active {
        color: var(--brand-primary) !important;
    }
</style>
