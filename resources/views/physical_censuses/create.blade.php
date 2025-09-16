<x-layout title="New Physical Census">

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">
        Create Physical Census – {{ $regionStocktaking->region->name }} (Stocktaking #{{ $regionStocktaking->stocktaking_id }})
    </h1>

    <div class="mb-4">
        <button type="button" id="start-scan"
                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
            Start Scanning
        </button>
        <button type="button" id="stop-scan"
                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition hidden">
            Stop Scanning
        </button>
        <div id="reader" style="width: 300px; display:none;"></div>
        <p id="scan-result" class="mt-2 text-sm text-gray-600"></p>
    </div>



    <form action="{{ route('region_stocktakings.censuses.store', $regionStocktaking) }}" method="POST">
        @csrf
        <input type="hidden" name="region_stocktaking_id" value="{{ $regionStocktaking->id }}">

        <!-- Location -->
        <div class="mb-4">
            <label for="location" class="block font-semibold mb-1">Location</label>
            <input type="text" name="location" id="location"
                   class="w-full border rounded px-3 py-2"
                   placeholder="e.g., Freezer A, Shelf 3" value="{{ old('location') }}">
            @error('location')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Products -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Products</label>

            <table class="min-w-full border border-gray-300 mb-2" id="products-table">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-left">Quantity</th>
                        <th class="px-4 py-2 text-left">Unit</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be added dynamically -->
                </tbody>
            </table>

            <div>
                <select id="product-select" class="border rounded px-2 py-1">
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                data-unit="{{ $product->unit->code ?? '' }}">
                            {{ $product->name }} ({{ number_format($product->price, 2) }} $)
                        </option>
                    @endforeach
                </select>
                <button type="button" id="add-product-btn"
                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                    Add Product
                </button>
            </div>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Save Census
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('add-product-btn');
    const tableBody = document.querySelector('#products-table tbody');
    const select = document.getElementById('product-select');
    let index = 0;

    addBtn.addEventListener('click', function () {
        const selectedOption = select.options[select.selectedIndex];
        const productId = select.value;
        const productName = selectedOption.text;
        const productPrice = selectedOption.dataset.price;
        const productUnit = selectedOption.dataset.unit || '';

        if (!productId) return;

        // step = 1 for integer units, 0.001 for decimal
        const step = (productUnit === 'szt' || productUnit === 'opak') ? 1 : 0.001;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-2">
                ${productName}
                <input type="hidden" name="products[${index}][id]" value="${productId}">
                <input type="hidden" name="products[${index}][price]" value="${productPrice}">
            </td>
            <td class="px-4 py-2">
                <input type="number" step="${step}" min="0"
                       name="products[${index}][quantity]" class="border rounded px-2 py-1 w-24" value="0">
            </td>
            <td class="px-4 py-2">${productUnit}</td>
            <td class="px-4 py-2">
                <button type="button" class="remove-btn bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Remove</button>
            </td>
        `;

        tableBody.appendChild(row);

        row.querySelector('.remove-btn').addEventListener('click', function () {
            row.remove();
        });

        index++;
        select.value = '';
    });
});
</script>

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="{{ asset('js/barcode-scanner.js') }}"></script>


</x-layout>
