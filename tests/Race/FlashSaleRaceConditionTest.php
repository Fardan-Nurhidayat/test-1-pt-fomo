<?php

namespace Tests\Race;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

describe('Flash Sale Race Condition - Concurrent', function () {
    it('flash sale dengan stok cukup (6 concurrent requests)', function () {
        $product = createTestProduct(
            'Race Test - Sufficient Stock',
            150000.00,
        );
        $flashSale = createTestFlashSale($product['id'], 20, 'active');
        $numRequests = 6;
        $productId = $product['id'];
        $inventory = createInventory($productId, 10);
        $flashSaleId = $flashSale['id'];
        $promises = [];
        $client = apiClient();

        for ($i = 1; $i <= $numRequests; $i++) {
            $userId =  $i;

            $promise = createOrderAsync(
                $client,
                $userId,
                $productId,
                $flashSaleId,
                1
            );

            $promises[$userId] = $promise;
        }

        echo "Hasil Tes - Flash Sale dengan Stok Cukup (6 concurrent requests):\n";
        $results = Utils::settle($promises)->wait();

        $successCount = 0;
        $failedCount = 0;
        $totalQuantitySold = 0;

        foreach ($results as $userId => $result) {
            if ($result['state'] === 'fulfilled') {
                $data = $result['value'];

                if ($data['success'] === true) {
                    $statusCode = $data['statusCode'];

                    if ($statusCode === 201 || $statusCode === 200) {
                        $successCount++;
                        $totalQuantitySold += 1;

                        $totalPrice = $data['body']['data']['total_price'] ?? 'N/A';
                        $discountApplied = $data['body']['data']['discount_applied'] ?? 'N/A';

                        echo "- User $userId (ID: {$data['userId']}): SUCCESS\n";
                        echo "- Total Price: $totalPrice, Discount: $discountApplied\n";
                    } else {
                        $failedCount++;
                        $message = $data['body']['message'] ?? 'Unknown error';
                        echo "- User $userId: FAILED ($statusCode) - $message\n";
                    }
                } else {
                    $failedCount++;
                    echo "User $userId: FAILED - {$data['error']}\n";
                }
            } else {
                $failedCount++;
                echo "User $userId: ERROR - {$result['reason']}\n";
            }
        }

        $finalStock = getInventoryQuantity($inventory['id']);
        expect($successCount)->toBe(6);
        expect($totalQuantitySold)->toBe(6);
        expect($finalStock)->toBe(4); // 10 - 6 = 4
        expect($finalStock)->toBeGreaterThanOrEqual(0);
    });

    /**
     * TEST 2: Flash sale dengan stok tidak cukup (concurrent)
     * Expected: 5 requests, 3 sukses, 2 gagal (out of stock)
     */
    it('flash sale dengan stok tidak cukup (5 concurrent requests vs 3 stock)', function () {

        $product = createTestProduct(
            'Race Test - Insufficient Stock',
            150000.00,
        );
        $flashSale = createTestFlashSale($product['id'], 20, 'active');
        $numRequests = 5;
        $productId = $product['id'];
        $inventory = createInventory($productId, 3);
        $flashSaleId = $flashSale['id'];
        $promises = [];
        $client = apiClient();

        for ($i = 1; $i <= $numRequests; $i++) {
            $userId = $i;

            $promise = createOrderAsync(
                $client,
                $userId,
                $productId,
                $flashSaleId,
                1
            );

            $promises[$userId] = $promise;
        }

        echo "\nHasil Tes - Flash Sale dengan Stok Tidak Cukup (5 concurrent requests vs 3 stock):\n";
        $results = Utils::settle($promises)->wait();
        $successCount = 0;
        $failedCount = 0;
        $outOfStockCount = 0;


        foreach ($results as $userId => $result) {
            if ($result['state'] === 'fulfilled') {
                $data = $result['value'];

                if ($data['success'] === true) {
                    $statusCode = $data['statusCode'];

                    if ($statusCode === 201 || $statusCode === 200) {
                        $successCount++;
                        echo "- User $userId: SUCCESS\n";
                    } else {
                        $failedCount++;
                        $message = $data['body']['message'] ?? 'Unknown';

                        if (str_contains(strtolower($message), 'stock')) {
                            $outOfStockCount++;
                            echo "- User $userId: FAILED - $message (OUT OF STOCK)\n";
                        } else {
                            echo "- User $userId: FAILED - $message\n";
                        }
                    }
                } else {
                    $failedCount++;
                    echo "User $userId: FAILED - {$data['error']}\n";
                }
            } else {
                $failedCount++;
                echo "User $userId: ERROR\n";
            }
        }

        $finalStock = getInventoryQuantity($inventory['id']);

        expect($successCount)->toBe(3);
        expect($failedCount)->toBe(2);
        expect($finalStock)->toBe(0);
        expect($finalStock)->toBeGreaterThanOrEqual(0);
    });
});
