@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">User Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Molikule</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                    <h4 class="card-title">Users</h4>
                    <p class="card-title-desc">Manage system users and their permissions</p>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h4 class="card-title mb-0">Users</h4>
                            <p class="card-title-desc mb-0">
                                <small class="text-muted">
                                    Total: {{ $users->total() }} users
                                </small>
                            </p>
                        </div>
                        <div class="col-sm-6 text-end">
                            <div class="search-box d-inline-block" style="max-width: 300px;">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Search users...">
                                    <i class="bx bx-search-alt search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.usersData = @json($users);
        window.routes = {
            toggle: "{{ url('users') }}",
            delete: "{{ url('users') }}",
            view: "{{ url('users') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/UsersPage.js') }}"></script>
@endpush