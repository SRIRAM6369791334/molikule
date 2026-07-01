@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Blogs</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Molikule</a></li>
                        <li class="breadcrumb-item active">Blogs</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title">Manage Blogs</h4>
                        <p class="card-title-desc mb-0">Create and edit blog posts for the molikule website.</p>
                    </div>
                    <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus-circle me-1"></i>Add New Blog
                    </a>
                </div>
                <div class="card-body">
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.blogsData = @json($blogs);
        window.routes = {
            toggle: "{{ url('blogs') }}",
            delete: "{{ url('blogs') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/BlogsPage.js') }}"></script>
@endpush
