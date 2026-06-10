<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInventory;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data inventory',
            'data' => Inventory::all(),
        ]);
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
    public function store(CreateInventory $request)
    {
        try {
            $validatedData = $request->validated();
            $inventory = Inventory::create([
                'products_id' => $validatedData['products_id'],
                'quantity' => $validatedData['quantity'],
                'reserved_quantity' => 0,
                'locked_until' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inventory berhasil dibuat',
                'data' => $inventory,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat inventory: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data inventory',
                'data' => $inventory,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data inventory: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
