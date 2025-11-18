<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaypalService
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = config('paypal.client_id');
        $this->clientSecret = config('paypal.client_secret');
        $this->baseUrl = config('paypal.base_url'); // sandbox or live
    }

    // Get PayPal access token
    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post($this->baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->accessToken = $data['access_token'];
            return $this->accessToken;
        }

        throw new \Exception('Failed to get PayPal access token');
    }

    // Create PayPal payment
    public function createPayment($amount, $currency = 'USD', $description = '')
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v1/payments/payment', [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal'
            ],
            'transactions' => [
                [
                    'amount' => [
                        'total' => number_format($amount, 2, '.', ''),
                        'currency' => $currency
                    ],
                    'description' => $description
                ]
            ],
            'redirect_urls' => [
                'return_url' => 'patientmanagementsystem://paypal/success',
                'cancel_url' => 'patientmanagementsystem://paypal/cancel'
            ]
        ]);

        if ($response->successful()) {
            $payment = $response->json();

            // Find approval URL
            $approvalUrl = null;
            foreach ($payment['links'] as $link) {
                if ($link['rel'] === 'approval_url') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            return [
                'payment_id' => $payment['id'],
                'approval_url' => $approvalUrl,
                'status' => 'created'
            ];
        }

        // Log full error but return simplified message
        Log::error('PayPal API Error: ' . $response->body());

        $errorData = $response->json();
        $errorMessage = $errorData['message'] ?? 'Failed to create PayPal payment';

        throw new \Exception($errorMessage);
    }

    // Execute PayPal payment
    public function executePayment($paymentId, $payerId)
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . "/v1/payments/payment/{$paymentId}/execute", [
            'payer_id' => $payerId
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        // Log full error but return simplified message
        Log::error('PayPal Execute Payment Error: ' . $response->body());

        $errorData = $response->json();
        $errorMessage = $errorData['message'] ?? 'Failed to execute PayPal payment';

        throw new \Exception($errorMessage);
    }

    // Get payment details
    public function getPaymentDetails($paymentId)
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($this->baseUrl . "/v1/payments/payment/{$paymentId}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get payment details');
    }
}
