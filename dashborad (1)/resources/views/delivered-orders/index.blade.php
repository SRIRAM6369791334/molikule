@extends('layouts.app')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Delivered Orders</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Molikule</a></li>
                                <li class="breadcrumb-item active">Delivered Orders</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <div class="search-box me-2 mb-2 d-inline-block">
                                        <div class="position-relative">
                                            <input type="text" class="form-control" placeholder="Search...">
                                            <i class="bx bx-search-alt search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="text-sm-end">
                                        <button type="button"
                                            class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i
                                                class="mdi mdi-plus me-1"></i> Add New Order</button>
                                    </div>
                                </div><!-- end col-->
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-check">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 20px;" class="align-middle">
                                                <div class="form-check font-size-16">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                    <label class="form-check-label" for="checkAll"></label>
                                                </div>
                                            </th>
                                            <th class="align-middle">Order ID</th>
                                            <th class="align-middle">Billing Name</th>
                                            <th class="align-middle">Date</th>
                                            <th class="align-middle">Total</th>
                                            <th class="align-middle">Payment Status</th>
                                            <th class="align-middle">View Details</th>
                                            <th class="align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>
                                                    <div class="form-check font-size-16">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="orderidcheck{{$loop->index + 1}}">
                                                        <label class="form-check-label"
                                                            for="orderidcheck{{$loop->index + 1}}"></label>
                                                    </div>
                                                </td>
                                                <td><a href="javascript: void(0);"
                                                        class="text-body fw-bold">#{{ $order->order_number ?: $order->id }}</a>
                                                </td>
                                                <td>
                                                    {{ $order->customer_name }}
                                                    <div class="mt-1">
                                                        @if($order->user_id)
                                                            <span class="badge badge-pill bg-success-subtle text-success font-size-10"><i class="mdi mdi-account-check"></i> Registered</span>
                                                        @else
                                                            <span class="badge badge-pill bg-secondary-subtle text-secondary font-size-10"><i class="mdi mdi-account-outline"></i> Guest</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $order->created_at->format('d M, Y') }}
                                                </td>
                                                <td>
                                                    ₹{{ number_format($order->total_amount, 2) }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill bg-success-subtle text-success font-size-12">Paid</span>
                                                </td>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary btn-sm btn-rounded">
                                                        View Details
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <a href="javascript:void(0);" class="text-success"><i
                                                                class="mdi mdi-pencil font-size-18"></i></a>
                                                        <a href="javascript:void(0);" class="text-danger"><i
                                                                class="mdi mdi-delete font-size-18"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <i class="bx bx-check-circle display-4 text-muted"></i>
                                                    <h5 class="mt-3 text-muted">No delivered orders yet</h5>
                                                    <p class="text-muted">Completed deliveries will appear here.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination pagination-rounded justify-content-end mb-2">
                                <li class="page-item disabled">
                                    <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                        <i class="mdi mdi-chevron-left"></i>
                                    </a>
                                </li>
                                @php $currentPage = $orders->currentPage();
                                $lastPage = $orders->lastPage(); @endphp
                                @for($page = max(1, $currentPage - 2); $page <= min($lastPage, $currentPage + 2); $page++)
                                    <li class="page-item{{ $page == $currentPage ? ' active' : '' }}">
                                        <a class="page-link" href="{{ $orders->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endfor
                                <li class="page-item{{ $currentPage == $lastPage ? ' disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ $currentPage < $lastPage ? $orders->url($currentPage + 1) : 'javascript: void(0);' }}"
                                        aria-label="Next">
                                        <i class="mdi mdi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
@endsection