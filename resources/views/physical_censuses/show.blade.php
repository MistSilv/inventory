<x-layout title="Physical Census Details">

    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6">
            Physical Census #{{ $census->id }} – {{ $regionStocktaking->region->name ?? 'Unknown Region' }}
        </h1>

        <div class="mb-4">
            <p class="text-sm text-gray-600">
                Stocktaking: <span class="font-medium">{{ $regionStocktaking->stocktaking->title ?? 'Untitled' }}</span>
            </p>
            <p class="text-sm text-gray-600">
                Location: <span class="font-medium">{{ $census->location ?? '-' }}</span>
            </p>
            <p class="text-sm text-gray-600">
                Created At: <span class="font-medium">{{ $census->created_at->format('Y-m-d H:i') }}</span>
            </p>
        </div>

        <h2 class="text-lg font-semibold mb-2">Products</h2>

        @if($census->products->count())
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Unit</th>
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach($census->products as $product)
                            @php 
                                $total = $product->pivot->quantity * $product->pivot->price; 
                                $grandTotal += $total;
                            @endphp
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $product->name }}</td>
                                <td class="px-4 py-2">{{ $product->pivot->quantity }}</td>
                                <td class="px-4 py-2">{{ $product->unit->code ?? '-' }}</td>
                                <td class="px-4 py-2">{{ number_format($product->pivot->price, 2) }} zł</td>
                                <td class="px-4 py-2">{{ number_format($total, 2) }} zł</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-semibold">
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
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                ← Back to Censuses
            </a>
        </div>

    </div>

</x-layout>
