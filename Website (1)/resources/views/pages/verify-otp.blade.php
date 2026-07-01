@extends('layouts.app')

@section('content')
<!-- sign-section -->
        <section class="sign-section pt_100 pb_100">
            <div class="auto-container">
                <div class="sec-title mb_50 centred">
                    <h2>Verify OTP</h2>
                    <p>Enter the 6-digit code sent to <strong>{{ $email }}</strong></p>
                </div>
                <div class="form-inner">
                    <form method="POST" action="{{ route('password.verify.post') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="form-group">
                            <label>OTP Code</label>
                            <input type="text" name="otp" maxlength="6" required oninput="this.value = this.value.replace(/[^0-9]/g, '');" style="text-align: center; font-size: 24px; letter-spacing: 10px;">
                        </div>
                        <div class="form-group message-btn">
                            <button type="submit" class="theme-btn">Verify OTP<span></span><span></span><span></span><span></span></button>
                        </div>
                    </form>
                    <div class="lower-text centred">
                        <p>Didn't receive code? 
                            <form action="{{ route('password.otp') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <button type="submit" style="background: none; border: none; color: var(--title-color); font-weight: 500; cursor: pointer; text-decoration: underline;">Resend OTP</button>
                            </form>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- sign-section end -->
@endsection
