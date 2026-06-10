<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Helper\GenerateOrderNumber;
use App\Http\Requests\CreateOrder;
use App\Models\FlashSale;
use App\Models\Inventory;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrder $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();

            $orderNumber = GenerateOrderNumber::generate();
            $order = Orders::create([
                'order_number' => $orderNumber,
                'user_id' => $validatedData['user_id'],
                'flash_sale_id' => $validatedData['flash_sale_id'] ?? null,
                'notes' => $validatedData['notes'] ?? null,
            ]);

            foreach ($validatedData['list_of_products'] as $item) {
                $inventory = Inventory::where('products_id', $item['products_id'])->lockForUpdate()->first();
                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception('Stok tidak cukup untuk produk ID: ' . $item['products_id']);
                }

                $inventory->quantity -= $item['quantity'];
                $inventory->reserved_quantity += $item['quantity'];
                if ($validatedData['flash_sale_id']) {
                    $flashSale = FlashSale::find($validatedData['flash_sale_id']);
                    if ($flashSale && $flashSale->products_id == $item['products_id'] && $flashSale->status == 'active') {
                        $unitPrice = Products::find($item['products_id'])->base_price * (1 - $flashSale->discount_value / 100);
                        $subTotal = $unitPrice * $item['quantity'];
                        $discountAmount = Products::find($item['products_id'])->base_price * ($flashSale->discount_value / 100) * $item['quantity'];
                        $discountPercentage = $flashSale->discount_value;
                    } else {
                        $unitPrice = Products::find($item['products_id'])->base_price;
                    }
                } else {
                    $unitPrice = Products::find($item['products_id'])->base_price;
                }
                $order->orderItems()->create([
                    'products_id' => $item['products_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subTotal ?? ($unitPrice * $item['quantity']),
                    'discount_amount' => $discountAmount ?? 0,
                    'discount_percentage' => $discountPercentage ?? 0,
                    'is_flash_sale_item' => isset($flashSale) && $flashSale->status == 'active' ? true : false,
                ]);

                $order->update([
                    'total_price' => $order->orderItems()->sum('subtotal'),
                    'discount_applied' => $order->orderItems()->sum('discount_amount'),
                ]);
                $order->status = OrderStatus::PAID->value;
                $order->save();
                $inventory->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Order berhasil dibuat',
                    'data' => $order->load('orderItems'),
                ], 201);
            };
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orders $orders)
    {
        //
    }
}
