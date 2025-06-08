<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreSmsServices
{
    protected $apiKey;
    protected $senderName;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.semaphore.api_key');
        $this->senderName = config('services.semaphore.sender_name');
        $this->baseUrl = config('services.semaphore.base_url');
    }

    /**
     * Send SMS to single recipient
     */
    public function sendSms($number, $message)
    {
        try {
            $response = Http::post($this->baseUrl . 'messages', [
                'apikey' => $this->apiKey,
                'number' => $this->formatPhoneNumber($number),
                'message' => $message,
                'sendername' => $this->senderName
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('SMS sent successfully', [
                    'message_id' => $data[0]['message_id'] ?? null,
                    'number' => $number,
                    'status' => $data[0]['status'] ?? null
                ]);

                return [
                    'success' => true,
                    'message_id' => $data[0]['message_id'] ?? null,
                    'status' => $data[0]['status'] ?? null,
                    'data' => $data
                ];
            }

            Log::error('SMS sending failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send SMS',
                'response' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'error' => $e->getMessage(),
                'number' => $number
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS to multiple recipients
     */
    public function sendBulkSms($numbers, $message)
    {
        try {
            $formattedNumbers = array_map([$this, 'formatPhoneNumber'], $numbers);
            $numbersString = implode(',', $formattedNumbers);

            $response = Http::post($this->baseUrl . 'messages', [
                'apikey' => $this->apiKey,
                'number' => $numbersString,
                'message' => $message,
                'sendername' => $this->senderName
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Bulk SMS sent successfully', [
                    'count' => count($numbers),
                    'data' => $data
                ]);

                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to send bulk SMS',
                'response' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Bulk SMS sending exception', [
                'error' => $e->getMessage(),
                'numbers' => $numbers
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send OTP SMS
     */
    public function sendOtp($number, $code)
    {
        $message = "Your verification code is: {$code}. Do not share this code with anyone.";
        return $this->sendSms($number, $message);
    }

    /**
     * Check account balance
     */
    public function getBalance()
    {
        try {
            $response = Http::get($this->baseUrl . 'account', [
                'apikey' => $this->apiKey
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'error' => 'Failed to get balance'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get message status
     */
    public function getMessageStatus($messageId)
    {
        try {
            $response = Http::get($this->baseUrl . 'messages/' . $messageId, [
                'apikey' => $this->apiKey
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'error' => 'Failed to get message status'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to proper format
     */
    private function formatPhoneNumber($number)
    {
        // Remove any non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Convert to international format for Philippines
        if (substr($number, 0, 1) === '0') {
            $number = '63' . substr($number, 1);
        } elseif (substr($number, 0, 2) !== '63') {
            $number = '63' . $number;
        }

        return $number;
    }
}
