@extends('layouts.app')
@section('title') Product Creation Records | Audit Logs @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Reports @endslot
        @slot('title') Product Snapshots @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Historical Product Records</h4>
                    <p class="text-muted">These records capture the exact state of a product (Category, Brand, Variants) at the time of creation. This data remains even if the original product is deleted.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Variants Count</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $record->product_name }}</strong></td>
                                        <td>{{ $record->sku ?? 'N/A' }}</td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ $record->category_name }}</span></td>
                                        <td><span class="badge bg-soft-info text-info">{{ $record->brand_name }}</span></td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ count($record->variants_full_data) }} Variants
                                            </span>
                                        </td>
                                        <td>{{ $record->created_at->format('d M, Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewSnapshot({{ $record->id }})">
                                                <i class="bx bx-show me-1"></i> View All Values
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Snapshot Modal -->
    <div class="modal fade" id="snapshotModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complete Snapshot Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="snapshot-content">
                        {{-- Data will be injected here --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function viewSnapshot(id) {
        fetch(`/product-records/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="bg-light p-2 border-start border-primary border-4">Product Original Data</h6>
                            <pre class="bg-dark text-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">${JSON.stringify(data.product_full_data, null, 2)}</pre>
                        </div>
                        <div class="col-md-6">
                            <h6 class="bg-light p-2 border-start border-info border-4">Category Original Data</h6>
                            <pre class="bg-dark text-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">${JSON.stringify(data.category_full_data, null, 2)}</pre>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h6 class="bg-light p-2 border-start border-warning border-4">Brand Original Data</h6>
                            <pre class="bg-dark text-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">${JSON.stringify(data.brand_full_data, null, 2)}</pre>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h6 class="bg-light p-2 border-start border-success border-4">Variants Original Data</h6>
                            <pre class="bg-dark text-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">${JSON.stringify(data.variants_full_data, null, 2)}</pre>
                        </div>
                    </div>
                `;
                document.getElementById('snapshot-content').innerHTML = html;
                new bootstrap.Modal(document.getElementById('snapshotModal')).show();
            });
    }
</script>
@endpush
