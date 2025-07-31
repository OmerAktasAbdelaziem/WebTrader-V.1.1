<?php
namespace App\Http\Services\Api\Crm;

use App\Http\Services\Api\Crm\Interfaces\CrmApiServiceInterface;

use App\Http\Services\Api\Core\HttpClientServiceInterface;

class CrmApiService implements CrmApiServiceInterface
{
    protected HttpClientServiceInterface $httpClientService;
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct(HttpClientServiceInterface $httpClientService)
    {
        $this->httpClientService = $httpClientService;
        $this->baseUrl = config('services.crm_api.url');
        $this->apiKey = config('services.crm_api.key');
    }

    public function getFinancialData(int $brokerId): array
    {
        $url = $this->baseUrl . '/api/getFinancialData';

        $headers = [
            'X-API-KEY' => $this->apiKey,
            'Accept'    => 'application/json',
        ];

        $response = $this->httpClientService->request('GET', $url, $headers, [
            'broker_id' => $brokerId,
        ]);

        return $response->body->finance ?? [];
    }
}