@extends('layouts.app')

@section('title', 'Contact Messages - Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Contact Messages</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Contacts</li>
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
                                <th scope="col">Name</th>
                                <th scope="col">Email & Phone</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Message</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $index => $msg)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><h5 class="font-size-14 mb-1">{{ $msg->username }}</h5></td>
                                <td>
                                    <p class="text-muted mb-0"><i class="bx bx-envelope ms-1"></i> {{ $msg->email }}</p>
                                    <p class="text-muted mb-0"><i class="bx bx-phone ms-1"></i> {{ $msg->phone }}</p>
                                </td>
                                <td>{{ $msg->subject }}</td>
                                <td>
                                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#messageModal{{ $msg->id }}">
                                        View Message
                                    </button>
                                </td>
                                <td>
                                    @if($msg->is_read)
                                        <span class="badge bg-success">Read</span>
                                    @else
                                        <span class="badge bg-danger">Unread</span>
                                    @endif
                                </td>
                                <td>
                                        <div class="d-flex gap-2">
                                            @if(!$msg->is_read)
                                                <button class="btn btn-sm btn-soft-primary mark-read-btn" data-id="{{ $msg->id }}">
                                                    <i class="bx bx-check-double me-1"></i>Mark Read
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-soft-warning mark-unread-btn" data-id="{{ $msg->id }}">
                                                    <i class="bx bx-envelope me-1"></i>Mark Unread
                                                </button>
                                            @endif
                                            {{-- <button class="btn btn-sm btn-soft-danger delete-btn" data-id="{{ $msg->id }}">Delete</button> --}}
                                        </div>
                                </td>
                            </tr>
                            
                            <!-- Message Modal -->
                            <div class="modal fade" id="messageModal{{ $msg->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Message from {{ $msg->username }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Subject:</strong> {{ $msg->subject }}</p>
                                            <hr>
                                            <p>{{ $msg->message }}</p>
                                            <hr>
                                            <small class="text-muted">Received at: {{ $msg->created_at->format('d M, Y H:i A') }}</small>
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
                                <td colspan="7" class="text-center">No messages found.</td>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.mark-read-btn').on('click', function() {
        let btn = $(this);
        let id = btn.data('id');
        $.post('/contacts/' + id + '/mark-read', function(res) {
            if(res.success) {
                location.reload();
            }
        });
    });

    $('.mark-unread-btn').on('click', function() {
        let btn = $(this);
        let id = btn.data('id');
        $.post('/contacts/' + id + '/mark-unread', function(res) {
            if(res.success) {
                location.reload();
            }
        });
    });

    $('.delete-btn').on('click', function() {
        let btn = $(this);
        let id = btn.data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "This message will be deleted permanently!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/contacts/' + id,
                    type: 'DELETE',
                    success: function(res) {
                        if(res.success) {
                            Swal.fire("Deleted!", "Message has been deleted.", "success").then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
