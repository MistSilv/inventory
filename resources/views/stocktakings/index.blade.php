<x-layout title="Stocktakings">

    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-4">Stocktakings</h1>

        <!-- Create button -->
        <div class="mb-6">
            <a href="{{ route('stocktakings.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
               Create New Stocktaking
            </a>
        </div>

        <!-- Active Stocktakings -->
        @if($active->count())
            <h2 class="text-xl font-semibold mb-2">Active Stocktakings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($active as $stocktaking)
                    <a href="{{ route('stocktakings.show', $stocktaking->id) }}">
                        <div class="border rounded-lg p-4 shadow hover:shadow-lg transition">
                            <h3 class="text-lg font-bold">{{ $stocktaking->title ?? 'Untitled' }}</h3>
                            <p class="text-sm text-gray-600">Date: {{ $stocktaking->date?->format('Y-m-d') ?? '-' }}</p>
                            <p class="text-sm text-gray-600">Status: {{ ucfirst($stocktaking->status) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Archived Stocktakings -->
        <h2 class="text-xl font-semibold mb-2">Archived Stocktakings</h2>
        @if($archived->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archived as $stocktaking)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $stocktaking->id }}</td>
                                <td class="px-4 py-2">{{ $stocktaking->title ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $stocktaking->date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ ucfirst($stocktaking->status) }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('stocktakings.show', $stocktaking->id) }}" 
                                       class="text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No archived stocktakings found.</p>
        @endif

    </div>

</x-layout>
