@extends('layouts.app')
@section('title', 'Edit Certificate')

@section('content')
<div class="row">
    <div class="col-md-7 col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Edit Certificate</h4>
                <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back
                </a>
            </div>

            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('certificates.update', $certificate) }}"
                      enctype="multipart/form-data">
                    @csrf @method('PUT')

                    {{-- Title --}}
                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">Certificate Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               id="title" name="title"
                               value="{{ old('title', $certificate->title) }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Certificate Image</label>

                        {{-- Current image --}}
                        @if($certificate->image_path)
                            <div class="mb-2">
                                <p class="form-text mb-1">Current image:</p>
                                <img src="{{ asset('uploads/' . $certificate->image_path) }}"
                                     alt="{{ $certificate->title }}"
                                     style="max-height:130px; border-radius:12px; border:1px solid #e2e8f0; padding:6px; background:#f8fafc;">
                            </div>
                        @endif

                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image"
                               accept="image/*"
                               onchange="previewCertImage(this)">
                        <div class="form-text">Leave blank to keep existing image. Max 5 MB.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div id="imgPreview" class="mt-3" style="display:none;">
                            <img id="previewImg" src="" alt="New Preview"
                                 style="max-height:130px; border-radius:12px; border:1px solid #bbd700; padding:6px; background:#f8fafc;">
                        </div>
                    </div>


                    {{-- Active --}}
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $certificate->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active (visible on website)</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                            <i class="bx bx-save me-1"></i> Update Certificate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewCertImage(input) {
    const preview = document.getElementById('imgPreview');
    const img     = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
document.querySelector('form').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
});
</script>
@endpush
