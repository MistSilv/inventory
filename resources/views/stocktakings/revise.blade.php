<x-layout>
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">
        Przegląd spisu – {{ $regionStocktaking->region->name }}
    </h1>

    <div class="mb-6">
        <p><strong>Stocktaking:</strong> {{ $regionStocktaking->stocktaking->title ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $regionStocktaking->stocktaking->status }}</p>
        <p><strong>Data:</strong> {{ $regionStocktaking->stocktaking->date }}</p>
    </div>

    <form id="stocktaking-form" action="{{ route('stocktakings.adjustments.store', $regionStocktaking->id) }}" method="POST">
        @csrf
        <h2 class="text-xl font-semibold mb-2">Agregacja produktów</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full border border-gray-300 border-separate" style="border-spacing: 0 4px;">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border-b border-gray-300">Poz.</th>
                        <th class="px-4 py-2 border-b border-gray-300">Towar</th>
                        <th class="px-4 py-2 border-b border-gray-300">Barcode</th>
                        <th class="px-4 py-2 border-b border-gray-300">Jm</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-right">Ilość</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-right">Cena jedn.</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-right">Wartość</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-center">Akcje</th>
                    </tr>
                </thead>
                @php
                    $sortedProducts = collect($aggregatedProducts)
                        ->sortBy('product_id') // sortowanie malejąco po product_id
                        ->values(); // reset indeksów
                @endphp
                <tbody id="products-body">
                    @foreach($aggregatedProducts as $index => $row)
                    <tr class="bg-white shadow-md rounded-lg product-row" 
                        data-product-id="{{ $row['product_id'] }}"
                        data-batch-key="{{ $row['batch_key'] }}"
                        data-adjustment-id="{{ $row['adjustment_id'] }}">
                        <td class="px-4 py-2 border border-gray-200">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $row['product_name'] }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $row['barcode'] }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $row['unit'] }}</td>

                        <td class="px-4 py-2 border border-gray-200 text-right">
                            <span class="quantity-text">{{ $row['quantity'] }}</span>
                            <input type="text" name="products[{{ $index }}][quantity]" value="{{ $row['quantity'] }}" class="border px-2 py-1 w-20 text-right editable hidden">
                        </td>
                        <td class="px-4 py-2 border border-gray-200 text-right">
                            <span class="unit-price-text">{{ $row['unit_price'] }}</span>
                            <input type="text" name="products[{{ $index }}][unit_price]" value="{{ $row['unit_price'] }}" class="border px-2 py-1 w-20 text-right editable hidden">
                        </td>
                        <td class="px-4 py-2 border border-gray-200 text-right value-cell">{{ number_format($row['value'], 2, ',', ' ') }}</td>
                        <td class="px-4 py-2 border border-gray-200 text-center">
                            <button type="button" class="edit-btn cursor-pointer">✏️</button>
                            <button type="button" class="save-btn cursor-pointer hidden bg-green-200 px-2 py-1 rounded">💾</button>
                            <button type="button" class="cancel-btn cursor-pointer hidden bg-red-200 px-2 py-1 rounded">❌</button>
                            <button type="button" class="split-btn cursor-pointer bg-blue-200 px-2 py-1 rounded">➕ Podziel</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="6" class="text-right px-4 py-2 border border-gray-200">Łączna wartość:</td>
                        <td id="total-value" class="text-right px-4 py-2 border border-gray-200">0,00</td>
                        <td class="border border-gray-200"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Zapisz wszystkie zmiany</button>
        </div>
    </form>
</div>

<script>
function updateRowNumbers() {
    document.querySelectorAll('#products-body tr').forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

function updateRowValue(row) {
    const quantityInput = row.querySelector('input[name*="[quantity]"]');
    const priceInput = row.querySelector('input[name*="[unit_price]"]');
    const valueCell = row.querySelector('.value-cell');

    const quantity = parseFloat(quantityInput.value.replace(',', '.')) || 0;
    const price = parseFloat(priceInput.value.replace(',', '.')) || 0;
    const value = quantity * price;

    valueCell.textContent = value.toFixed(2).replace('.', ',');
    updateTotalValue();
}

function updateTotalValue() {
    let total = 0;
    document.querySelectorAll('#products-body tr').forEach(row => {
        const qtyInput = row.querySelector('input[name*="[quantity]"]');
        const priceInput = row.querySelector('input[name*="[unit_price]"]');

        const quantity = parseFloat(qtyInput.value.replace(',', '.')) || 0;
        const price = parseFloat(priceInput.value.replace(',', '.')) || 0;

        total += quantity * price;
    });

    document.getElementById('total-value').textContent = total.toFixed(2).replace('.', ',');
}

function initRowEvents(row) {
    const editBtn = row.querySelector('.edit-btn');
    const saveBtn = row.querySelector('.save-btn');
    const cancelBtn = row.querySelector('.cancel-btn');
    const splitBtn = row.querySelector('.split-btn');
    const inputs = row.querySelectorAll('.editable');

    if (!editBtn) return;

    let originalValues = [];

    editBtn.addEventListener('click', () => {
        originalValues = Array.from(inputs).map(input => input.value);

        inputs.forEach(input => {
            input.classList.remove('hidden');
            input.removeAttribute('readonly');
            input.classList.add('bg-yellow-100');
            input.addEventListener('input', () => updateRowValue(row));
        });

        row.querySelectorAll('.quantity-text, .unit-price-text').forEach(span => span.classList.add('hidden'));

        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
    });

    cancelBtn.addEventListener('click', () => {
        inputs.forEach((input, i) => {
            input.value = originalValues[i];
            input.setAttribute('readonly', true);
            input.classList.remove('bg-yellow-100');
            input.classList.add('hidden');
        });

        row.querySelectorAll('.quantity-text, .unit-price-text').forEach(span => span.classList.remove('hidden'));

        updateRowValue(row);
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
    });

    saveBtn.addEventListener('click', () => {
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.remove('bg-yellow-100');
            input.classList.add('hidden');
        });

        row.querySelector('.quantity-text').textContent = row.querySelector('input[name*="[quantity]"]').value;
        row.querySelector('.unit-price-text').textContent = row.querySelector('input[name*="[unit_price]"]').value;

        row.dataset.changed = 'true';

        row.querySelectorAll('.quantity-text, .unit-price-text').forEach(span => span.classList.remove('hidden'));

        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');

        updateRowValue(row);
    });

    splitBtn.addEventListener('click', () => {
        function parseDecimal(value) {
            if (!value) return 0;
            value = value.toString().trim().replace(',', '.');
            if (value.startsWith('.')) value = '0' + value;
            const num = parseFloat(value);
            if (isNaN(num)) throw new Error(`Niepoprawna liczba: ${value}`);
            return num;
        }

        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        let availableQty;
        try {
            availableQty = parseDecimal(quantityInput.value);
        } catch (e) {
            return alert(e.message);
        }

        if (availableQty <= 0) return alert('Nie można podzielić produktu o zerowej ilości.');

        let splitQty;
        while (true) {
            const input = prompt(`Podaj ilość do podzielenia (max ${availableQty}):`, '0');
            if (input === null) return; // anulowane
            try {
                splitQty = parseDecimal(input);
            } catch (e) {
                alert(e.message);
                continue;
            }
            if (splitQty <= 0 || splitQty >= availableQty) {
                alert(`Niepoprawna ilość. Wprowadź wartość >0 i <${availableQty}.`);
            } else {
                break;
            }
        }

        // Oryginalny wiersz – zmniejszamy ilość
        const remainingQty = availableQty - splitQty;
        quantityInput.value = remainingQty.toFixed(3).replace('.', ',');
        row.querySelector('.quantity-text').textContent = remainingQty.toFixed(3).replace('.', ',');
        row.dataset.changed = 'true';
        updateRowValue(row);

        // Tworzymy nowy wiersz
        const newRow = row.cloneNode(true);
        const nextIndex = document.querySelectorAll('.product-row').length;

        const newQtyInput = newRow.querySelector('input[name*="[quantity]"]');
        newQtyInput.name = `products[${nextIndex}][quantity]`;
        newQtyInput.value = splitQty.toFixed(3).replace('.', ',');

        const newPriceInput = newRow.querySelector('input[name*="[unit_price]"]');
        newPriceInput.name = `products[${nextIndex}][unit_price]`;
        
        newRow.querySelector('.quantity-text').textContent = splitQty.toFixed(3).replace('.', ',');
        newRow.querySelectorAll('.editable').forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.add('hidden');
        });

        newRow.dataset.batchKey = `split-${Date.now()}-${Math.floor(Math.random()*1000)}`;
        newRow.dataset.adjustmentId = null;
        newRow.dataset.changed = 'true';

        row.after(newRow);
        initRowEvents(newRow);
        updateRowNumbers();
        updateTotalValue();

        // Przygotowanie danych do wysyłki AJAX
        const dataToSend = [
            {
                product_id: newRow.dataset.productId,
                product_name: newRow.cells[1].textContent,
                barcode: newRow.cells[2].textContent,
                unit: newRow.cells[3].textContent,
                quantity: parseDecimal(newQtyInput.value),
                unit_price: parseDecimal(newPriceInput.value),
                batch_key: newRow.dataset.batchKey,
                adjustment_id: null
            },
            {
                product_id: row.dataset.productId,
                product_name: row.cells[1].textContent,
                barcode: row.cells[2].textContent,
                unit: row.cells[3].textContent,
                quantity: parseDecimal(quantityInput.value),
                unit_price: parseDecimal(row.querySelector('input[name*="[unit_price]"]').value),
                batch_key: row.dataset.batchKey,
                adjustment_id: row.dataset.adjustmentId
            }
        ];

        // AJAX POST do storeAdjustments
        fetch(`{{ route('stocktakings.adjustments.store', $regionStocktaking->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ products: dataToSend })
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                console.log('Podział zapisany w bazie');
            } else {
                alert('Błąd zapisu podziału');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Błąd połączenia z serwerem');
        });
    });



}

document.querySelectorAll('.product-row').forEach(row => initRowEvents(row));
updateRowNumbers();
updateTotalValue();

document.getElementById('stocktaking-form').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('.product-row');
    const productsToSubmit = [];

    rows.forEach(row => {
        const qtyInput = row.querySelector('input[name*="[quantity]"]').value.replace(',', '.');
        const priceInput = row.querySelector('input[name*="[unit_price]"]').value.replace(',', '.');

        const qty = parseFloat(qtyInput) || 0;
        const price = parseFloat(priceInput) || 0;

        if (row.dataset.changed === 'true') {
            productsToSubmit.push({
                product_id: row.dataset.productId,
                product_name: row.cells[1].textContent,
                barcode: row.cells[2].textContent,
                unit: row.cells[3].textContent,
                quantity: qty,
                unit_price: price,
                batch_key: row.dataset.batchKey,
                adjustment_id: row.dataset.adjustmentId,
            });
        }
    });

    if (productsToSubmit.length === 0) {
        e.preventDefault();
        alert('Nie wprowadzono żadnych zmian.');
        return;
    }

    document.querySelectorAll('input[name*="products"]').forEach(input => input.remove());

    productsToSubmit.forEach((p, i) => {
        for (const key in p) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = `products[${i}][${key}]`;
            hidden.value = p[key];
            this.appendChild(hidden);
        }
    });
});
</script>
</x-layout>
