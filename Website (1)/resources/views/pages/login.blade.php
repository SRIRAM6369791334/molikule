@extends('layouts.app')

@section('content')
<!-- sign-section -->
        <section class="sign-section pt_100 pb_100">
            <div class="auto-container">
                <div class="sec-title mb_50 centred">
                    <h2>Login to your account</h2>
                </div>
                <div class="form-inner">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        


                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="password-input-group" style="position: relative;">
                                <input type="password" name="password" id="password" required>
                                <button type="button" id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #777; z-index: 10;">
                                    <i class="far fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group message-btn">
                            <button type="submit" class="theme-btn">Log In<span></span><span></span><span></span><span></span></button>
                        </div>
                        <!-- <span class="text">or</span>
                        <ul class="social-links clearfix">
                            <li>
                                <a href="{{ route('login') }}"><img src="{{ asset('assets/images/icons/icon-3.png') }}" alt="">Continue with Google</a>
                            </li>
                            <li>
                                <a href="{{ route('login') }}"><img src="{{ asset('assets/images/icons/icon-4.png') }}" alt="">Continue with Facebook</a>
                            </li>
                        </ul> -->
                    </form>
                    <div class="other-option">
                        <div class="check-box">
                            <!-- <input class="check" type="checkbox" id="checkbox1" name="remember">
                            <label for="checkbox1">Remember me</label> -->
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                    </div>
                    <div class="lower-text centred"><p>Not registered yet? <a href="{{ route('register') }}">Create an Account</a></p></div>
                </div>
            </div>
        </section>
        <!-- sign-section end -->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#togglePassword').click(function() {
            const passwordField = $('#password');
            const eyeIcon = $('#eyeIcon');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            
            // Toggle icon class
            if (type === 'password') {
                eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });
</script>
@endpush
