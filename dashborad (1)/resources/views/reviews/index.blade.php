@extends('layouts.app')
@section('title') Product Reviews | Enterprise Dashboard @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Home @endslot
        @slot('title') Product Reviews @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="card-title mb-0">Reviews Management</h4>
                        </div>
                        <div>
                            <form action="{{ route('reviews.index') }}" method="GET" class="d-flex">
                                <input type="text" name="q" class="form-control me-2" placeholder="Search reviews..." value="{{ request('q') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                                @if(request('q'))
                                    <a href="{{ route('reviews.index') }}" class="btn btn-light ms-2">Clear</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Product</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Approved</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{ $review->user->name ?? 'N/A' }}</a></h5>
                                            <p class="text-muted mb-0">{{ $review->user->email ?? 'N/A' }}</p>
                                        </td>
                                        <td>
                                            @if($review->product)
                                                <h5 class="font-size-14 mb-1">{{ $review->product->name }}</h5>
                                                <p class="text-muted mb-0">{{ $review->product->sku }}</p>
                                            @else
                                                <span class="text-muted">Product Deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $review->comment }}">
                                                {{ $review->comment }}
                                            </div>
                                        </td>
                                        <td>{{ $review->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $review->id }}" {{ $review->is_approved ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No reviews found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleCheckboxes = document.querySelectorAll('.toggle-status');
        toggleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const reviewId = this.getAttribute('data-id');
                const isChecked = this.checked;
                
                fetch(`/reviews/${reviewId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Toastify success
                        if (typeof Toastify === 'function') {
                            Toastify({
                                text: data.message,
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#4fbe87",
                            }).showToast();
                        }
                    } else {
                        this.checked = !isChecked;
                        alert('Something went wrong!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !isChecked;
                    alert('Network error occurred');
                });
            });
        });
    });
</script>
@endpush
