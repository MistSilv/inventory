<x-layout title="Physical Census Details">

    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6 text-amber-400">
            Physical Census #{{ $census->id }} – {{ $regionStocktaking->region->name ?? 'Unknown Region' }}
        </h1>

        <div class="mb-4 text-gray-200">
            <p class="text-sm">
                Stocktaking: <span class="font-medium text-amber-400">{{ $regionStocktaking->stocktaking->title ?? 'Untitled' }}</span>
            </p>
            <p class="text-sm">
                Location: <span class="font-medium text-amber-400">{{ $census->location ?? '-' }}</span>
            </p>
            <p class="text-sm">
                Created At: <span class="font-medium text-amber-400">{{ $census->created_at->format('Y-m-d H:i') }}</span>
            </p>
        </div>

        <h2 class="text-lg font-semibold mb-2 text-amber-400">Products</h2>

        @if($census->products->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-950 border border-gray-800 rounded-lg overflow-hidden shadow-lg">
                    <thead class="bg-black text-amber-400">
                        <tr>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Unit</th>
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-200">
                        @php $grandTotal = 0; @endphp
                        @foreach($census->products as $product)
                            @php 
                                $total = $product->pivot->quantity * $product->pivot->price; 
                                $grandTotal += $total;
                            @endphp
                            <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                                <td class="px-4 py-2">{{ $product->name }}</td>
                                <td class="px-4 py-2">{{ $product->pivot->quantity }}</td>
                                <td class="px-4 py-2">{{ $product->unit->code ?? '-' }}</td>
                                <td class="px-4 py-2">{{ number_format($product->pivot->price, 2) }} zł</td>
                                <td class="px-4 py-2">{{ number_format($total, 2) }} zł</td>
                            </tr>
                        @endforeach
                        <tr class="bg-black font-semibold text-amber-400">
                            <td class="px-4 py-2 text-right" colspan="4">Grand Total:</td>
                            <td class="px-4 py-2">{{ number_format($grandTotal, 2) }} zł</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No products added to this census yet.</p>
        @endif

        <div class="mt-6">
            <a href="{{ route('region_stocktakings.censuses.index', $regionStocktaking) }}"
               class="bg-blue-900 hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition inline-block">
                ← Back to Censuses
            </a>
        </div>

    </div>

</x-layout>
