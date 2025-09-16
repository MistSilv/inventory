<x-layout title="Physical Censuses">

    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6 text-amber-400">Physical Censuses</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-amber-700/20 border border-amber-700 text-amber-400 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($censuses->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($censuses as $census)
                    <div class="border rounded-lg shadow p-4 bg-gray-950 text-gray-200 hover:shadow-lg transition">
                        <h2 class="text-lg font-semibold mb-2 text-amber-400">
                            {{ $census->regionStocktaking->region->name ?? 'Unknown Region' }}
                        </h2>

                        <p class="text-sm mb-1">
                            Stocktaking: 
                            <span class="font-medium text-amber-400">
                                {{ $census->regionStocktaking->stocktaking->title ?? 'Untitled' }}
                            </span>
                        </p>

                        <p class="text-sm mb-1">
                            Location: <span class="font-medium text-amber-400">{{ $census->location ?? '-' }}</span>
                        </p>

                        <p class="text-sm mb-1">
                            Products Count: <span class="font-medium text-amber-400">{{ $census->products->count() }}</span>
                        </p>

                        <p class="text-xs text-gray-500">
                            Created: {{ $census->created_at->format('Y-m-d H:i') }}
                        </p>

                        <div class="mt-3">
                            <a href="{{ route('physical_censuses.show', $census->id) }}" 
                               class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                               View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $censuses->links() }}
            </div>
        @else
            <p class="text-gray-500">No physical censuses found.</p>
        @endif

    </div>

</x-layout>
