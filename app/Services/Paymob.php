<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Paymob
{
    /**
     * API key to get the access token
     */
    protected string $apiKey;

    /**
     * Integration ID for online cards payment
     */
    protected string $integrationId;

    /**
     * The main endpoint for Paymob Acceptance API
     */
    protected string $apiUrl = 'https://accept.paymob.com/api/';

    /**
     * Create a new instance of Paymob
     */
    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->integrationId = config('services.paymob.integration_id');
    }

    /**
     * Get the Paymob client
     */
    public function client(bool $authenticated = true): PendingRequest
    {
        $client = Http::baseUrl($this->apiUrl);

        if ($authenticated) {
            $client->withToken($this->token());
        }

        return $client;
    }

    /**
     * Get the access token
     */
    public function token($fresh = false): string
    {
        $duration = 10 * 60; // 10 minutes

        $fetchToken = function () {
            $response = $this->client(authenticated: false)->post('auth/tokens', [
                'api_key' => $this->apiKey,
            ])->json();

            return $response['token'] ?? null;
        };

        if ($fresh) {
            return $fetchToken();
        }

        return cache()->remember('paymob_access_token', $duration, $fetchToken);
    }

    /**
     * Create a new subscription plan
     */
    public function createPlan(
        string $name,
        int $price,
        string $webhookUrl,
        int $frequency = 30,
        int $reminderDays = 3,
        int $retrialDays = 3,
        string $planType = 'rent',
        bool $useTransactionAmount = false,
        bool $isActive = true,
    ): array {
        $payload = [
            'frequency' => $frequency,
            'name' => $name,
            'reminder_days' => $reminderDays,
            'retrial_days' => $retrialDays,
            'plan_type' => $planType,
            'amount_cents' => $price,
            'use_transaction_amount' => $useTransactionAmount,
            'is_active' => $isActive,
            'integration' => $this->integrationId,
            'webhook_url' => $webhookUrl,
        ];

        return $this->client()->post('acceptance/subscription-plans', $payload)->json();
    }

    /**
     * Update a subscription plan
     */
    public function updatePlan(int $id, float $price): array
    {
        $payload = [
            'amount_cents' => $price,
        ];

        return $this->client()->put("acceptance/subscription-plans/$id", $payload)->json();
    }

    /**
     * Suspend a subscription plan
     */
    public function suspendPlan(int $id): array
    {
        return $this->client()->post("acceptance/subscription-plans/$id/suspend")->json();
    }

    /**
     * Activate a subscription plan
     */
    public function resumePlan(int $id): array
    {
        return $this->client()->post("acceptance/subscription-plans/$id/resume")->json();
    }

    /**
     * List all subscription plans
     */
    public function listPlans(): array
    {
        return $this->client()->get('acceptance/subscription-plans')->json();
    }
}
