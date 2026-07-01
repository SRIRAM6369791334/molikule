@extends('layouts.app')

@section('title', 'Job Applications - Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Job Applications</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Job Applications</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Applicant Info</th>
                                <th scope="col">Position Applied</th>
                                <th scope="col">Details</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $index => $app)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <h5 class="font-size-14 mb-1">{{ $app->first_name }} {{ $app->last_name }}</h5>
                                    <p class="text-muted mb-0"><i class="bx bx-envelope ms-1"></i> {{ $app->email }}</p>
                                    @if($app->phone)
                                        <p class="text-muted mb-0"><i class="bx bx-phone ms-1"></i> {{ $app->phone }}</p>
                                    @endif
                                </td>
                                <td><span class="badge bg-primary">{{ $app->position }}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#appModal{{ $app->id }}">
                                        View Details
                                    </button>
                                </td>
                                <td>
                                    @if($app->is_read)
                                        <span class="badge bg-success">Reviewed</span>
                                    @else
                                        <span class="badge bg-danger">New</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if(!$app->is_read)
                                            <form action="{{ route('job-applications.mark-read', $app->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-soft-primary">
                                                    <i class="bx bx-check-double me-1"></i>Mark Read
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('job-applications.mark-unread', $app->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-soft-warning">
                                                    <i class="bx bx-envelope me-1"></i>Mark Unread
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('job-applications.destroy', $app->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-soft-danger delete-btn">
                                                <i class="bx bx-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Application Modal -->
                            <div class="modal fade" id="appModal{{ $app->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Application: {{ $app->first_name }} {{ $app->last_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <p><strong>Position:</strong> {{ $app->position }}</p>
                                                    <p><strong>Email:</strong> <a href="mailto:{{ $app->email }}">{{ $app->email }}</a></p>
                                                    <p><strong>Phone:</strong> {{ $app->phone ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-md-6 text-md-end">
                                                     <a href="{{ rtrim(env('MAIN_URL'), '/') }}/uploads/resumes/{{ $app->resume_path }}" target="_blank" download class="btn btn-primary">
                                                        <i class="bx bx-download me-1"></i> Download Resume
                                                    </a>
                                                </div>
                                            </div>
                                            <hr>
                                            <h6>Cover Letter / Note</h6>
                                            @if($app->cover_letter)
                                                <div class="bg-light p-3 rounded">
                                                    {!! nl2br(e($app->cover_letter)) !!}
                                                </div>
                                            @else
                                                <p class="text-muted">No cover letter provided.</p>
                                            @endif
                                            <hr>
                                            <small class="text-muted">Applied at: {{ $app->created_at->format('d M, Y H:i A') }}</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No job applications found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-btn').on('click', function() {
        let form = $(this).closest('form');
        Swal.fire({
            title: "Are you sure?",
            text: "This application and the resume file will be deleted permanently!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
