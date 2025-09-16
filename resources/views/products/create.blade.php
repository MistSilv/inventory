<x-layout  :no-wrapper="true" title="Create Product">

    <div class="max-w-3xl mx-auto bg-gray-950 p-6 rounded-xl shadow-lg mt-6 border border-amber-700">
        <h2 class="text-2xl font-bold mb-6 text-amber-400">Create New Product</h2>

        <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- ID Abaco -->
            <div>
                <label for="id_abaco" class="block font-medium text-gray-300">ID Abaco</label>
                <input type="text" name="id_abaco" id="id_abaco"
                       class="mt-1 block w-full bg-gray-900 text-gray-200 border border-gray-700 rounded-md shadow-sm focus:ring-amber-700 focus:border-amber-700"
                       value="{{ old('id_abaco') }}">
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block font-medium text-gray-300">Name</label>
                <input type="text" name="name" id="name" required
                       class="mt-1 block w-full bg-gray-900 text-gray-200 border border-gray-700 rounded-md shadow-sm focus:ring-amber-700 focus:border-amber-700"
                       value="{{ old('name') }}">
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block font-medium text-gray-300">Price</label>
                <input type="number" step="0.01" name="price" id="price"
                       class="mt-1 block w-full bg-gray-900 text-gray-200 border border-gray-700 rounded-md shadow-sm focus:ring-amber-700 focus:border-amber-700"
                       value="{{ old('price') }}">
            </div>

            <!-- Unit -->
            <div>
                <label for="unit_id" class="block font-medium text-gray-300">Unit</label>
                <select name="unit_id" id="unit_id"
                        class="mt-1 block w-full bg-gray-900 text-gray-200 border border-gray-700 rounded-md shadow-sm focus:ring-amber-700 focus:border-amber-700">
                    <option value="">-- Select Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }} ({{ $unit->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- EAN Codes -->
            <div>
                <label class="block font-medium text-gray-300">EAN Codes</label>
                <div id="ean-container" class="space-y-2">
                    <input type="text" name="barcodes[]" maxlength="13"
                           class="mt-1 block w-full bg-gray-900 text-gray-200 border border-gray-700 rounded-md shadow-sm focus:ring-amber-700 focus:border-amber-700"
                           placeholder="99999999">
                </div>
                <button type="button" id="add-ean"
                        class="mt-2 px-3 py-1 bg-blue-900 text-white rounded hover:bg-blue-900 transition shadow">
                    Add another EAN
                </button>
            </div>

            <button type="submit" 
                    class="bg-green-900 hover:bg-green-900 text-white px-4 py-2 rounded shadow transition font-semibold">
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
            input.maxLength = 13;
            input.placeholder = '99999999';
            input.className = 'mt-1 block w-full bg-gray-900 text-gray-200 border border-gray-700 rounded-md shadow-sm focus:ring-amber-700 focus:border-amber-700';
            container.appendChild(input);
        });
    </script>

</x-layout>
