<x-layout title="Stocktakings">

    <div class="container mx-auto p-6">

        <h1 class="text-3xl font-bold mb-6 text-amber-400">Stocktakings</h1>

        <!-- Create button -->
        <div class="mb-6">
            <a href="{{ route('stocktakings.create') }}" 
               class="bg-green-900 text-white px-4 py-2 rounded hover:bg-green-600 transition font-semibold shadow">
               Create New Stocktaking
            </a>
        </div>

        <!-- Active Stocktakings -->
        @if($active->count())
            <h2 class="text-2xl font-semibold mb-4 text-amber-300">Active Stocktakings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($active as $stocktaking)
                    <a href="{{ route('stocktakings.show', $stocktaking->id) }}">
                        <div class="bg-gray-900 border-2 border-amber-700 rounded-xl p-4 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
                            <h3 class="text-lg font-bold text-amber-400">{{ $stocktaking->title ?? 'Untitled' }}</h3>
                            <p class="text-gray-300 text-sm mt-1">Date: {{ $stocktaking->date?->format('Y-m-d') ?? '-' }}</p>
                            <p class="text-gray-300 text-sm">Status: {{ ucfirst($stocktaking->status) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Archived Stocktakings -->
        <h2 class="text-2xl font-semibold mb-4 text-amber-300">Archived Stocktakings</h2>
        @if($archived->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-black border-2 border-amber-700 rounded-lg">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-amber-400">ID</th>
                            <th class="px-4 py-2 text-left text-amber-400">Title</th>
                            <th class="px-4 py-2 text-left text-amber-400">Date</th>
                            <th class="px-4 py-2 text-left text-amber-400">Status</th>
                            <th class="px-4 py-2 text-left text-amber-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archived as $stocktaking)
                            <tr class="border-b border-gray-700 hover:bg-gray-800">
                                <td class="px-4 py-2 text-gray-300">{{ $stocktaking->id }}</td>
                                <td class="px-4 py-2 text-gray-300">{{ $stocktaking->title ?? '-' }}</td>
                                <td class="px-4 py-2 text-gray-300">{{ $stocktaking->date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-2 text-gray-300">{{ ucfirst($stocktaking->status) }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('stocktakings.show', $stocktaking->id) }}" 
                                       class="text-amber-400 hover:underline">View</a>
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
