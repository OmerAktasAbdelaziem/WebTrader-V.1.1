<?php
namespace App\Http\Services\Api\Core\Interfaces;

interface HttpClientServiceInterface
{
    public function request(string $method, string $url, array $headers = [], array $data = []): object;
}