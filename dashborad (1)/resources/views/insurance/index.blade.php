@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="bx bx-shield me-2 text-primary"></i>Insurance Quotes
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Insurance Quotes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="card-title mb-0">Insurance Quotes</h4>
                        <p class="card-title-desc mb-0">
                            <small class="text-muted">
                                Total: {{ $insurances->total() }} quotes
                            </small>
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <!-- Search Box -->
                        <form method="GET" action="{{ route('insurance.index') }}" class="d-inline-block" style="max-width: 300px;">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="Search quotes..."
                                           value="{{ request('search') }}">
                                    <i class="bx bx-search-alt search-icon"></i>
                                </div>
                            </div>
                        </form>
                        @if(request('search'))
                            <a href="{{ route('insurance.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="bx bx-x"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Table View -->
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Quote #</th>
                                <th>Customer</th>
                                <th>Vehicle Details</th>
                                <th>Insurance Type</th>
                                <th>Premium</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($insurances as $insurance)
                            <tr>
                                <td>
                                    <a href="{{ route('insurance.show', $insurance) }}" class="text-primary fw-semibold">
                                        {{ $insurance->quote_number }}
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $insurance->customer_name }}</h6>
                                        <small class="text-muted">{{ $insurance->customer_email }}</small><br>
                                        @if($insurance->customer_phone)
                                            <small class="text-muted">{{ $insurance->customer_phone }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $insurance->formatted_vehicle }}</strong><br>
                                        <small class="text-muted">{{ $insurance->registration_number ?: 'Not Registered' }}</small><br>
                                        @if($insurance->year_manufacture)
                                            <small class="text-muted">Year: {{ $insurance->year_manufacture }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $insurance->insurance_type ?: 'Standard' }}</span>
                                    @if($insurance->coverage_type)
                                        <br><small class="text-muted">{{ $insurance->coverage_type }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($insurance->quoted_premium)
                                        <strong class="text-success">₹{{ number_format($insurance->quoted_premium, 2) }}</strong>
                                    @else
                                        <span class="text-muted">Not Quoted</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $insurance->created_at->format('d M, Y') }}</small><br>
                                    <small class="text-muted">{{ $insurance->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('insurance.show', $insurance) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="View Details">
                                            <i class="bx bx-show"></i> View
                                        </a>
                                        <a href="{{ route('insurance.print', $insurance) }}" 
                                           class="btn btn-sm btn-outline-success"
                                           title="Download PDF">
                                            <i class="bx bx-download"></i> PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bx bx-shield display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">No Insurance Quotes Found</h5>
                                        <p class="text-muted mb-4">
                                            @if(request('search'))
                                                No quotes match your search.
                                                <a href="{{ route('insurance.index') }}">Clear search</a> to see all quotes.
                                            @else
                                                No insurance quotes have been submitted yet.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($insurances) && is_object($insurances) && method_exists($insurances, 'hasPages') && $insurances->hasPages())
                <div class="d-flex flex-column align-items-center gap-3 mt-4">
                    <div class="w-100">
                        {{ $insurances->appends(request()->query())->links('components.pagination') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
