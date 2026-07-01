@extends('layouts.app')

@section('title', 'NEXUS Enquiries - Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">NEXUS Enquiries</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">NEXUS Enquiries</li>
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
                                <th scope="col">Contact Info</th>
                                <th scope="col">Company</th>
                                <th scope="col">Segment</th>
                                <th scope="col">Received</th>
                                <th scope="col">Details</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enquiries as $index => $enquiry)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <h5 class="font-size-14 mb-1">{{ $enquiry->name }}</h5>
                                        <p class="text-muted mb-0"><i class="bx bx-envelope ms-1"></i> {{ $enquiry->email }}</p>
                                        <p class="text-muted mb-0"><i class="bx bx-phone ms-1"></i> {{ $enquiry->contact_no }}</p>
                                    </td>
                                    <td>{{ $enquiry->company_name }}</td>
                                    <td><span class="badge bg-primary">{{ $enquiry->segment }}</span></td>
                                    <td>{{ $enquiry->created_at->format('d M, Y H:i A') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#nexusModal{{ $enquiry->id }}">
                                            View Details
                                        </button>
                                    </td>
                                    <td>
                                        @if($enquiry->is_read)
                                            <span class="badge bg-success">Read</span>
                                        @else
                                            <span class="badge bg-danger">New</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if(!$enquiry->is_read)
                                                <form action="{{ route('nexus-certifications.mark-read', $enquiry->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-soft-primary">
                                                        <i class="bx bx-check-double me-1"></i>Mark Read
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('nexus-certifications.mark-unread', $enquiry->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-soft-warning">
                                                        <i class="bx bx-envelope me-1"></i>Mark Unread
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('nexus-certifications.destroy', $enquiry->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-soft-danger delete-btn">
                                                    <i class="bx bx-trash me-1"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="nexusModal{{ $enquiry->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">NEXUS Enquiry: {{ $enquiry->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <p><strong>Name:</strong> {{ $enquiry->name }}</p>
                                                        <p><strong>Email:</strong> <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></p>
                                                        <p><strong>Contact No.:</strong> {{ $enquiry->contact_no }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Company:</strong> {{ $enquiry->company_name }}</p>
                                                        <p><strong>Segment:</strong> {{ $enquiry->segment }}</p>
                                                        <p><strong>Received:</strong> {{ $enquiry->created_at->format('d M, Y H:i A') }}</p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h6>Describe Your Thoughts</h6>
                                                <div class="bg-light p-3 rounded">
                                                    {!! nl2br(e($enquiry->thoughts)) !!}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No NEXUS enquiries found.</td>
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
            text: "This NEXUS enquiry will be deleted permanently!",
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
