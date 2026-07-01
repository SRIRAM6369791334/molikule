@extends('layouts.app')
@section('title', 'Banners | Home Page')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Content @endslot
    @slot('title') Banners @endslot
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
                    <h4 class="card-title mb-1">Banners</h4>
                    <p class="text-muted mb-0">Showing {{ $bentoCards->count() }} card(s) — displayed on the Home Page.</p>
                </div>
                <a href="{{ route('bento-cards.create') }}" class="btn btn-primary px-4">
                    <i class="mdi mdi-plus me-1"></i> Add Bento Card
                </a>
            </div>

            <div class="card-body">
                @if($bentoCards->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-layout" style="font-size:64px; color:#e2e8f0;"></i>
                        <h5 class="mt-3 text-muted">No Banners yet</h5>
                        <p class="text-muted">Add your first bento card to display it on the Home page.</p>
                        <a href="{{ route('bento-cards.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i> Add Bento Card
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                    <th width="60">S.no</th>
                                    <th width="120">Image</th>
                                    <th width="100">Status</th>
                                    <th width="140">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bentoCards as $card)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($card->image_path)
                                            <img src="{{ asset('uploads/bento_cards/' . $card->image_path) }}"
                                                 alt="Banner"
                                                 style="height:60px; width:90px; object-fit:contain; border-radius:8px; border:1px solid #e2e8f0; background:#f8fafc; padding:4px;">
                                        @else
                                            <span class="text-muted small">No image</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($card->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('bento-cards.edit', $card) }}"
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <form action="{{ route('bento-cards.destroy', $card) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this card?')">
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
