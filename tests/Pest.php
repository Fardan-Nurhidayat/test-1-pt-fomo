<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function apiClient(): Client
{
    $baseUrl = getenv('API_BASE_URL') ?: 'http://localhost:8000';

    // Verify API is running
    try {
        $testClient = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 5,
            'connect_timeout' => 2,
            'http_errors' => false,
        ]);

        $response = $testClient->get('/api/v1/products');

        if ($response->getStatusCode() === 404) {
            throw new Exception(
                "API endpoints not found. Ensure API is running at: $baseUrl\n" .
                    "Try: php artisan serve (if using Laravel)\n" .
                    "Or: php -S localhost:8000 (if using plain PHP)"
            );
        }
    } catch (RequestException $e) {
        throw new Exception(
            "Cannot connect to API at: $baseUrl\n" .
                "Error: " . $e->getMessage() . "\n" .
                "Make sure API server is running!"
        );
    }

    return new Client([
        'base_uri' => $baseUrl,
        'timeout' => 30,
        'connect_timeout' => 5,
        'http_errors' => false,
    ]);
}

function createTestProduct(
    string $name = 'Test Product',
    float $price = 100000.00,
): array {
    try {
        $client = apiClient();
        $productResponse = $client->post('/api/v1/products', [
            'json' => [
                'name' => $name,
                'description' => 'Test product for race condition',
                'base_price' => $price,
                'status' => 'active',
            ],
            'timeout' => 10,
            'connect_timeout' => 5,
        ]);

        $statusCode = $productResponse->getStatusCode();
        $body = json_decode($productResponse->getBody(), true);

        if ($statusCode !== 201 && $statusCode !== 200) {
            throw new Exception(
                "Failed to create product. Status: $statusCode\n" .
                    "Response: " . json_encode($body)
            );
        }

        $product = $body['data'] ?? $body;
        $productId = $product['id'] ?? null;

        if (!$productId) {
            throw new Exception('No product ID returned: ' . json_encode($body));
        }
        return $product;
    } catch (RequestException $e) {
        throw new Exception(
            "API Request Error: " . $e->getMessage() . "\n" .
                "Make sure API is running at: " .
                (getenv('API_BASE_URL') ?: 'http://localhost:8000')
        );
    } catch (Exception $e) {
        throw $e;
    }
}

function createInventory(int $productId, int $quantity)
{
    try {
        $client = apiClient();
        $response = $client->post('/api/v1/inventory', [
            'json' => [
                'products_id' => $productId,
                'quantity' => $quantity,
            ],
            'timeout' => 10,
            'connect_timeout' => 5,
        ]);

        if ($response->getStatusCode() > 299) {
            throw new Exception(
                "Failed to create inventory: " . $response->getBody()
            );
        }

        $data = json_decode($response->getBody(), true);
        return $data['data'] ?? $data;
    } catch (RequestException $e) {
        throw new Exception(
            "API Request Error: " . $e->getMessage() . "\n" .
                "Make sure API is running at: " .
                (getenv('API_BASE_URL') ?: 'http://localhost:8000')
        );
    } catch (Exception $e) {
        throw $e;
    }
}

function createTestFlashSale(
    int $productId,
    int $discountValue = 20,
    string $status = 'active'
): array {
    $client = apiClient();

    $response = $client->post('/api/v1/flash-sales', [
        'json' => [
            'products_id' => $productId,
            'title' => 'Test Flash Sale - ' . uniqid(),
            'description' => 'Test flash sale for race condition',
            'discount_type' => 'percentage',
            'discount_value' => $discountValue,
            'status' => $status,
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s', strtotime('+2 hours')),
        ],
        'timeout' => 10,
    ]);

    $statusCode = $response->getStatusCode();
    $body = json_decode($response->getBody(), true);

    if ($statusCode !== 201 && $statusCode !== 200) {
        throw new Exception(
            "Failed to create flash sale: " . json_encode($body)
        );
    }

    return $body['data'] ?? $body;
}

function createOrderAsync(
    Client $client,
    int $userId,
    int $productId,
    ?int $flashSaleId = null,
    int $quantity = 1
): \GuzzleHttp\Promise\PromiseInterface {
    return $client->postAsync('/api/v1/orders', [
        'json' => [
            'user_id' => $userId,
            'list_of_products' => [
                [
                    'products_id' => $productId,
                    'quantity' => $quantity,
                ],
            ],
            'flash_sale_id' => $flashSaleId,
        ],
        'timeout' => 30,
        'connect_timeout' => 5,
    ])
        ->then(
            function ($response) use ($userId) {
                // Handle success
                return [
                    'userId' => $userId,
                    'statusCode' => $response->getStatusCode(),
                    'body' => json_decode($response->getBody(), true),
                    'success' => true,
                ];
            },
            function ($reason) use ($userId) {
                // Handle error
                return [
                    'userId' => $userId,
                    'success' => false,
                    'error' => $reason->getMessage(),
                ];
            }
        );
}

function getInventoryQuantity(int $productId): int
{
    try {
        $client = apiClient();
        $response = $client->get("/api/v1/inventory/{$productId}");

        if ($response->getStatusCode() !== 200) {
            throw new Exception(
                "Failed to get inventory: " . $response->getStatusCode()
            );
        }

        $data = json_decode($response->getBody(), true);
        return $data['data']['quantity'] ?? 0;
    } catch (Exception $e) {
        throw new Exception("Error getting inventory: " . $e->getMessage());
    }
}
