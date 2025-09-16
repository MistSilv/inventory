<x-layout title="New Physical Census">

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-amber-400">
        Create Physical Census – {{ $regionStocktaking->region->name }} (Stocktaking #{{ $regionStocktaking->stocktaking_id }})
    </h1>

    <!-- Scan buttons -->
    <div class="mb-6 flex gap-4 flex-wrap">
        <button type="button" id="start-scan"
                class="bg-green-900 text-white px-3 py-1 rounded shadow hover:bg-green-600 transition">
            Start Scanning
        </button>
        <button type="button" id="stop-scan"
                class="bg-red-900 text-white px-3 py-1 rounded shadow hover:bg-red-600 transition hidden">
            Stop Scanning
        </button>
        <div id="reader" style="width: 300px; display:none;"></div>
        <p id="scan-result" class="mt-2 text-sm text-gray-400"></p>
    </div>

    <form action="{{ route('region_stocktakings.censuses.store', $regionStocktaking) }}" method="POST" class="bg-gray-950 border border-gray-800 rounded-lg p-4 shadow-lg">
        @csrf
        <input type="hidden" name="region_stocktaking_id" value="{{ $regionStocktaking->id }}">

        <!-- Location -->
        <div class="mb-4">
            <label for="location" class="block font-semibold mb-1 text-amber-400">Location</label>
            <input type="text" name="location" id="location"
                   class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-900 text-gray-200"
                   placeholder="e.g., Freezer A, Shelf 3" value="{{ old('location') }}">
            @error('location')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Added products -->
        <div class="mb-4 overflow-x-auto">
            <table class="min-w-full border border-gray-800 mb-2 bg-gray-900 text-gray-200" id="products-table">
                <thead class="bg-black text-amber-400">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Unit</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows added dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Product list table with pagination -->
        <div class="overflow-x-auto border border-gray-800 rounded p-2 mb-4 bg-gray-950">
            <table class="min-w-full border border-gray-800 text-gray-200">
                <thead class="bg-black text-amber-400">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Unit</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Add</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                        <td class="px-4 py-2">{{ $product->id }}</td>
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2">{{ $product->unit->code ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $product->price ? number_format($product->price, 2) : '-' }}</td>
                        <td class="px-4 py-2">
                            <button type="button"
                                    class="add-product-btn bg-green-900 text-white px-3 py-1 rounded shadow hover:bg-green-600 transition"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}"
                                    data-unit="{{ $product->unit->code ?? '' }}">
                                Add
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-2">
                {{ $products->links('pagination::tailwind') }}
            </div>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                    class="bg-green-900 text-white px-4 py-2 rounded shadow hover:bg-green-600 transition">
                Save Census
            </button>
        </div>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#products-table tbody');
    let index = 0;

    // Funkcja do wczytania tymczasowych produktów z serwera
    async function loadTempProducts() {
        tableBody.innerHTML = '';
        const res = await fetch('{{ route("temp_products.list") }}');
        const products = await res.json();
        index = 0;
        products.forEach(product => addRow(product));
    }

    // Funkcja dodająca wiersz do tabeli
    function addRow(product) {
        const step = (product.unit === 'szt' || product.unit === 'opak') ? 1 : 0.001;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-2">
                ${product.name}
                <input type="hidden" name="products[${index}][id]" value="${product.product_id}">
                <input type="hidden" name="products[${index}][name]" value="${product.name}">
                <input type="hidden" name="products[${index}][price]" value="${product.price}">
            </td>
            <td class="px-4 py-2">
                <input type="number" step="${step}" min="0"
                       name="products[${index}][quantity]"
                       class="border rounded px-2 py-1 w-24 quantity-input bg-transparent text-white"
                       value="${product.quantity}">
            </td>
            <td class="px-4 py-2">${product.unit}</td>
            <td class="px-4 py-2">${product.price ?? 0}</td>
            <td class="px-4 py-2">
                <button type="button" class="remove-btn bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Remove</button>
            </td>
        `;

        // Remove product
        row.querySelector('.remove-btn').addEventListener('click', async () => {
            await fetch('{{ route("temp_products.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: product.product_id })
            });
            row.remove();
        });

        // Update quantity on change
        row.querySelector('.quantity-input').addEventListener('change', async (e) => {
            const newQuantity = parseFloat(e.target.value) || 0;
            await fetch('{{ route("temp_products.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: product.product_id,
                    quantity: newQuantity,
                    price: product.price,
                    name: product.name,
                    unit: product.unit
                })
            });
        });

        tableBody.appendChild(row);
        index++;
    }

    // Obsługa kliknięcia w przycisk "Add" dla produktów
    function attachAddButtons() {
        document.querySelectorAll('.add-product-btn').forEach(btn => {
            btn.addEventListener('click', async function () {
                const product = {
                    product_id: btn.dataset.id,
                    name: btn.dataset.name,
                    unit: btn.dataset.unit,
                    price: btn.dataset.price || 0,
                    quantity: 1
                };

                await fetch('{{ route("temp_products.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(product)
                });

                addRow(product);
            });
        });
    }

    attachAddButtons(); // pierwsze podłączenie przycisków Add

    // Nasłuch kliknięcia w paginację
    document.addEventListener('click', async function(e) {
        if(e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            const res = await fetch(url);
            const html = await res.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Zamień tbody w tabeli paginowanej
            const newTbody = doc.querySelector('.overflow-x-auto table tbody');
            if(newTbody) {
                const paginatedTable = document.querySelector('.overflow-x-auto table tbody');
                if(paginatedTable) {
                    paginatedTable.innerHTML = newTbody.innerHTML;
                }
            }

            // Podłącz eventy ponownie
            attachAddButtons();

            // Załaduj tymczasowe produkty
            await loadTempProducts();
        }
    });

    // Pierwsze wczytanie tymczasowych produktów
    loadTempProducts();
});
</script>



<script src="https://unpkg.com/html5-qrcode"></script>
<script src="{{ asset('js/barcode-scanner.js') }}"></script>

</x-layout>
