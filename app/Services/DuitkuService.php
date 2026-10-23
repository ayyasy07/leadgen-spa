<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected string $merchantCode;
    protected string $apiKey;
    protected bool $sandbox;
    protected array $endpoints;

    public function __construct()
    {
        $this->merchantCode = (string) config('duitku.merchant_code');
        $this->apiKey       = (string) config('duitku.api_key');
        $this->sandbox      = (bool) config('duitku.sandbox', true);

        $mode = $this->sandbox ? 'sandbox' : 'production';
        $this->endpoints = config("duitku.endpoints.{$mode}", []);
    }

    /**
     * Generate signature HMAC SHA256.
     */
    protected function generateSignature(string $stringToSign): string
    {
        return hash_hmac('sha256', $stringToSign, $this->apiKey);
    }

    /**
     * Get daftar metode pembayaran dari Duitku.
     */
    public function getPaymentMethods(int $amount): array
    {
        $datetime = now()->format('Y-m-d H:i:s');

        $stringToSign = $this->merchantCode . $amount . $datetime;
        $signature = $this->generateSignature($stringToSign);

        $payload = [
            'merchantcode' => $this->merchantCode,
            'amount'       => $amount,
            'datetime'     => $datetime,
            'signature'    => $signature,
        ];

        Log::info('[Duitku] getPaymentMethods REQUEST', [
            'endpoint'     => $this->endpoints['payment_method'] ?? null,
            'merchantcode' => $this->merchantCode,
            'amount'       => $amount,
            'datetime'     => $datetime,
            'signature'    => $signature,
        ]);

        try {
            $response = Http::timeout(20)
                ->asJson()
                ->post($this->endpoints['payment_method'], $payload);

            Log::info('[Duitku] getPaymentMethods RESPONSE', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();

            if (($data['responseCode'] ?? null) !== '00') {
                Log::warning('[Duitku] getPaymentMethods non-success', $data);
                return [];
            }

            return $data['paymentFee'] ?? [];
        } catch (\Throwable $e) {
            Log::error('[Duitku] getPaymentMethods exception', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Create transaksi di Duitku.
     */
    public function createTransaction(array $params): array
    {
        $merchantOrderId = (string) ($params['merchantOrderId'] ?? '');
        $paymentAmount   = (int) ($params['paymentAmount'] ?? 0);

        $stringToSign = $this->merchantCode . $merchantOrderId . $paymentAmount;
        $signature = $this->generateSignature($stringToSign);

        $payload = [
            'merchantCode'    => $this->merchantCode,
            'merchantOrderId' => $merchantOrderId,
            'paymentAmount'   => $paymentAmount,
            'paymentMethod'   => $params['paymentMethod'] ?? '',
            'productDetails'  => $params['productDetails'] ?? 'Spa Booking',
            'additionalParam' => $params['additionalParam'] ?? '',
            'merchantUserInfo'=> $params['merchantUserInfo'] ?? '',
            'customerVaName'  => $params['customerVaName'] ?? 'Customer',
            'email'           => $params['email'] ?? '',
            'phoneNumber'     => $params['phoneNumber'] ?? '',
            'itemDetails'     => $params['itemDetails'] ?? [],
            'customerDetail'  => [
                'firstName'   => $params['firstName'] ?? 'Customer',
                'lastName'    => $params['lastName'] ?? '',
                'email'       => $params['email'] ?? '',
                'phoneNumber' => $params['phoneNumber'] ?? '',
            ],
            'callbackUrl'     => $params['callbackUrl'] ?? config('duitku.callback_url'),
            'returnUrl'       => $params['returnUrl'] ?? config('duitku.return_url'),
            'signature'       => $signature,
            'expiryPeriod'    => (int) ($params['expiryPeriod'] ?? config('duitku.expiry_period', 60)),
        ];

        Log::info('[Duitku] createTransaction REQUEST', [
            'merchantOrderId' => $merchantOrderId,
            'paymentAmount'   => $paymentAmount,
            'stringToSign'    => $stringToSign,
            'signature'       => $signature,
        ]);

        try {
            $response = Http::timeout(20)
                ->asJson()
                ->post($this->endpoints['inquiry'], $payload);

            $data = $response->json();

            Log::info('[Duitku] createTransaction RESPONSE', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => $data['responseMessage'] ?? $data['Message'] ?? 'Gagal menghubungi Duitku.',
                ];
            }

            if (($data['statusCode'] ?? null) !== '00') {
                return [
                    'success' => false,
                    'message' => $data['statusMessage'] ?? 'Transaksi gagal dibuat.',
                ];
            }

            return [
                'success' => true,
                'data'    => [
                    'reference'     => $data['reference'] ?? null,
                    'paymentUrl'    => $data['paymentUrl'] ?? null,
                    'vaNumber'      => $data['vaNumber'] ?? null,
                    'amount'        => $data['amount'] ?? $paymentAmount,
                    'statusCode'    => $data['statusCode'] ?? null,
                    'statusMessage' => $data['statusMessage'] ?? null,
                ],
            ];
        } catch (\Throwable $e) {
            Log::error('[Duitku] createTransaction exception', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat transaksi.',
            ];
        }
    }

    /**
     * Verifikasi signature callback dari Duitku.
     * Formula: HMAC SHA256(merchantCode + amount + merchantOrderId)
     */
    public function verifyCallbackSignature(
        string $merchantCode,
        string $amount,
        string $merchantOrderId,
        string $signature
    ): bool {
        if ($merchantCode !== $this->merchantCode) {
            return false;
        }

        $stringToSign = $merchantCode . $amount . $merchantOrderId;
        $expected = $this->generateSignature($stringToSign);
        return hash_equals($expected, $signature);
    }

    /**
     * Cek status transaksi ke Duitku.
     * Formula: HMAC SHA256(merchantCode + merchantOrderId)
     */
    public function checkTransaction(string $merchantOrderId): array
    {
        $stringToSign = $this->merchantCode . $merchantOrderId;
        $signature = $this->generateSignature($stringToSign);

        $payload = [
            'merchantCode'    => $this->merchantCode,
            'merchantOrderId' => $merchantOrderId,
            'signature'       => $signature,
        ];

        try {
            $response = Http::timeout(20)
                ->asJson()
                ->post($this->endpoints['status'], $payload);
            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('[Duitku] checkTransaction exception', ['message' => $e->getMessage()]);
            return [];
        }
    }
}