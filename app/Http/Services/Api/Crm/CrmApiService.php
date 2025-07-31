<?php
namespace App\Http\Services\Api\Crm;

use App\Http\Services\Api\Crm\Interfaces;

class CrmApiService implements Interfaces\CrmApiServiceInterface
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.crm.url');
        $this->apiKey = config('services.crm.key');
    }

    public function getFinancialData(int $brokerId): array
    {
        $url = $this->baseUrl . "/api/getFinancialData?broker_id=" . $brokerId;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "X-API-KEY: {$this->apiKey}"
            ],
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            throw new \Exception('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        return $data['finance'] ?? [];
    }
}