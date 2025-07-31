<?php

namespace App\Http\Services\Api\Core;

use App\Http\Services\Api\Core\Interfaces\HttpClientServiceInterface;

class CurlHttpClientService implements HttpClientServiceInterface
{
    /**
 * Sends an HTTP request using cURL and returns the response.
 *
 * @param string $method  The HTTP method to use (e.g., 'GET', 'POST', 'PUT', 'DELETE').
 * @param string $url     The full URL to which the request should be sent.
 * @param array  $headers Optional associative array of headers to include in the request,
 *                        e.g., ['Authorization' => 'Bearer token', 'Content-Type' => 'application/json'].
 * @param array  $data    Optional associative array of request data.
 *                        For GET requests, this will be appended as query parameters.
 *                        For other methods, it will be sent as a JSON-encoded body.
 *
 * @return object An object containing:
 *                - int    $status: HTTP response status code.
 *                - mixed  $body:   Decoded JSON response (stdClass, array, or null).
 *                - string $raw:    Raw response body as a string.
 *
 * @throws \Exception If a cURL error occurs during the request.
 */
    public function request(string $method, string $url, array $headers = [], array $data = []): object
    {
        $ch = curl_init();

    $method = strtoupper($method);

    // Set the request URL (with query string if it's a GET request)
    curl_setopt($ch, CURLOPT_URL, $this->buildUrl($url, $method, $data));

    // Return the response as a string instead of outputting it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Set the HTTP request method (GET, POST, PUT, DELETE, etc.)
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    // Prepare and format headers
    $formattedHeaders = [];

    // Automatically add Content-Type: application/json if not already provided
    $hasContentType = false;
    foreach ($headers as $key => $value) {
        if (strtolower($key) === 'content-type') {
            $hasContentType = true;
        }
        $formattedHeaders[] = "$key: $value";
    }

    if (!in_array($method, ['GET']) && !$hasContentType) {
        $formattedHeaders[] = 'Content-Type: application/json';
    }

    if (!empty($formattedHeaders)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);
    }

    // If method is not GET, send data as JSON body
    if (!in_array($method, ['GET'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new \Exception("cURL Error: $error");
    }

    // Get the HTTP response status code
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($ch);

    // Return response data as an object
    return (object)[
        'status' => $statusCode,
        'body' => json_decode($response),
        'raw' => $response,
    ];
    
    }

    /**
     * Build the full URL with query parameters if it's a GET request.
     */
    protected function buildUrl(string $url, string $method, array $data): string
    {
        if (strtoupper($method) === 'GET' && !empty($data)) {
            $query = http_build_query($data);
            return $url . '?' . $query;
        }
        return $url;
    }
}