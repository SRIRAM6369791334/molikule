@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Add New Blog</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Molikule</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Blog Content</h4>
                    <p class="card-title-desc">Fill in the details for the new blog post.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Blog Title *</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required minlength="5" maxlength="255">
                                </div>

                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Excerpt (Summary) * (The content field must be at least 20 characters.)</label>
                                    <textarea name="excerpt" id="excerpt" class="form-control" rows="3" required minlength="20" maxlength="500">{{ old('excerpt') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Content * (The content field must be at least 50 characters.)</label>
                                    <x-ckeditor name="content" id="content" :value="old('content')" rows="10" />
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="category_name" class="form-label">Category *</label>
                                    <input type="text" name="category_name" id="category_name" class="form-control" list="category-list" value="{{ old('category_name') }}" placeholder="Enter or select category" required>
                                    <datalist id="category-list">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="mb-3">
                                    <label for="author_name" class="form-label">Author *</label>
                                    <input type="text" name="author_name" id="author_name" class="form-control" list="author-list" value="{{ old('author_name') }}" placeholder="Enter or select author" required>
                                    <datalist id="author-list">
                                        @foreach($authors as $author)
                                            <option value="{{ $author->name }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Feature Image *</label>
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                                    <div id="image-preview" class="mt-2 d-none text-center border rounded p-2">
                                        <img src="#" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tags_input" class="form-label">Tags</label>
                                    <input
                                        type="text"
                                        name="tags_input"
                                        id="tags_input"
                                        class="form-control @error('tags_input') is-invalid @enderror"
                                        value="{{ old('tags_input') }}"
                                        placeholder="e.g. hygiene, floor care, disinfection"
                                    >
                                    @error('tags_input')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enter tags manually, separated by commas.</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">Publish Immediately</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Blog Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewDiv = document.getElementById('image-preview');
        const previewImg = previewDiv.querySelector('img');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.classList.add('d-none');
        }
    });

</script>
@endpush
