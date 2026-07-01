<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $maxRetries;

    public function __construct()
    {
        $this->baseUrl = config('services.admin_dashboard.url', env('ADMIN_DASHBOARD_URL', 'http://127.0.0.1:8001'));
        $this->timeout = config('services.admin_dashboard.timeout', env('ADMIN_API_TIMEOUT', 30));
        $this->maxRetries = config('services.admin_dashboard.retries', env('ADMIN_API_RETRIES', 3));
    }

    /**
     * Get the base URL for the admin dashboard
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Trigger order email notifications via admin dashboard API
     */
    public function triggerOrderEmails($orderId, $source = 'frontend')
    {
        $endpoint = '/api/notifications/send-order-emails';
        $url = $this->baseUrl . $endpoint;

        $payload = [
            'order_id' => $orderId,
            'source' => $source
        ];

        Log::info('Triggering order emails via admin API', [
            'order_id' => $orderId,
            'url' => $url,
            'source' => $source
        ]);

        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            try {
                $response = Http::timeout($this->timeout)
                    ->post($url, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Admin API call successful', [
                        'order_id' => $orderId,
                        'response' => $data
                    ]);
                    return $data;
                } else {
                    Log::warning('Admin API call failed', [
                        'order_id' => $orderId,
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'attempt' => $attempt + 1
                    ]);
                }
            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning('Admin API call exception', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage(),
                    'attempt' => $attempt + 1
                ]);
            }

            $attempt++;
            if ($attempt < $this->maxRetries) {
                // Exponential backoff: wait 1, 2, 4 seconds...
                sleep(pow(2, $attempt - 1));
            }
        }

        // All retries failed
        Log::error('Admin API call failed after all retries', [
            'order_id' => $orderId,
            'max_retries' => $this->maxRetries,
            'last_error' => $lastException ? $lastException->getMessage() : 'Unknown error'
        ]);

        return [
            'success' => false,
            'message' => 'Failed to send email notifications after ' . $this->maxRetries . ' attempts',
            'error' => $lastException ? $lastException->getMessage() : 'Unknown error'
        ];
    }

    /**
     * Test connection to admin dashboard
     */
    public function testConnection()
    {
        try {
            // Try the API endpoint we created
            $response = Http::timeout(10)->get($this->baseUrl . '/api/user');

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'url' => $this->baseUrl
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'url' => $this->baseUrl
            ];
        }
    }
}
