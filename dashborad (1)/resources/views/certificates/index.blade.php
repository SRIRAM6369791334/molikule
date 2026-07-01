@extends('layouts.app')
@section('title', 'Certificates | About Page')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Content @endslot
    @slot('title') Certificates @endslot
@endcomponent

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-1">Certificates</h4>
                    <p class="text-muted mb-0">Showing {{ $certificates->count() }} certificate(s) — displayed on the About Us page marquee.</p>
                </div>
                <a href="{{ route('certificates.create') }}" class="btn btn-primary px-4">
                    <i class="mdi mdi-plus me-1"></i> Add Certificate
                </a>
            </div>

            <div class="card-body">
                @if($certificates->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-award" style="font-size:64px; color:#e2e8f0;"></i>
                        <h5 class="mt-3 text-muted">No certificates yet</h5>
                        <p class="text-muted">Add your first certificate to display it on the About Us page.</p>
                        <a href="{{ route('certificates.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i> Add Certificate
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th width="120">Image</th>
                                    <th>Title</th>
                                    <th width="100">Status</th>
                                    <th width="140">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($certificates as $cert)
                                <tr>
                                    <td class="text-muted">{{ $cert->id }}</td>
                                    <td>
                                        @if($cert->image_path)
                                            <img src="{{ asset('uploads/' . $cert->image_path) }}"
                                                 alt="{{ $cert->title }}"
                                                 style="height:60px; width:90px; object-fit:contain; border-radius:8px; border:1px solid #e2e8f0; background:#f8fafc; padding:4px;">
                                        @else
                                            <span class="text-muted small">No image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $cert->title }}</strong>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input toggle-status" type="checkbox"
                                                   data-id="{{ $cert->id }}"
                                                   {{ $cert->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('certificates.edit', $cert) }}"
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <form action="{{ route('certificates.destroy', $cert) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this certificate?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-status').forEach(toggle => {
    toggle.addEventListener('change', function () {
        const id = this.dataset.id;
        fetch(`/certificates/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) this.checked = !this.checked;
        })
        .catch(() => { this.checked = !this.checked; });
    });
});
</script>
@endpush
