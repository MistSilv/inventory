<x-layout title="Physical Censuses">

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">
            Physical Censuses – {{ $regionStocktaking->region->name ?? 'Unknown Region' }}
        </h1>

        <!-- Create button -->
        <div class="mb-6">
            <a href="{{ route('region_stocktakings.censuses.create', $regionStocktaking)}}" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                + Create New Census
            </a>
        </div>
        <a href="{{ route('region_stocktakings.revise', $regionStocktaking->id) }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            🔍 Przejdź do podsumowania (Revise)
        </a>


        <!-- Census list -->
        @if($censuses->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Location</th>
                            <th class="px-4 py-2 text-left">Created At</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($censuses as $census)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $census->id }}</td>
                                <td class="px-4 py-2">{{ $census->location ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $census->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('region_stocktakings.censuses.show', ['regionStocktaking' => $regionStocktaking->id, 'census' => $census->id]) }}" class="text-blue-500 hover:underline">View</a>

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
