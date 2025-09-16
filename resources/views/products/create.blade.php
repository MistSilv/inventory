<x-layout title="Create Product">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-2xl font-bold mb-4">Create New Product</h2>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <!-- ID Abaco -->
            <div class="mb-4">
                <label for="id_abaco" class="block font-medium text-gray-700">ID Abaco</label>
                <input type="text" name="id_abaco" id="id_abaco"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('id_abaco') }}">
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('name') }}">
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label for="price" class="block font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" name="price" id="price"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('price') }}">
            </div>

            <!-- Unit -->
            <div class="mb-4">
                <label for="unit_id" class="block font-medium text-gray-700">Unit</label>
                <select name="unit_id" id="unit_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }} ({{ $unit->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- EAN Codes -->
            <div class="mb-4">
                <label class="block font-medium text-gray-700">EAN Codes</label>
                <div id="ean-container" class="space-y-2">
                    <input type="text" name="barcodes[]" maxlength="13"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="99999999">
                </div>
                <button type="button" id="add-ean" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Add another EAN
                </button>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Save Product
            </button>
        </form>
    </div>

    <script>
        const addButton = document.getElementById('add-ean');
        const container = document.getElementById('ean-container');

        addButton.addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'barcodes[]';
            input.maxLength = 13; // limit input length to 13
            input.placeholder = '99999999';
            input.className = 'mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500';
            container.appendChild(input);
        });
    </script>

</x-layout>
