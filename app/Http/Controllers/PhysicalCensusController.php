<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegionStocktaking;
use App\Models\PhysicalCensus;
use App\Models\Product;

class PhysicalCensusController extends Controller
{
    // Lista wszystkich censusów w danym region_stocktaking
    public function index(RegionStocktaking $regionStocktaking)
    {
        $censuses = $regionStocktaking->censuses()->with('products')->get();

        return view('physical_censuses.index', compact('regionStocktaking', 'censuses'));
    }

    // Formularz tworzenia
    public function create(RegionStocktaking $regionStocktaking)
    {
        $products = Product::all(); // pobranie wszystkich produktów
        return view('physical_censuses.create', compact('regionStocktaking', 'products'));
    }
    // Zapis nowego
    public function store(Request $request, RegionStocktaking $regionStocktaking)
    {
        $validated = $request->validate([
            'location' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0',
        ]);

        // Create the census
        $census = $regionStocktaking->censuses()->create([
            'location' => $validated['location'] ?? null
        ]);

        // Attach products
        foreach ($validated['products'] as $p) {
            $product = Product::find($p['id']); // fetch the product from DB
            $census->products()->attach($p['id'], [
                'quantity' => $p['quantity'],
                'price' => $product->price, // use actual DB price
            ]);
        }


        return redirect()->route('region_stocktakings.censuses.index', $regionStocktaking)
                        ->with('success', 'Physical census created successfully!');
    }



    // Podgląd szczegółowy jednego census
    public function show(RegionStocktaking $regionStocktaking, PhysicalCensus $census)
    {
        // No need for manual check anymore
        return view('physical_censuses.show', compact('regionStocktaking', 'census'));
    }

}
