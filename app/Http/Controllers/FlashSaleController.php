<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFlashSale;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashSales = FlashSale::all();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data flash sale',
            'data' => $flashSales
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
    public function store(CreateFlashSale $request)
    {
        try {
            $validatedData = $request->validated();
            $flashSale = FlashSale::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Flash sale berhasil dibuat',
                'data' => $flashSale,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat flash sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FlashSale $flashSale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FlashSale $flashSale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FlashSale $flashSale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlashSale $flashSale)
    {
        //
    }
}
