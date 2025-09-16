<x-layout title="Stocktaking Details">

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">
            {{ $stocktaking->title ?? 'Untitled Stocktaking' }}
        </h1>

        <p class="mb-4 text-gray-600">
            Date: {{ $stocktaking->date?->format('Y-m-d') ?? '-' }} <br>
            Status: {{ ucfirst($stocktaking->status) }}
        </p>

        <h2 class="text-xl font-semibold mb-2">Regions</h2>
        @if($stocktaking->regionStocktakings->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($stocktaking->regionStocktakings as $regionStock)
                    <a href="{{ route('region_stocktakings.censuses.index', $regionStock) }}">
                        <div class="border rounded-lg p-4 shadow hover:shadow-lg transition">
                            <h3 class="text-lg font-bold">{{ $regionStock->region->name ?? '-' }}</h3>
                            <p class="text-sm text-gray-600">Code: {{ $regionStock->region->code ?? '-' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No regions assigned to this stocktaking.</p>
        @endif


    </div>

</x-layout>
