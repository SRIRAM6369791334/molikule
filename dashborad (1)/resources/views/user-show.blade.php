@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    <i class="bx bx-user me-2 text-primary"></i>User Details
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Molikule</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
                        <li class="breadcrumb-item active">{{ $user->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('users') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>Back to Users List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Basic Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Full Name</small><br>
                            <strong class="fs-5">{{ $user->name }}</strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Email Address</small><br>
                            <strong>
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Phone Number</small><br>
                            <strong>
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Details -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-shield me-2"></i>Account Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <small class="text-muted">User Type</small><br>
                            @if($user->user_type === 'admin')
                                <span class="badge bg-danger fs-6">Admin</span>
                            @else
                                <span class="badge bg-primary fs-6">User</span>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <small class="text-muted">Account Status</small><br>
                            @if($user->status === 'active')
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-danger fs-6">Inactive</span>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <small class="text-muted">User ID</small><br>
                            <strong>#{{ $user->id }}</strong>
                        </div>
                        <div class="col-md-3 mb-3">
                            <small class="text-muted">Email Verified</small><br>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="bx bx-check-circle me-1"></i>Verified
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bx bx-info-circle me-1"></i>Not Verified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Timeline -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-calendar me-2"></i>Account Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Account Created</small><br>
                            <strong>{{ $user->created_at->format('d M, Y') }}</strong><br>
                            <small class="text-muted">{{ $user->created_at->format('h:i A') }}</small>
                        </div>
                        @if($user->email_verified_at)
                            <div class="col-md-4 mb-3">
                                <small class="text-muted">Email Verified</small><br>
                                <strong>{{ $user->email_verified_at->format('d M, Y') }}</strong><br>
                                <small class="text-muted">{{ $user->email_verified_at->format('h:i A') }}</small>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Last Updated</small><br>
                            <strong>{{ $user->updated_at->format('d M, Y') }}</strong><br>
                            <small class="text-muted">{{ $user->updated_at->format('h:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection