<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegionStocktaking;
use App\Models\PhysicalCensus;
use App\Models\Product;
use App\Models\TempCensusProduct;
use Illuminate\Support\Facades\Auth;

class PhysicalCensusController extends Controller
{
    // Lista wszystkich censusów w danym region_stocktaking
    public function index(RegionStocktaking $regionStocktaking)
    {
        // Tylko przy wejściu do listy censusów czyścimy tymczasowe produkty
        TempCensusProduct::where('user_id', Auth::id())->delete();

        $censuses = $regionStocktaking->censuses()->with('products')->get();
        return view('physical_censuses.index', compact('regionStocktaking', 'censuses'));
    }


    // Formularz tworzenia
    public function create($regionStocktakingId)
    {
        $regionStocktaking = RegionStocktaking::with('region')->findOrFail($regionStocktakingId);
        
        $products = Product::with('unit')->paginate(50); 
        
        return view('physical_censuses.create', compact('regionStocktaking', 'products'));
    }

    // Zapis nowego
     public function store(Request $request, $regionStocktakingId)
    {
        $tempProducts = TempCensusProduct::where('user_id', Auth::id())->get();

        $census = PhysicalCensus::create([
            'region_stocktaking_id' => $regionStocktakingId,
            'location' => $request->location,
        ]);

        foreach ($tempProducts as $item) {
            $census->products()->attach($item->product_id, [
                'quantity' => $item->quantity,
                'price'    => $item->price,
            ]);
        }

        TempCensusProduct::where('user_id', Auth::id())->delete();

        return redirect()->route('region_stocktakings.censuses.index', $regionStocktakingId)
                        ->with('success', 'Census saved successfully!');
    }




    // Podgląd szczegółowy jednego census
    public function show(RegionStocktaking $regionStocktaking, PhysicalCensus $census)
    {
        // No need for manual check anymore
        return view('physical_censuses.show', compact('regionStocktaking', 'census'));
    }

    

    public function getTempProducts()
    {
        $products = TempCensusProduct::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($products);
    }

}
