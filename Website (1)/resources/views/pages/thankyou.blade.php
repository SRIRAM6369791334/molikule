@extends('layouts.app')

@section('content')
<!-- page-title -->
<section class="page-title centred">
    <div class="auto-container">
        <div class="content-box">
            <h1>Order Success</h1>
        </div>
    </div>
</section>
<!-- page-title end -->

<!-- thank-you-section -->
<section class="thank-you-section pt_100 pb_100 centred">
    <div class="auto-container">
        <div class="row clearfix justify-content-center">
            <div class="col-lg-8 col-md-12 col-sm-12">
                <div class="inner-box border shadow-lg p-5 rounded-4 bg-white">
                    <div class="icon-box mb_30">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <div class="content_block_2">
                        <div class="content-box">
                            <h2 class="mb_20">Thank You For Your Purchase!</h2>
                            <p class="mb_30 fs-5 text-muted">Your order has been received and is now being processed. An email confirmation has been sent to your registered email address.</p>
                            
                            <div class="order-number-box bg-light p-4 rounded-3 mb_40 border">
                                <h4 class="mb_10">Order Number</h4>
                                <h3 class="text-primary font-weight-bold">{{ $orderNumber }}</h3>
                            </div>

                            <div class="btn-box">
                                <a href="{{ route('shop') }}" class="theme-btn btn-one me-3">Continue Shopping<span></span><span></span><span></span><span></span></a>
                                <a href="{{ route('home') }}" class="theme-btn btn-two">Back to Home<span></span><span></span><span></span><span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- thank-you-section end -->

<style>
    .rounded-4 { border-radius: 1.5rem !important; }
    .btn-two { background: #111; color: #fff; }
    .btn-two:hover { background: #000; }
</style>
@endsection
