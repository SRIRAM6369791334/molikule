@extends('layouts.app')

@section('content')

@php
    $thumbs = [];
    if (!empty($product->image_full_url)) {
        $thumbs[] = $product->image_full_url;
    }
    if (!empty($product->gallery_images) && is_array($product->gallery_images)) {
        foreach ($product->gallery_images as $galImg) {
            $thumbs[] = productImageUrl($galImg);
        }
    }
    if ($product->variants) {
        foreach ($product->variants as $variant) {
            $variantUrl = $variant->variant_image_full_url;
            if ($variantUrl && !in_array($variantUrl, $thumbs)) {
                $thumbs[] = $variantUrl;
            }
        }
    }
    $thumbs = array_slice(array_values(array_unique($thumbs)), 0, 4);
@endphp

{{-- ═══════════════════════ PAGE TITLE ═══════════════════════ --}}
<section class="pd-hero">
    <div class="auto-container">
        <div class="pd-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">→</span>
            <a href="{{ route('shop') }}">Shop</a>
            <span class="sep">→</span>
            <span class="current">{{ $product->name }}</span>
        </div>
        <h1 class="pd-hero-title">Product <em>Details.</em></h1>
    </div>
</section>

{{-- ═══════════════════════ PRODUCT MAIN ═══════════════════════ --}}
<section class="pd-main">
    <div class="auto-container">
        <div class="pd-grid">

            {{-- ── LEFT: IMAGE ── --}}
            <div class="pd-image-col">
                <div class="pd-image-wrap">
                    @if($product->badge)
                        <span class="{{ $product->badge_class }} pd-badge">{{ $product->badge }}</span>
                    @endif
                    <figure class="pd-image-fig">
                        <a href="{{ $product->image_full_url }}" class="lightbox-image" data-fancybox="gallery">
                            <img src="{{ $product->image_full_url }}" alt="{{ $product->name }}" class="pd-product-img" id="main-product-image">
                        </a>
                    </figure>
                    <div class="pd-image-decor"></div>
                </div>

                {{-- ── THUMBNAILS ── --}}
                <div class="pd-thumbs-wrap {{ count($thumbs) <= 1 ? 'd-none' : '' }}" id="product-thumbnails-container">
                    @foreach($thumbs as $thumb)
                        <div class="pd-thumb-item {{ $loop->first ? 'active' : '' }}" onclick="changeProductImage('{{ $thumb }}', this)">
                            <img src="{{ $thumb }}" alt="Thumbnail">
                        </div>
                    @endforeach
                </div>

                {{-- ── TABS MOVED UNDER IMAGE ── --}}
                <div class="pd-tabs-wrap mt-4">
                    <div class="pd-tab-nav">
                        <button class="pd-tab-btn active" data-tab="tab-desc">Description</button>
                        <button class="pd-tab-btn" data-tab="tab-reviews">Reviews ({{ str_pad($product->reviews->count(), 2, '0', STR_PAD_LEFT) }})</button>
                        <button class="pd-tab-btn" data-tab="tab-spec">Specification</button>
                    </div>

                    {{-- Description --}}
                    <div class="pd-tab-panel active" id="tab-desc">
                        <div class="pd-desc-content">
                            {!! $product->description !!}
                        </div>
                    </div>

                    {{-- Reviews --}}
                    <div class="pd-tab-panel" id="tab-reviews">
                        <div class="pd-reviews-wrap">
                            @if(session('success'))
                                <div class="pd-alert pd-alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="pd-alert pd-alert-danger">{{ session('error') }}</div>
                            @endif
                            @if($errors->any())
                                <div class="pd-alert pd-alert-danger">
                                    <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                                </div>
                            @endif

                            @forelse($product->reviews as $review)
                                <div class="pd-review-card">
                                    <div class="pd-review-head">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}&background=bbd700&color=0f172a&size=44" alt="" class="pd-review-avatar">
                                        <div>
                                            <strong class="pd-review-name">{{ $review->user->name ?? 'Verified Customer' }}</strong>
                                            <span class="pd-review-date">{{ $review->created_at->format('F Y') }}</span>
                                        </div>
                                        <div class="pd-review-stars ml-auto">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="icon-41" style="{{ $i > $review->rating ? 'color:#e2e8f0' : 'color:#f59e0b' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="pd-review-body">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <div class="pd-no-reviews">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    <p>No reviews yet. Be the first to review this product!</p>
                                </div>
                            @endforelse

                            @if($canReview)
                                <div class="pd-review-form-wrap">
                                    <h3 class="pd-review-form-title">Write Your Review</h3>
                                    <form method="post" action="{{ route('review.store', $product->slug) }}">
                                        @csrf
                                        <div class="pd-form-group">
                                            <label class="pd-form-label">Your Rating <span class="req">*</span></label>
                                            <div class="pd-star-input">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                                    <label for="star{{ $i }}" title="{{ $i }} stars"><i class="icon-41"></i></label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="pd-form-group">
                                            <label class="pd-form-label">Your Review <span class="req">*</span></label>
                                            <textarea name="comment" required class="pd-textarea" placeholder="Share your experience with this product..."></textarea>
                                        </div>
                                        <button type="submit" class="pd-submit-btn">Submit Review</button>
                                    </form>
                                </div>
                            @else
                                <div class="pd-review-msg">
                                    @if(!auth()->check())
                                        <p>Please <a href="{{ route('login') }}">login</a> to write a review.</p>
                                    @elseif($pendingReview ?? false)
                                        <div class="pd-alert pd-alert-info">
                                            <i class="fas fa-clock"></i> Your review has been submitted and is awaiting admin approval.
                                        </div>
                                    @else
                                        <p>You can only review products you have purchased and received.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Specification --}}
                    <div class="pd-tab-panel" id="tab-spec">
                        <div class="pd-spec-table">
                            <div class="pd-spec-row">
                                <span class="pd-spec-key">SKU</span>
                                <span class="pd-spec-val" id="pd-spec-sku">{{ optional(optional($product->variants)->first())->sku ?? ($product->sku ?? 'MK-' . str_pad($product->product_id, 4, '0', STR_PAD_LEFT)) }}</span>
                            </div>
                            <div class="pd-spec-row">
                                <span class="pd-spec-key">Brand</span>
                                <span class="pd-spec-val">
                                    @if($product->brand)
                                        <a href="{{ $product->brand->shop_url }}" style="color: var(--theme-color-solid); font-weight: 700;">{{ $product->brand->brand_name }}</a>
                                    @else
                                        Molikule
                                    @endif
                                </span>
                            </div>
                            <div class="pd-spec-row">
                                <span class="pd-spec-key">Category</span>
                                <span class="pd-spec-val">{{ optional($product->category)->category_name }}</span>
                            </div>
                            {{-- @if($product->variants && $product->variants->count())
                                <div class="pd-spec-row">
                                    <span class="pd-spec-key">Options</span>
                                    <span class="pd-spec-val">{{ $product->variants->map(fn($v) => $v->variant_label)->unique()->implode(', ') }}</span>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: CONTENT ── --}}
            <div class="pd-info-col">
                <div class="pd-info-card">

                    {{-- Category tag --}}
                    <div class="pd-cat-tag">{{ optional($product->category)->category_name }}</div>

                    {{-- Title --}}
                    <h2 class="pd-product-name">{{ $product->name }}</h2>

                    {{-- Price + Rating --}}
                    <div class="pd-price-row">
                        <div class="pd-price-block">
                            <span id="main-price-display" class="pd-price-main">₹{{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <del class="pd-price-del">₹{{ number_format($product->compare_price, 2) }}</del>
                            @endif
                        </div>
                        {{-- <div class="pd-rating-block">
                            <div class="pd-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="icon-41 star-icon"></i>
                                @endfor
                            </div>
                            <span class="pd-rating-val">(5.0)</span>
                        </div> --}}
                    </div>

                    {{-- Short description --}}
                    {{-- <p class="pd-short-desc">
                        {{ $product->short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 160) }}
                    </p> --}}

                    {{-- Meta info grid --}}
                    <div class="pd-meta-grid">
                        <div class="pd-meta-item">
                            <span class="pd-meta-label">Brand</span>
                            <span class="pd-meta-value">{{ optional($product->brand)->brand_name ?? 'Molikule' }}</span>
                        </div>
                        <div class="pd-meta-item">
                            <span class="pd-meta-label">SKU</span>
                            <span class="pd-meta-value">{{ $product->sku ?? 'MK-' . str_pad($product->product_id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="pd-meta-item pd-meta-full">
                            <span class="pd-meta-label">Availability</span>
                            <span class="pd-stock-badge">
                                <span class="pd-stock-dot"></span>
                                <span id="main-stock-display">In Stock ({{ $product->stock_quantity ?? '50+' }} Units)</span>
                            </span>
                        </div>
                    </div>

                    {{-- Highlights --}}
                    {{-- <ul class="pd-highlights">
                        <li><i class="fas fa-check-circle"></i> Fully Tested &amp; Functional</li>
                        <li><i class="fas fa-gem"></i> Premium Quality Formulation</li>
                        <li><i class="fas fa-leaf"></i> Eco-Friendly &amp; Sustainable</li>
                    </ul> --}}

                    {{-- ════ FORM ════ --}}
                    <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                        {{-- Variant Matrix --}}
                        @if(!empty($variantMatrix['attributes']) && count($variantMatrix['attributes']) > 0)
                            <div class="pd-variants" id="variant-selector">
                                @foreach($variantMatrix['attributes'] as $attr)
                                    <div class="pd-variant-group">
                                        <h6 class="pd-variant-label">{{ $attr['name'] }} <span class="req">*</span></h6>
                                        <div class="pd-variant-options">
                                            @foreach($attr['options'] as $option)
                                                <label class="pd-pill" for="attr-{{ $attr['id'] }}-{{ $option['id'] }}">
                                                    <input
                                                        class="pd-pill-input variant-attr-radio"
                                                        type="radio"
                                                        id="attr-{{ $attr['id'] }}-{{ $option['id'] }}"
                                                        name="attr_{{ $attr['id'] }}"
                                                        value="{{ $option['id'] }}"
                                                        data-attr-id="{{ $attr['id'] }}"
                                                    >
                                                    <span class="pd-pill-text">{{ $option['value'] }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                <input type="hidden" id="resolved-variant-id" name="variant_id" value="">
                                <div id="variant-status" class="pd-variant-status">
                                    <span id="variant-stock-badge" class="pd-vstatus-badge"></span>
                                </div>
                            </div>

                            @push('scripts')
                            <script>
                            (function() {
                                const variantMatrix = @json($variantMatrix);
                                const priceDisplay   = document.getElementById('main-price-display');
                                const hiddenInput    = document.getElementById('resolved-variant-id');
                                const stockBadge     = document.getElementById('variant-stock-badge');
                                const skuDisplay     = document.getElementById('pd-spec-sku');
                                const defaultThumbsHtml = document.getElementById('product-thumbnails-container') ? document.getElementById('product-thumbnails-container').innerHTML : '';

                                function resolveVariant() {
                                    const selected = {};
                                    document.querySelectorAll('.variant-attr-radio:checked').forEach(radio => {
                                        selected[radio.dataset.attrId] = String(radio.value);
                                    });

                                    // Filter visibility of other options based on current selection (Waterfall Approach)
                                    variantMatrix.attributes.forEach((attr, index) => {
                                        const attrId = attr.id;
                                        const options = document.querySelectorAll(`.variant-attr-radio[data-attr-id="${attrId}"]`);
                                        const previousAttrs = variantMatrix.attributes.slice(0, index);
                                        
                                        options.forEach(opt => {
                                            const valId = String(opt.value);
                                            // Check if this option exists in ANY valid combination with attributes ABOVE it
                                            const isPossible = variantMatrix.combinations.some(combo => {
                                                if (String(combo.attributes[attrId]) !== valId) return false;
                                                return previousAttrs.every(pAttr => {
                                                    const sValId = selected[pAttr.id];
                                                    if (!sValId) return true; // Not selected yet
                                                    return String(combo.attributes[pAttr.id]) === sValId;
                                                });
                                            });

                                            const label = opt.closest('.pd-pill');
                                            if (label) {
                                                if (isPossible) {
                                                    label.style.display = '';
                                                    opt.disabled = false;
                                                } else {
                                                    label.style.display = 'none';
                                                    opt.disabled = true;
                                                    if (opt.checked) opt.checked = false; // Uncheck if hidden
                                                }
                                            }
                                        });
                                    });

                                    // If current selection is incomplete or invalid, try to auto-select first possible option for missing/invalid ones
                                    let hasChanged = false;
                                    variantMatrix.attributes.forEach(attr => {
                                        const checked = document.querySelector(`.variant-attr-radio[data-attr-id="${attr.id}"]:checked`);
                                        if (!checked || checked.disabled) {
                                            const firstVisible = document.querySelector(`.variant-attr-radio[data-attr-id="${attr.id}"]:not([disabled])`);
                                            if (firstVisible) {
                                                firstVisible.checked = true;
                                                hasChanged = true;
                                            }
                                        }
                                    });

                                    if (hasChanged) return resolveVariant(); // Re-run if we auto-selected something

                                    // Now find the final matching variant
                                    const match = variantMatrix.combinations.find(combo =>
                                        Object.entries(combo.attributes).every(([attrId, valId]) =>
                                            selected[attrId] == String(valId)
                                        )
                                    );

                                    const productImage = document.getElementById('main-product-image');
                                    const mainStock    = document.getElementById('main-stock-display');
                                    const addToCartBtn = document.querySelector('.main-cart-btn');

                                    if (match) {
                                        hiddenInput.value = match.variant_id;
                                        if (priceDisplay) {
                                            let html = '₹' + match.price.toFixed(2);
                                            if (match.mrp_price > match.price) {
                                                html += '<del class="pd-price-del" style="font-size:18px;margin-left:12px;">₹' + match.mrp_price.toFixed(2) + '</del>';
                                            }
                                            priceDisplay.innerHTML = html;
                                        }

                                        // Update Image & Thumbnails
                                        const thumbsContainer = document.getElementById('product-thumbnails-container');
                                        if (productImage) {
                                            const variantGallery = (match.gallery && match.gallery.length > 0) ? match.gallery : (match.image ? [match.image] : []);
                                            
                                            if (variantGallery.length > 0) {
                                                // Case B: Variant has custom gallery/image. Show only variant's thumbnails!
                                                const firstImage = variantGallery[0];
                                                productImage.src = firstImage;
                                                const link = productImage.closest('.lightbox-image');
                                                if (link) link.href = firstImage;

                                                if (thumbsContainer) {
                                                    let galleryHtml = '';
                                                    variantGallery.forEach((imgUrl, index) => {
                                                        galleryHtml += `
                                                            <div class="pd-thumb-item ${index === 0 ? 'active' : ''}" onclick="changeProductImage('${imgUrl}', this)">
                                                                <img src="${imgUrl}" alt="Variant Thumbnail">
                                                            </div>
                                                        `;
                                                    });
                                                    thumbsContainer.innerHTML = galleryHtml;
                                                    
                                                    // Limit gallery thumbs to maximum of 4
                                                    const galleryThumbs = thumbsContainer.querySelectorAll('.pd-thumb-item');
                                                    if (galleryThumbs.length > 4) {
                                                        for (let i = 4; i < galleryThumbs.length; i++) {
                                                            galleryThumbs[i].style.display = 'none';
                                                        }
                                                    }
                                                    
                                                    // If count is <= 1, hide container, else show it
                                                    if (variantGallery.length <= 1) {
                                                        thumbsContainer.classList.add('d-none');
                                                    } else {
                                                        thumbsContainer.classList.remove('d-none');
                                                    }
                                                }
                                            } else {
                                                // Case A: Variant has NO custom image/gallery. Restore product's default thumbnails!
                                                if (thumbsContainer) {
                                                    thumbsContainer.innerHTML = defaultThumbsHtml;
                                                    
                                                    // If default thumbs count was <= 1, hide container, else show it
                                                    const hasMultipleThumbs = {{ count($thumbs) > 1 ? 'true' : 'false' }};
                                                    if (hasMultipleThumbs) {
                                                        thumbsContainer.classList.remove('d-none');
                                                    } else {
                                                        thumbsContainer.classList.add('d-none');
                                                    }
                                                    
                                                    // Highlight the first default thumbnail (the main product image)
                                                    const firstThumb = thumbsContainer.querySelector('.pd-thumb-item');
                                                    if (firstThumb) {
                                                        firstThumb.classList.add('active');
                                                        const firstThumbImg = firstThumb.querySelector('img');
                                                        if (firstThumbImg) {
                                                            productImage.src = firstThumbImg.src;
                                                            const link = productImage.closest('.lightbox-image');
                                                            if (link) link.href = firstThumbImg.src;
                                                        }
                                                    }
                                                } else {
                                                    productImage.src = "{{ $product->image_full_url }}";
                                                    const link = productImage.closest('.lightbox-image');
                                                    if (link) link.href = "{{ $product->image_full_url }}";
                                                }
                                            }
                                        }

                                        // Update Stock
                                        const qtyInput = document.getElementById('pd-qty');
                                        if (match.stock > 0) {
                                            const stockText = match.stock > 10 ? 'Available' : 'Only ' + match.stock + ' Left';
                                            stockBadge.textContent = '✓ ' + stockText;
                                            stockBadge.className = 'pd-vstatus-badge status-ok';
                                            if (mainStock) mainStock.textContent = 'In Stock (' + match.stock + ' Units)';
                                            if (addToCartBtn) { addToCartBtn.disabled = false; addToCartBtn.textContent = 'Add To Cart'; }
                                            if (qtyInput) {
                                                qtyInput.setAttribute('max', match.stock);
                                                if (parseInt(qtyInput.value) > match.stock) qtyInput.value = match.stock;
                                            }
                                        } else {
                                            stockBadge.textContent = '✕ Out of Stock';
                                            stockBadge.className = 'pd-vstatus-badge status-out';
                                            if (mainStock) mainStock.textContent = 'Out of Stock';
                                            if (addToCartBtn) { addToCartBtn.disabled = true; addToCartBtn.textContent = 'Out of Stock'; }
                                            if (qtyInput) qtyInput.setAttribute('max', 0);
                                        }

                                        // Update SKU in Spec Tab
                                        if (skuDisplay) {
                                            skuDisplay.textContent = match.sku || '{{ $product->sku ?? 'MK-' . str_pad($product->product_id, 4, '0', STR_PAD_LEFT) }}';
                                        }
                                    }
                                }

                                document.querySelectorAll('.variant-attr-radio').forEach(r => r.addEventListener('change', resolveVariant));

                                const bestCombo = variantMatrix.combinations.find(c => c.stock > 0) || variantMatrix.combinations[0];
                                if (bestCombo) {
                                    Object.entries(bestCombo.attributes).forEach(([attrId, valId]) => {
                                        const radio = document.getElementById('attr-' + attrId + '-' + valId);
                                        if (radio) { radio.checked = true; }
                                    });
                                    resolveVariant();
                                }
                            })();
                            </script>
                            @endpush

                        @elseif($product->variants && $product->variants->count() > 0)
                            <div class="pd-variants">
                                <h6 class="pd-variant-label">Select Option <span class="req">*</span></h6>
                                <div class="pd-variant-options">
                                    @foreach($product->variants as $variant)
                                        <label class="pd-pill" for="variant-{{ $variant->id }}">
                                            <input class="pd-pill-input simple-variant-radio" type="radio" id="variant-{{ $variant->id }}" name="variant_id" value="{{ $variant->id }}" data-stock="{{ $variant->stock_quantity }}" data-sku="{{ $variant->sku }}" {{ $loop->first ? 'checked' : '' }}>
                                            <span class="pd-pill-text">{{ $variant->variant_label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <script>
                                document.querySelectorAll('.simple-variant-radio').forEach(r => {
                                    if (r.checked) {
                                        r.closest('.pd-pill').classList.add('active');
                                        const qtyInput = document.getElementById('pd-qty');
                                        if (qtyInput) qtyInput.setAttribute('max', r.dataset.stock || 1);
                                    }
                                    r.addEventListener('change', function() {
                                        document.querySelectorAll('.simple-variant-radio').forEach(x => x.closest('.pd-pill').classList.remove('active'));
                                        this.closest('.pd-pill').classList.add('active');
                                        
                                        const qtyInput = document.getElementById('pd-qty');
                                        if (qtyInput) {
                                            const maxStock = parseInt(this.dataset.stock) || 0;
                                            qtyInput.setAttribute('max', maxStock);
                                            if (parseInt(qtyInput.value) > maxStock) {
                                                qtyInput.value = maxStock > 0 ? maxStock : 1;
                                            }
                                        }
                                        
                                        const skuDisplay = document.getElementById('pd-spec-sku');
                                        if (skuDisplay) {
                                            skuDisplay.textContent = this.dataset.sku || '{{ $product->sku ?? 'MK-' . str_pad($product->product_id, 4, '0', STR_PAD_LEFT) }}';
                                        }
                                    });
                                });
                            </script>
                        @endif

                        {{-- Action Bar --}}
                        <div class="pd-action-bar">
                            <div class="pd-qty-wrap">
                                <button type="button" class="pd-qty-btn" onclick="adjustQty(-1)">−</button>
                                <input class="pd-qty-input" type="number" value="1" min="1" max="{{ $product->stock_quantity ?? 100 }}" name="quantity" id="pd-qty" readonly>
                                <button type="button" class="pd-qty-btn" onclick="adjustQty(1)">+</button>
                            </div>
                            <button type="submit" class="pd-cart-btn main-cart-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Add To Cart
                            </button>
                            <button type="button" class="pd-wish-btn add-to-wishlist-btn {{ isInWishlist($product->product_id) ? 'active' : '' }}" data-id="{{ $product->product_id }}">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                        </div>
                    </form>

                </div>{{-- /pd-info-card --}}
            </div>{{-- /pd-info-col --}}
        </div>{{-- /pd-grid --}}

        {{-- ═══════════════════════ RELATED ═══════════════════════ --}}
        @if($relatedProducts->count() > 0)
            <div class="pd-related">
                <h2 class="pd-related-title">You may also like <em>these.</em></h2>
                <div class="pd-related-grid">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" style="featured" :noCol="true" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>{{-- /auto-container --}}
</section>

{{-- ═══════════════════════ STYLES ═══════════════════════ --}}
<style>
:root {
    --brand:      #bbd700;
    --brand-dark: #8da200;
    --ink:        #0f172a;
    --ink-light:  #475569;
    --muted:      #94a3b8;
    --border:     #e2e8f0;
    --surface:    #f8fafc;
    --white:      #ffffff;
    --radius-sm:  12px;
    --radius-md:  20px;
    --radius-lg:  32px;
    --radius-xl:  44px;
    --shadow-sm:  0 2px 8px rgba(0,0,0,0.06);
    --shadow-md:  0 8px 30px rgba(0,0,0,0.08);
    --shadow-lg:  0 20px 60px rgba(0,0,0,0.10);
    --brand-glow: 0 8px 24px rgba(187,215,0,0.30);
}

/* Prevent global .internal-page styles from turning spans white on this page */
body.internal-page span {
    color: inherit;
}

/* ── HERO ── */
.pd-hero {
    background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
    padding: 70px 0 44px;
    border-bottom: 1px solid var(--border);
}
.pd-breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--white);
    padding: 12px 28px;
    border-radius: 100px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 24px;
}
.pd-breadcrumb a { color: var(--ink-light); text-decoration: none; transition: color .2s; }
.pd-breadcrumb a:hover { color: var(--brand-dark); }
.pd-breadcrumb .sep { color: var(--border); }
.pd-breadcrumb .current { color: var(--brand-dark); }
.pd-hero-title {
    font-size: clamp(36px, 5vw, 60px);
    font-weight: 900;
    color: var(--ink);
    letter-spacing: -2px;
    line-height: 1.05;
    margin: 0;
}
.pd-hero-title em { color: var(--brand); font-style: normal; }

/* ── MAIN SECTION ── */
.pd-main {
    padding: 70px 0 90px;
    background: #f0f4f8;
}

.pd-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: start;
    margin-bottom: 0;
}

/* ── DETAILS SECTION ── */
.pd-details-section {
    padding: 0 0 90px;
    background: #f0f4f8;
}

.pd-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: start;
}

.pd-details-left {}
.pd-details-right {
    position: sticky;
    top: 100px;
}

.pd-trust-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 30px;
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.pd-trust-item {
    display: flex;
    align-items: center;
    gap: 16px;
}
.pd-trust-icon {
    width: 44px;
    height: 44px;
    background: rgba(187,215,0,0.1);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 18px;
    flex-shrink: 0;
}
.pd-trust-text strong { display: block; font-size: 14px; color: var(--ink); margin-bottom: 2px; }
.pd-trust-text span { font-size: 12px; color: var(--muted); }

/* ── IMAGE COLUMN ── */
.pd-image-col {}
.pd-image-wrap {
    position: relative;
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 48px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.8);
    overflow: hidden;
}

/* ── PRODUCT THUMBNAILS GALLERY ── */
.pd-thumbs-wrap {
    display: flex;
    gap: 14px;
    margin-top: 24px;
    margin-bottom: 8px;
    flex-wrap: wrap;
    justify-content: flex-start;
}
.pd-thumb-item {
    width: 82px;
    height: 82px;
    background: var(--white);
    border-radius: var(--radius-sm);
    border: 2px solid var(--border);
    padding: 8px;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}
.pd-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.pd-thumb-item:hover {
    border-color: var(--brand);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.pd-thumb-item:hover img {
    transform: scale(1.06);
}
.pd-thumb-item.active {
    border-color: var(--brand);
    box-shadow: 0 0 0 2px var(--brand), var(--shadow-md);
}
.pd-image-decor {
    position: absolute;
    bottom: -60px;
    right: -60px;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(187,215,0,0.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.pd-badge {
    position: absolute;
    top: 24px;
    left: 24px;
    z-index: 10;
}
.pd-image-fig { margin: 0; }
.pd-product-img {
    width: 100%;
    height: 400px;
    object-fit: contain;
    display: block;
    transition: transform 0.5s cubic-bezier(0.34,1.56,0.64,1);
}
.pd-image-wrap:hover .pd-product-img { transform: scale(1.04); }

/* ── INFO COLUMN ── */
.pd-info-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 44px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,0.8);
}

.pd-cat-tag {
    display: inline-block;
    background: rgba(187,215,0,0.12);
    color: var(--brand-dark);
    padding: 5px 16px;
    border-radius: 100px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 14px;
}

.pd-product-name {
    font-size: clamp(28px, 3.5vw, 44px);
    font-weight: 900;
    color: var(--ink);
    letter-spacing: -1.5px;
    line-height: 1.1;
    margin: 0 0 20px;
}

/* Price row */
.pd-price-row {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}
.pd-price-block { display: flex; align-items: baseline; gap: 12px; }
#main-price-display, .pd-price-main {
    font-size: 36px;
    font-weight: 900;
    color: var(--ink);
    line-height: 1;
}
.pd-price-del {
    font-size: 18px;
    color: var(--muted);
    font-weight: 500;
}
.pd-rating-block {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-left: 20px;
    border-left: 1px solid var(--border);
}
.pd-stars { display: flex; gap: 2px; }
.star-icon { color: #f59e0b; font-size: 13px; }
.pd-rating-val { color: var(--muted); font-size: 12px; font-weight: 700; }

/* Short desc */
.pd-short-desc {
    color: var(--ink-light);
    line-height: 1.75;
    font-size: 15px;
    margin-bottom: 24px;
}

/* Meta grid */
.pd-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    background: var(--surface);
    padding: 20px;
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    margin-bottom: 24px;
}
.pd-meta-item {}
.pd-meta-full { grid-column: span 2; }
.pd-meta-label {
    display: block;
    font-size: 10px;
    color: var(--muted) !important;
    text-transform: uppercase;
    font-weight: 800;
    letter-spacing: 1.2px;
    margin-bottom: 4px;
}
.pd-meta-value {
    font-size: 14px;
    font-weight: 800;
    color: var(--ink) !important;
}
.pd-stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 800;
    color: var(--brand-dark) !important;
}
.pd-stock-dot {
    width: 9px;
    height: 9px;
    background: var(--brand) !important;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(187,215,0,0.25);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 3px rgba(187,215,0,0.25); }
    50%       { box-shadow: 0 0 0 7px rgba(187,215,0,0.08); }
}

/* Highlights */
.pd-highlights {
    list-style: none;
    padding: 0;
    margin: 0 0 28px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.pd-highlights li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--ink-light);
}
.pd-highlights li i { color: var(--brand); font-size: 15px; }

/* Variants */
.pd-variants { margin-bottom: 28px; }
.pd-variant-group { margin-bottom: 20px; }
.pd-variant-label {
    font-size: 11px;
    font-weight: 800;
    color: var(--ink) !important;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 12px;
}
.req { color: #ef4444; }
.pd-variant-options { display: flex; flex-wrap: wrap; gap: 10px; }

/* Pill — label wraps input; no extra checkbox shown */
.pd-pill { display: inline-block; cursor: pointer; }
.pd-pill-input { display: none !important; }
.pd-pill-text {
    display: block;
    padding: 10px 26px;
    border-radius: 100px;
    border: 2px solid var(--border);
    background: var(--white);
    font-size: 13px;
    font-weight: 800;
    color: #64748b;
    transition: all 0.25s;
    line-height: 1;
    user-select: none;
    min-width: 90px;
    text-align: center;
}
.pd-pill-input:checked + .pd-pill-text,
.pd-pill.active .pd-pill-text {
    background: var(--brand);
    color: var(--ink);
    border-color: var(--brand);
    box-shadow: var(--brand-glow);
    transform: translateY(-2px);
}
.pd-pill:hover .pd-pill-text {
    border-color: var(--brand);
    color: var(--brand-dark);
}

/* Variant status badge */
.pd-variant-status { margin-top: 10px; min-height: 26px; }
.pd-vstatus-badge {
    display: inline-block;
    padding: 5px 16px;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 800;
}
.pd-vstatus-badge.status-ok  { background: rgba(40,167,69,0.1);  color: #16a34a; }
.pd-vstatus-badge.status-low { background: rgba(245,158,11,0.1);  color: #b45309; }
.pd-vstatus-badge.status-out { background: rgba(220,53,69,0.1);   color: #dc2626; }

/* Action Bar */
.pd-action-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--surface);
    padding: 18px;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    margin-bottom: 28px;
}
.pd-qty-wrap {
    display: flex;
    align-items: center;
    gap: 0;
    background: var(--white);
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    flex-shrink: 0;
}
.pd-qty-btn {
    width: 40px;
    height: 50px;
    border: none;
    background: transparent;
    font-size: 20px;
    font-weight: 700;
    color: var(--ink-light);
    cursor: pointer;
    transition: background .2s, color .2s;
    line-height: 1;
}
.pd-qty-btn:hover { background: var(--brand); color: var(--ink); }
.pd-qty-input {
    width: 52px;
    height: 50px;
    border: none;
    border-left: 2px solid var(--border);
    border-right: 2px solid var(--border);
    text-align: center;
    font-size: 16px;
    font-weight: 900;
    color: var(--ink);
    background: transparent;
    -moz-appearance: textfield;
}
.pd-qty-input::-webkit-inner-spin-button,
.pd-qty-input::-webkit-outer-spin-button { -webkit-appearance: none; }

.pd-cart-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px 28px;
    height: 54px;
    background: var(--brand);
    color: var(--ink);
    border: none;
    border-radius: var(--radius-md);
    font-size: 14px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
    box-shadow: var(--brand-glow);
}
.pd-cart-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 32px rgba(187,215,0,0.4);
    filter: brightness(1.06);
}
.pd-cart-btn:disabled {
    background: #e2e8f0;
    color: var(--muted);
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

.pd-wish-btn {
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--white);
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    color: var(--muted);
    cursor: pointer;
    transition: all .3s;
}
.pd-wish-btn:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: rgba(239,68,68,0.05);
    transform: scale(1.08);
}
.pd-wish-btn.active {
    border-color: #ef4444;
    color: #fff;
    background: #ef4444;
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
}
.pd-wish-btn.active svg {
    fill: currentColor;
}

/* Share bar */
.pd-share-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}
.pd-share-links { display: flex; align-items: center; gap: 14px; }
.pd-share-label { font-size: 11px; color: var(--muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }
.pd-share-icon { color: #64748b; font-size: 16px; transition: color .2s; text-decoration: none; }
.pd-share-icon:hover { color: var(--brand-dark); }
.pd-secure-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: var(--muted);
    font-weight: 700;
    background: var(--surface);
    padding: 8px 16px;
    border-radius: 100px;
    border: 1px solid var(--border);
}
.pd-secure-badge svg { color: var(--brand); }

/* ── TABS ── */
.pd-tabs-wrap {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 40px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    margin-bottom: 70px;
}
.pd-tab-nav {
    display: flex;
    gap: 0;
    border-bottom: 2px solid var(--border);
    margin-bottom: 36px;
}
.pd-tab-btn {
    background: transparent;
    border: none;
    padding: 14px 28px;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--muted);
    cursor: pointer;
    position: relative;
    transition: color .2s;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
}
.pd-tab-btn.active {
    color: var(--brand-dark);
    border-bottom-color: var(--brand);
}
.pd-tab-btn:hover { color: var(--ink-light); }

.pd-tab-panel { display: none; }
.pd-tab-panel.active { display: block; }

/* Description */
.pd-desc-content {
    color: var(--ink-light);
    line-height: 1.8;
    font-size: 15px;
}

/* Reviews */
.pd-review-card {
    background: var(--surface);
    border-radius: var(--radius-md);
    padding: 24px;
    margin-bottom: 16px;
    border: 1px solid var(--border);
}
.pd-review-head {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 14px;
}
.pd-review-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.pd-review-name { display: block; font-size: 15px; font-weight: 800; color: var(--ink); }
.pd-review-date { font-size: 12px; color: var(--muted); }
.pd-review-stars { display: flex; gap: 2px; margin-left: auto; }
.pd-review-body { color: var(--ink-light); font-size: 14px; line-height: 1.7; margin: 0; }
.ml-auto { margin-left: auto !important; }

.pd-no-reviews {
    text-align: center;
    padding: 40px 20px;
    color: var(--muted);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}
.pd-no-reviews p { font-size: 14px; }

/* Review form */
.pd-review-form-wrap {
    margin-top: 40px;
    padding-top: 36px;
    border-top: 1px solid var(--border);
}
.pd-review-form-title {
    font-size: 22px;
    font-weight: 900;
    color: var(--ink);
    letter-spacing: -0.5px;
    margin-bottom: 28px;
}
.pd-form-group { margin-bottom: 24px; }
.pd-form-label { display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 10px; }
.pd-textarea {
    width: 100%;
    min-height: 120px;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 14px 18px;
    font-size: 14px;
    color: var(--ink);
    font-family: inherit;
    resize: vertical;
    transition: border-color .2s;
    background: var(--surface);
    box-sizing: border-box;
}
.pd-textarea:focus { outline: none; border-color: var(--brand); background: var(--white); }

/* Star rating input */
.pd-star-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
.pd-star-input input { display: none; }
.pd-star-input label { font-size: 28px; color: var(--border); cursor: pointer; transition: all .2s; }
.pd-star-input input:checked ~ label,
.pd-star-input label:hover,
.pd-star-input label:hover ~ label { color: #f59e0b; transform: scale(1.1); }

.pd-submit-btn {
    display: inline-block;
    padding: 16px 40px;
    background: var(--brand);
    color: var(--ink);
    border: none;
    border-radius: 100px;
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    cursor: pointer;
    box-shadow: var(--brand-glow);
    transition: all .3s;
}
.pd-submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 32px rgba(187,215,0,0.4);
}

/* Alerts */
.pd-alert { padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 14px; font-weight: 600; }
.pd-alert-success { background: rgba(40,167,69,0.1);  color: #166534; border: 1px solid rgba(40,167,69,0.2); }
.pd-alert-danger  { background: rgba(220,53,69,0.1);  color: #991b1b;  border: 1px solid rgba(220,53,69,0.2); }
.pd-alert-info    { background: rgba(59,130,246,0.1); color: #1e40af;  border: 1px solid rgba(59,130,246,0.2); }

.pd-review-msg { padding: 20px 0; }
.pd-review-msg a { color: var(--brand-dark); font-weight: 700; }

/* Spec table */
.pd-spec-table {
    border-radius: var(--radius-md);
    overflow: hidden;
    border: 1px solid var(--border);
}
.pd-spec-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}
.pd-spec-row:last-child { border-bottom: none; }
.pd-spec-row:hover { background: var(--surface); }
.pd-spec-key { font-size: 14px; font-weight: 800; color: var(--ink); }
.pd-spec-val { font-size: 14px; color: var(--ink-light); font-weight: 500; }

/* ── RELATED ── */
.pd-related { padding-top: 60px; border-top: 1px solid var(--border); }
.pd-related-title {
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 900;
    color: var(--ink);
    text-align: center;
    letter-spacing: -1.5px;
    margin-bottom: 44px;
}
.pd-related-title em { color: var(--brand); font-style: normal; }
.pd-related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 30px;
}

/* ── QTY JS ── */

/* ── RESPONSIVE ── */
@media (max-width: 991px) {
    /* Change Grid to Flex to enable DOM reordering */
    .pd-grid { display: flex; flex-direction: column; gap: 30px; }
    .pd-details-grid { grid-template-columns: 1fr; gap: 30px; }
    
    /* Strip the wrapper so its children join the flex context */
    .pd-image-col { display: contents; }
    
    /* Force the new visual order: Image -> Thumbs -> Info -> Tabs */
    .pd-image-wrap { order: 1; padding: 32px; width: 100%; }
    .pd-thumbs-wrap { 
        order: 2; 
        width: 100%; 
        justify-content: center; 
        margin-top: 15px; 
        margin-bottom: 15px; 
    }
    .pd-info-col { order: 3; width: 100%; }
    .pd-tabs-wrap { order: 4; width: 100%; margin-top: 0 !important; }

    .pd-product-img { height: 300px; }
    .pd-info-card { padding: 32px; }
}
@media (max-width: 575px) {
    .pd-info-card { padding: 22px; }
    .pd-image-wrap { padding: 20px; }
    
    /* Action Bar Fixes */
    .pd-action-bar { flex-wrap: wrap; gap: 12px; }
    .pd-cart-btn { min-width: 100%; width: 100%; order: -1; }
    .pd-qty-wrap { flex: 1; justify-content: space-between; }
    .pd-wish-btn { flex-shrink: 0; }
    
    .pd-product-name { font-size: 26px; }
    
    /* Tab Fixes (Scroll horizontally instead of squishing) */
    .pd-tabs-wrap { padding: 24px 18px; }
    .pd-tab-nav { 
        flex-wrap: nowrap; 
        overflow-x: auto; 
        white-space: nowrap; 
        padding-bottom: 5px; 
    }
    .pd-tab-btn { padding: 12px 16px; font-size: 11px; flex-shrink: 0; }
    
    /* Meta & Price Fixes */
    .pd-meta-grid { grid-template-columns: 1fr; }
    .pd-meta-full { grid-column: span 1; }
    .pd-price-row { flex-direction: column; align-items: flex-start; gap: 10px; }
    
    /* Hero/Breadcrumb Fixes */
    .pd-hero-title { font-size: 32px; }
    .pd-breadcrumb { font-size: 10px; padding: 10px 16px; flex-wrap: wrap; line-height: 1.5; }
}
</style>

{{-- ── TAB JS ── --}}
<script>
(function() {
    const btns   = document.querySelectorAll('.pd-tab-btn');
    const panels = document.querySelectorAll('.pd-tab-panel');
    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            btns.forEach(b => b.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });
})();

function changeProductImage(src, element) {
    const productImage = document.getElementById('main-product-image');
    if (productImage) {
        productImage.src = src;
        const link = productImage.closest('.lightbox-image');
        if (link) link.href = src;
    }
    
    // Highlight selected thumbnail
    const thumbs = document.querySelectorAll('.pd-thumb-item');
    thumbs.forEach(thumb => thumb.classList.remove('active'));
    if (element) element.classList.add('active');
}

function adjustQty(delta) {
    const input = document.getElementById('pd-qty');
    const max = parseInt(input.getAttribute('max')) || 1;
    const val = parseInt(input.value) + delta;
    
    if (val >= 1 && val <= max) {
        input.value = val;
    } else if (val > max) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Limit Reached',
                text: 'You cannot add more than the available stock (' + max + ').',
                confirmButtonColor: 'var(--brand-dark)',
                background: 'var(--white)',
                color: 'var(--ink)'
            });
        } else {
            alert('You cannot add more than the available stock (' + max + ').');
        }
    }
}
</script>

@endsection