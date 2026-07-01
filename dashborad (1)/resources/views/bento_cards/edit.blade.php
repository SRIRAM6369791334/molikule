@extends('layouts.app')
@section('title', 'Edit Bento Card')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Banners @endslot
    @slot('title') Edit Card @endslot
@endcomponent

<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">Edit Bento Card</h4>
            </div>
            
            <div class="card-body">
                <form action="{{ route('bento-cards.update', $bentoCard) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Image</label>
                        @if($bentoCard->image_path)
                            <div class="mb-2">
                                <img src="{{ asset('uploads/bento_cards/' . $bentoCard->image_path) }}" 
                                     alt="Current Image" style="max-height:100px; border-radius:8px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               name="image" accept="image/*">
                        <div class="form-text text-muted">Leave blank to keep current image.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3 d-none">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Tag</label>
                            <input type="text" class="form-control @error('tag') is-invalid @enderror" 
                                   name="tag" value="{{ old('tag', $bentoCard->tag) }}" placeholder="e.g. Air Fresheners">
                            @error('tag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 d-none">
                        <label class="form-label fw-bold">Title (Supports HTML like &lt;em&gt;, &lt;strong&gt;, &lt;br&gt;)</label>
                        <textarea class="form-control @error('title') is-invalid @enderror" 
                                  name="title" rows="2">{{ old('title', $bentoCard->title) }}</textarea>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 d-none">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3">{{ old('description', $bentoCard->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 d-none">
                            <label class="form-label fw-bold">Sort Order</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                   name="sort_order" value="{{ old('sort_order', $bentoCard->sort_order) }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" 
                                       {{ old('is_active', $bentoCard->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="isActive">Publish (Active)</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">
                        <a href="{{ route('bento-cards.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Bento Card</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
