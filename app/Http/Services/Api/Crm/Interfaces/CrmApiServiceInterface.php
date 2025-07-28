<?php
namespace App\Http\Services\Api\Crm\Interfaces;
interface CrmApiServiceInterface{
    public function getFinancialData(int $brokerId): array;
}