<x-layout title="Create Stocktaking">

    <div class="container mx-auto p-6 max-w-lg bg-white rounded shadow mt-6">
        <h2 class="text-2xl font-bold mb-4">Create New Stocktaking</h2>

        <form action="{{ route('stocktakings.store') }}" method="POST">
            @csrf

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label for="date" class="block font-medium text-gray-700">Date</label>
                <input type="date" name="date" id="date"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('date', now()->format('Y-m-d')) }}">
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Regions -->
            <div class="mb-4">
                <label class="block font-medium text-gray-700">Select Regions (at least one)</label>
                <div class="space-y-2">
                    @foreach($regions as $region)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="regions[]" value="{{ $region->id }}"
                                       class="form-checkbox" {{ in_array($region->id, old('regions', [])) ? 'checked' : '' }}>
                                <span class="ml-2">{{ $region->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('regions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Create Stocktaking
            </button>
        </form>
    </div>

</x-layout>
