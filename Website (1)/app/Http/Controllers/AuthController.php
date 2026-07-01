<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('my-account');
        }
        return view('pages.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('my-account'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration page.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('my-account');
        }
        return view('pages.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Send Welcome Email
        try {
            Mail::send('emails.welcome', ['user' => $user], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Welcome to Molikule - Green Care for a Better Tomorrow');
            });
        } catch (\Exception $e) {
            Log::error("Welcome email failed for {$user->email}: " . $e->getMessage());
        }

        return redirect()->route('my-account')->with('success', 'Account created successfully!');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('pages.forgot-password');
    }

    /**
     * Send OTP to user's email.
     */
    public function sendOTP(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $otp, // Storing OTP in the token column
                'created_at' => now()
            ]
        );

        // Send the email with OTP
        try {
            Mail::send('emails.otp', ['otp' => $otp], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Your Password Reset OTP - Molikule Green Care');
            });
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email to {$request->email}: " . $e->getMessage());
        }

        return redirect()->route('password.verify.show', ['email' => $request->email])
                         ->with('success', 'A 6-digit OTP has been sent to your email.');
    }

    /**
     * Show OTP verification form.
     */
    public function showVerifyOTP(Request $request)
    {
        $email = $request->email;
        if (!$email) {
            return redirect()->route('password.request');
        }
        return view('pages.verify-otp', compact('email'));
    }

    /**
     * Verify OTP.
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$reset || now()->diffInMinutes($reset->created_at) > 15) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        return redirect()->route('password.reset', ['otp' => $request->otp, 'email' => $request->email]);
    }

    /**
     * Show reset password form.
     */
    public function showResetForm(Request $request, $otp, $email)
    {
        return view('pages.reset-password', ['token' => $otp, 'email' => $email]);
    }

    /**
     * Reset the password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid session. Please request a new OTP.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now login.');
    }
}
