<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stocktaking;
use App\Models\RegionStocktaking;
use App\Models\Product;
use App\Models\StocktakingAdjustment;

use Illuminate\Support\Collection;

class StocktakingController extends Controller
{
    public function index()
    {
        $active = Stocktaking::active()->latest()->get();
        $archived = Stocktaking::archived()->latest()->get();
        return view('stocktakings.index', compact('active', 'archived'));
    }

    public function create()
    {
        $regions = \App\Models\Region::all(); 
        return view('stocktakings.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'regions' => 'required|array|min:1',
            'regions.*' => 'exists:regions,id',
        ]);

        $stocktaking = Stocktaking::create($validated);

        foreach ($validated['regions'] as $regionId) {
            $stocktaking->regions()->create(['region_id' => $regionId]);
        }

        return redirect()->route('stocktakings.index')->with('success', 'Stocktaking created successfully!');
    }

    public function show($id)
    {
        $stocktaking = Stocktaking::with('regionStocktakings.region')->findOrFail($id);
        return view('stocktakings.show', compact('stocktaking'));
    }

   
    public function revise(RegionStocktaking $regionStocktaking)
    {
        $regionStocktaking->load(['adjustments', 'censuses.products.unit', 'censuses.products.barcodes']);

        // Jeśli brak zapisanych adjustments, tworzymy je z zagregowanych produktów
        if ($regionStocktaking->adjustments->isEmpty()) {
            $aggregated = [];

            // Agregacja produktów z censusów
            foreach ($regionStocktaking->censuses as $census) {
                foreach ($census->products as $product) {
                    $id = $product->id;
                    if (!isset($aggregated[$id])) {
                        $aggregated[$id] = [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'barcode' => $product->barcodes->first()?->barcode ?? null,
                            'unit' => $product->unit?->code ?? null,
                            'quantity' => 0,
                            'unit_price' => $product->price ?? 0,
                        ];
                    }
                    $aggregated[$id]['quantity'] += $product->pivot->quantity;
                }
            }

            // Zapis do bazy
            foreach ($aggregated as $data) {
                StocktakingAdjustment::create([
                    'region_stocktaking_id' => $regionStocktaking->id,
                    'product_id' => $data['product_id'],
                    'product_name' => $data['product_name'],
                    'barcode' => $data['barcode'],
                    'unit' => $data['unit'],
                    'quantity' => $data['quantity'],
                    'unit_price' => $data['unit_price'],
                    'adjusted_by' => auth()->id(),
                ]);
            }

            // Załaduj ponownie adjustments
            $regionStocktaking->load(['adjustments' => function ($query) {
                $query->orderBy('product_id'); // sortowanie malejąco po product_id
            }]);

        }

        // Przygotowanie do widoku
        // Przygotowanie do widoku
        $finalProducts = [];
        foreach ($regionStocktaking->adjustments as $adjustment) {
            $finalProducts[] = [
                'product_name' => $adjustment->product_name,
                'barcode'      => $adjustment->barcode,
                'unit'         => $adjustment->unit,
                'quantity'     => $adjustment->quantity,
                'unit_price'   => $adjustment->unit_price,
                'value'        => $adjustment->quantity * $adjustment->unit_price,
                'adjustment_id'=> $adjustment->id,
                'batch_key'    => 'adj-'.$adjustment->id,
                'product_id'   => $adjustment->product_id,
            ];
        }

        // Sortowanie malejąco po product_id
        $finalProducts = collect($finalProducts)
            ->sortBy('product_id')
            ->values()
            ->all();

        return view('stocktakings.revise', [
            'regionStocktaking' => $regionStocktaking,
            'aggregatedProducts' => $finalProducts
        ]);

    }

    public function storeAdjustments(Request $request, RegionStocktaking $regionStocktaking)
{
    $validated = $request->validate([
        'products' => 'required|array',
        'products.*.product_id' => 'nullable|exists:products,id',
        'products.*.quantity' => 'required|numeric',
        'products.*.unit_price' => 'nullable|numeric',
        'products.*.product_name' => 'required|string',
        'products.*.barcode' => 'nullable|string',
        'products.*.unit' => 'nullable|string',
        'products.*.adjustment_id' => 'nullable|numeric',
        'products.*.batch_key' => 'nullable|string',
    ]);

    $userId = auth()->id();

    // grupowanie produktów po oryginalnym adjustment_id
    $grouped = collect($validated['products'])->groupBy('adjustment_id');

    foreach ($grouped as $adjustmentId => $productsGroup) {

        if ($adjustmentId) {
            // usuń oryginalny rekord przed wprowadzeniem podziału
            StocktakingAdjustment::where('id', $adjustmentId)->delete();
        }

        foreach ($productsGroup as $productData) {
            StocktakingAdjustment::create([
                'region_stocktaking_id' => $regionStocktaking->id,
                'product_id' => $productData['product_id'] ?? null,
                'product_name' => $productData['product_name'],
                'barcode' => $productData['barcode'] ?? null,
                'unit' => $productData['unit'] ?? null,
                'quantity' => $productData['quantity'],
                'unit_price' => $productData['unit_price'] ?? 0,
                'adjusted_by' => $userId,
            ]);
        }
    }

    return response()->json(['success' => true]);
}

}
