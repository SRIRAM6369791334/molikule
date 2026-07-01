<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function testEmail()
    {
        try {
            // Clear the log file first
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                file_put_contents($logFile, '');
            }

            $email = 'ss9819690@gmail.com'; // Use the same email as in .env
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email address'
                ]);
            }

            // Test sending email
            Mail::to($email)->send(new TestEmail('This is a test email from the order system.'));

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully! Check your email and the log file.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email test failed: ' . $e->getMessage(),
                'error' => $e
            ]);
        }
    }
}