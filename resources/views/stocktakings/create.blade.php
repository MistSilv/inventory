<x-layout :no-wrapper="true" title="Create Stocktaking">

    <div class="container mx-auto p-6 max-w-lg bg-black border-2 border-amber-700 rounded shadow-lg mt-6">
        <h2 class="text-2xl font-bold mb-4 text-amber-400">Create New Stocktaking</h2>

        <form action="{{ route('stocktakings.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block font-medium text-amber-400">Title</label>
                <input type="text" name="title" id="title" required
                       class="mt-1 block w-full bg-gray-900 text-gray-200 border border-amber-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-700"
                       value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block font-medium text-amber-400">Date</label>
                <input type="date" name="date" id="date"
                       class="mt-1 block w-full bg-gray-900 text-gray-200 border border-amber-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-700"
                       value="{{ old('date', now()->format('Y-m-d')) }}">
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block font-medium text-amber-400">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="mt-1 block w-full bg-gray-900 text-gray-200 border border-amber-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-700">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Regions -->
            <div>
                <label class="block font-medium text-amber-400">Select Regions (at least one)</label>
                <div class="space-y-2 mt-1">
                    @foreach($regions as $region)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="regions[]" value="{{ $region->id }}"
                                       class="form-checkbox text-amber-600" {{ in_array($region->id, old('regions', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-200">{{ $region->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('regions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-green-900 hover:bg-green-600 text-white py-2 rounded font-semibold transition">
                Create Stocktaking
            </button>
        </form>
    </div>

</x-layout>
