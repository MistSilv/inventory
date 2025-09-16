<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TempCensusProduct;

class TempProductController extends Controller
{
    // Lista tymczasowych produktów dla zalogowanego użytkownika
    public function list()
    {
        $products = TempCensusProduct::where('user_id', Auth::id())->get();
        return response()->json($products);
    }

    // Dodanie lub aktualizacja tymczasowego produktu
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|numeric|min:0',
            'price'      => 'nullable|numeric',
            'name'       => 'required|string',
            'unit'       => 'nullable|string',
        ]);

        $product = TempCensusProduct::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id']
            ],
            [
                'quantity' => $validated['quantity'],
                'price'    => $validated['price'] ?? 0,
                'name'     => $validated['name'],
                'unit'     => $validated['unit'] ?? '',
            ]
        );

        return response()->json($product);
    }

    // Usunięcie tymczasowego produktu
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        TempCensusProduct::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json(['status' => 'removed']);
    }

    // Czyszczenie wszystkich tymczasowych produktów dla użytkownika
    public function clear()
    {
        TempCensusProduct::where('user_id', Auth::id())->delete();
        return response()->json(['status' => 'cleared']);
    }
}
