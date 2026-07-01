@extends('layouts.app')

@section('content')
    <!-- sign-section -->
    <section class="sign-section pt_100 pb_100">
        <div class="auto-container">
            <div class="sec-title mb_50 centred">
                <h2>Forgot Password</h2>
                <p>Enter your email address to receive a password reset link.</p>
            </div>
            <div class="form-inner">
                <form method="POST" action="{{ route('password.otp') }}">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group message-btn">
                        <button type="submit" class="theme-btn">Send Reset
                            OTP<span></span><span></span><span></span><span></span></button>
                    </div>
                </form>
                <div class="lower-text centred">
                    <p>Remember your password? <a href="{{ route('login') }}">Login Here</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- sign-section end -->
@endsection