@extends('layouts.app')

@section('title', 'View Banner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Banner Details</h4>
                    </div>
                    <div>
                        <a href="{{ route('banners.edit', $banner) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i>Edit Banner
                        </a>
                        <a href="{{ route('banners.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Back to Banners
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Banner Image -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Banner Image</h5>
                            </div>
                            <div class="card-body text-center">
                                @if($banner->image_url)
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                         class="img-fluid rounded shadow">
                                @else
                                    <div class="bg-light rounded p-5">
                                        <i class="bx bx-image display-1 text-muted"></i>
                                        <p class="text-muted mt-2">No image available</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Min Image -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Min Image</h5>
                            </div>
                            <div class="card-body text-center">
                                @if($banner->minimage_url)
                                    <img src="{{ $banner->minimage_url }}" alt="Min image"
                                         class="img-fluid rounded shadow">
                                @else
                                    <div class="bg-light rounded p-5">
                                        <i class="bx bx-image display-1 text-muted"></i>
                                        <p class="text-muted mt-2">No min image available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Banner Details -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Banner Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Title:</td>
                                        <td>{{ $banner->title }}</td>
                                    </tr>
                                    @if($banner->subtitle)
                                    <tr>
                                        <td class="fw-bold">Subtitle:</td>
                                        <td>{{ $banner->subtitle }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="fw-bold">Position:</td>
                                        <td >Home page</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>{!! $banner->status_badge !!}</td>
                                    </tr>
                                    @if($banner->link_url)
                                    <tr>
                                        <td class="fw-bold">Link URL:</td>
                                        <td>
                                            <a href="{{ $banner->link_url }}" target="_blank" class="text-primary">
                                                {{ $banner->link_url }}
                                                <i class="bx bx-link-external ms-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($banner->link_text)
                                    <tr>
                                        <td class="fw-bold">Link Text:</td>
                                        <td>{{ $banner->link_text }}</td>
                                    </tr>
                                    @endif
                                    @if($banner->starts_at)
                                    <tr>
                                        <td class="fw-bold">Start Date:</td>
                                        <td>{{ $banner->starts_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @endif
                                    @if($banner->expires_at)
                                    <tr>
                                        <td class="fw-bold">End Date:</td>
                                        <td>{{ $banner->expires_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="fw-bold">Created:</td>
                                        <td>{{ $banner->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Updated:</td>
                                        <td>{{ $banner->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <!-- <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">

                                    <form method="POST" action="{{ route('banners.toggle-status', $banner) }}" class="w-100">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="is_active" value="{{ $banner->is_active ? '0' : '1' }}">
                                        <button type="submit" class="btn btn-outline-warning w-100">
                                            <i class="bx bx-{{ $banner->is_active ? 'x-circle' : 'check-circle' }} me-1"></i>
                                            {{ $banner->is_active ? 'Deactivate' : 'Activate' }} Banner
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('banners.destroy', $banner) }}"
                                          class="d-inline" onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            <i class="bx bx-trash me-1"></i>Delete Banner
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.dataset.url;
            const bannerId = this.dataset.id;

            if (!confirm('Are you sure you want to change the banner status?')) {
                return;
            }

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...';

            // Get current active state from button text
            const isCurrentlyActive = this.textContent.includes('Deactivate');
            const newActiveState = !isCurrentlyActive; // Toggle the current state

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    is_active: newActiveState
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update status');
                    this.disabled = false;
                    this.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
                this.disabled = false;
                this.innerHTML = originalText;
            });
        });
    });
});
</script>
@endsection
