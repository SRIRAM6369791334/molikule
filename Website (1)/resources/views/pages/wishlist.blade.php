@extends('layouts.app')

@section('content')
<!-- page-title -->
<section class="page-title-two centred">
    <div class="auto-container">
        <div class="content-box">
            <h1>My Wishlist</h1>
            <p>Save your favorite items for later</p>
        </div>
    </div>
</section>
<!-- page-title end -->

<!-- wishlist section -->
<section class="cart-section pt_100 pb_100">
    <div class="auto-container">
        <div class="table-outer mb_30">
            <table class="cart-table">
                <thead class="cart-header">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock Status</th>
                        <th>Action</th>
                        <th>&nbsp;</th>
                    </tr>    
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr id="wishlist-item-{{ $product->product_id }}">
                        <td class="product-column">
                            <div class="product-box">
                                <figure class="image-box"><img src="{{ $product->image_full_url }}" alt="{{ $product->name }}" style="object-fit: contain;"></figure>
                                <h6><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></h6>    
                            </div>
                        </td>
                        <td>{{ formatPrice($product->mrp_price) }}</td>
                        <td>
                            @if($product->stock_quantity > 0)
                                <span class="text-success"><i class="fas fa-check-circle"></i> In Stock</span>
                            @else
                                <span class="text-danger"><i class="fas fa-times-circle"></i> Out of Stock</span>
                            @endif
                        </td>
                        <td>
                            @if($product->stock_quantity > 0)
                                <button type="button" class="theme-btn add-to-cart-btn" data-id="{{ $product->product_id }}" data-variant-id="">
                                    Add to Cart<span></span><span></span><span></span><span></span>
                                </button>
                            @else
                                <button type="button" class="theme-btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                    Out of Stock<span></span><span></span><span></span><span></span>
                                </button>
                            @endif
                        </td>
                        <td>
                            <button class="cancel-btn remove-from-wishlist" data-id="{{ $product->product_id }}" title="Remove from Wishlist">
                                <i class="icon-38"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-wishlist-box">
                                <i class="icon-7" style="font-size: 64px; color: #ccc;"></i>
                                <h3 class="mt-3">Your wishlist is empty</h3>
                                <p class="text-muted">You haven't added any items to your wishlist yet.</p>
                                <a href="{{ route('shop') }}" class="theme-btn mt-3">Continue Shopping<span></span><span></span><span></span><span></span></a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>    
            </table>
        </div>
    </div>
</section>
<!-- wishlist section end -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Remove from wishlist
    $('.remove-from-wishlist').click(function() {
        const productId = $(this).data('id');
        const btn = $(this);
        
        Swal.fire({
            title: 'Remove from Wishlist?',
            text: "Are you sure you want to remove this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('wishlist.toggle') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.success && response.status === 'removed') {
                            $(`#wishlist-item-${productId}`).fadeOut(300, function() {
                                $(this).remove();
                                if ($('tbody tr').length === 0) {
                                    location.reload(); // Show empty message
                                }
                            });
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed',
                                text: 'Item removed from wishlist',
                                timer: 1500,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
