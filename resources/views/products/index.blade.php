<x-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4 text-amber-400">Products</h1>

        <a href="{{ route('products.create') }}" 
           class="bg-green-900 text-white px-4 py-2 rounded shadow hover:bg-green-600 mb-4 inline-block transition">
            Add New Product
        </a>

        @if(session('success'))
            <div class="bg-amber-700/20 border border-amber-700 text-amber-400 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-950 border border-gray-800 rounded-lg overflow-hidden shadow-lg">
                <thead class="bg-black text-amber-400">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Abaco ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Unit</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Barcodes</th>
                    </tr>
                </thead>
                <tbody class="text-gray-200">
                    @forelse($products as $product)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="px-4 py-2">{{ $product->id }}</td>
                            <td class="px-4 py-2">{{ $product->id_abaco ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $product->name }}</td>
                            <td class="px-4 py-2">{{ $product->unit->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $product->price ? number_format($product->price, 2) : '-' }}</td>
                            <td class="px-4 py-2">
                                @if($product->barcodes->count())
                                    @if($product->barcodes->count() > 1) 🔢 @endif
                                    {{ $product->barcodes->first()->barcode }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-layout>
