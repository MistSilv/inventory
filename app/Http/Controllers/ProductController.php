<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Barcode;

class ProductController extends Controller
{
    public function create()
    {
        $units = Unit::all();
        return view('products.create', compact('units'));
    }

    public function store(Request $request)
    {

        
        $validated = $request->validate([
            'id_abaco' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'unit_id' => 'nullable|exists:units,id',
            'barcodes.*' => 'nullable|string|max:13',
        ]);

        //dd($validated['barcodes']);

        // Create the product
        $product = Product::create([
            'id_abaco' => $validated['id_abaco'] ?? null,
            'name' => $validated['name'],
            'price' => $validated['price'] ?? null,
            'unit_id' => $validated['unit_id'] ?? null,
        ]);

        // Save barcodes if any
        if (!empty($validated['barcodes'])) {
            foreach ($validated['barcodes'] as $code) {
                if ($code) {
                    $product->barcodes()->create(['barcode' => $code]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function index()
    {
        $products = Product::with('unit', 'barcodes')->paginate(20);
        return view('products.index', compact('products'));
    }
}
