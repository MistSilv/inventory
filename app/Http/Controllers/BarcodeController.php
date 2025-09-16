<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barcode;

class BarcodeController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcodeValue = $request->input('barcode');

        // Find barcode and eager load product + unit
        $barcode = Barcode::where('barcode', $barcodeValue)
                          ->with('product.unit')
                          ->first();

        if (!$barcode || !$barcode->product) {
            return response()->json(['message' => 'Produkt nie znaleziony'], 404);
        }

        $product = $barcode->product;

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'unit' => $product->unit ? $product->unit->code : null,
            ]
        ]);
    }
}
