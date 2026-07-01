@extends('layouts.app')

@section('content')
<!-- sign-section -->
        <section class="sign-section pt_100 pb_100">
            <div class="auto-container">
                <div class="sec-title mb_50 centred">
                    <h2>Reset Password</h2>
                    <p>Create a new password for your account.</p>
                </div>
                <div class="form-inner">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <div class="password-input-group" style="position: relative;">
                                <input type="password" name="password" id="password" required>
                                <button type="button" id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #777; z-index: 10;">
                                    <i class="far fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="password-input-group" style="position: relative;">
                                <input type="password" name="password_confirmation" id="password_confirmation" required>
                                <button type="button" id="toggleConfirmPassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #777; z-index: 10;">
                                    <i class="far fa-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group message-btn">
                            <button type="submit" class="theme-btn">Reset Password<span></span><span></span><span></span><span></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- sign-section end -->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function setupToggle(buttonId, inputId, iconId) {
            $(`#${buttonId}`).click(function() {
                const field = $(`#${inputId}`);
                const icon = $(`#${iconId}`);
                const type = field.attr('type') === 'password' ? 'text' : 'password';
                field.attr('type', type);
                
                if (type === 'password') {
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
        }

        setupToggle('togglePassword', 'password', 'eyeIcon');
        setupToggle('toggleConfirmPassword', 'password_confirmation', 'eyeIconConfirm');
    });
</script>
@endpush
