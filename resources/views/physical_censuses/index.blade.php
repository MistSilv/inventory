<x-layout title="Physical Censuses">

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4 text-amber-400">
            Physical Censuses – {{ $regionStocktaking->region->name ?? 'Unknown Region' }}
        </h1>

        <!-- Create button -->
        <div class="mb-6 flex gap-4 flex-wrap">
            <a href="{{ route('region_stocktakings.censuses.create', $regionStocktaking)}}" 
               class="bg-green-900 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition">
                + Create New Census
            </a>

            <a href="{{ route('region_stocktakings.revise', $regionStocktaking->id) }}"
               class="bg-blue-900 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                🔍 Przejdź do podsumowania (Revise)
            </a>
        </div>

        <!-- Census list -->
        @if($censuses->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-950 border border-gray-800 rounded-lg shadow-lg">
                    <thead class="bg-black text-amber-400">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Location</th>
                            <th class="px-4 py-2 text-left">Created At</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-200">
                        @foreach($censuses->sortByDesc('id') as $census)
                            <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                                <td class="px-4 py-2">{{ $census->id }}</td>
                                <td class="px-4 py-2">{{ $census->location ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $census->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('region_stocktakings.censuses.show', ['regionStocktaking' => $regionStocktaking->id, 'census' => $census->id]) }}" 
                                       class="text-blue-400 hover:underline">
                                       View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No censuses found for this region.</p>
        @endif

    </div>

</x-layout>
