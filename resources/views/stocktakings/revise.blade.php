<x-layout title="Przegląd Spisu – {{ $regionStocktaking->region->name }}">

<div class="container mx-auto px-4">

    <h1 class="text-3xl font-bold mb-6 text-amber-400">
        Przegląd spisu – {{ $regionStocktaking->region->name }}
    </h1>

    <div class="mb-6 bg-gray-900 border-2 border-amber-700 rounded-xl p-4 shadow">
        <p><strong class="text-amber-400">Stocktaking:</strong> <span class="text-gray-200">{{ $regionStocktaking->stocktaking->title ?? '-' }}</span></p>
        <p><strong class="text-amber-400">Status:</strong> <span class="text-gray-200">{{ $regionStocktaking->stocktaking->status }}</span></p>
        <p><strong class="text-amber-400">Data:</strong> <span class="text-gray-200">{{ $regionStocktaking->stocktaking->date }}</span></p>
    </div>

    <form id="stocktaking-form" action="{{ route('stocktakings.adjustments.store', $regionStocktaking->id) }}" method="POST">
        @csrf
        <h2 class="text-2xl font-semibold mb-4 text-amber-300">Agregacja produktów</h2>

        <div class="overflow-x-auto">
            <table class="table-auto w-full border-separate border-spacing-y-2">
                <thead class="bg-black text-amber-400">
                    <tr>
                        <th class="px-4 py-2">Poz.</th>
                        <th class="px-4 py-2">Towar</th>
                        <th class="px-4 py-2">Barcode</th>
                        <th class="px-4 py-2">Jm</th>
                        <th class="px-4 py-2 text-right">Ilość</th>
                        <th class="px-4 py-2 text-right">Cena jedn.</th>
                        <th class="px-4 py-2 text-right">Wartość</th>
                        <th class="px-4 py-2 text-center">Akcje</th>
                    </tr>
                </thead>
                <tbody id="products-body">
                    @foreach($aggregatedProducts as $index => $row)
                        <tr class="bg-gray-900 text-gray-200 shadow rounded-xl product-row"
                            data-product-id="{{ $row['product_id'] }}"
                            data-batch-key="{{ $row['batch_key'] }}"
                            data-adjustment-id="{{ $row['adjustment_id'] }}">
                            <td class="px-4 py-2 border border-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border border-gray-700">{{ $row['product_name'] }}</td>
                            <td class="px-4 py-2 border border-gray-700">{{ $row['barcode'] }}</td>
                            <td class="px-4 py-2 border border-gray-700">{{ $row['unit'] }}</td>

                            <td class="px-4 py-2 border border-gray-700 text-right">
                                <span class="quantity-text">{{ $row['quantity'] }}</span>
                                <input type="text" name="products[{{ $index }}][quantity]" value="{{ $row['quantity'] }}" class="border px-2 py-1 w-20 text-right editable hidden bg-gray-800 text-gray-200">
                            </td>
                            <td class="px-4 py-2 border border-gray-700 text-right">
                                <span class="unit-price-text">{{ $row['unit_price'] }}</span>
                                <input type="text" name="products[{{ $index }}][unit_price]" value="{{ $row['unit_price'] }}" class="border px-2 py-1 w-20 text-right editable hidden bg-gray-800 text-gray-200">
                            </td>
                            <td class="px-4 py-2 border border-gray-700 text-right value-cell">{{ number_format($row['value'], 2, ',', ' ') }}</td>
                            <td class="px-4 py-2 border border-gray-700 text-center space-x-1">
                                <button type="button" class="edit-btn cursor-pointer bg-amber-900 text-black px-2 py-1 rounded hover:bg-amber-600">✏️</button>
                                <button type="button" class="save-btn cursor-pointer hidden bg-green-900 text-black px-2 py-1 rounded hover:bg-green-600">💾</button>
                                <button type="button" class="cancel-btn cursor-pointer hidden bg-red-900 text-black px-2 py-1 rounded hover:bg-red-600">❌</button>
                                <button type="button" class="split-btn cursor-pointer bg-blue-900 text-white px-2 py-1 rounded hover:bg-blue-600">➕ Podziel</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-black text-amber-300 font-bold">
                        <td colspan="6" class="text-right px-4 py-2">Łączna wartość:</td>
                        <td id="total-value" class="text-right px-4 py-2">0,00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-green-900 text-white rounded hover:bg-green-600 font-semibold shadow">
                Zapisz wszystkie zmiany
            </button>

            <button type="button" id="generate-report" class="px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-600 font-semibold shadow">
                📝 Generuj Spis z natury
            </button>
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
            input.classList.add('bg-transparent', 'text-white', 'border', 'border-amber-700', 'px-2', 'py-1', 'rounded');
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





<script>
document.getElementById('generate-report').addEventListener('click', function() {
    const owner = "Jarosławdis Sp. z o.o."; // Możesz dynamicznie pobrać z danych
    const date = new Date().toLocaleDateString('pl-PL');
    const rows = document.querySelectorAll('#products-body tr');

    let totalValue = 0;
    let tableRowsHtml = '';

    rows.forEach((row, index) => {
        const name = row.cells[1].textContent;
        const unit = row.cells[3].textContent;
        const qty = row.querySelector('input[name*="[quantity]"]').value || row.querySelector('.quantity-text').textContent;
        const price = row.querySelector('input[name*="[unit_price]"]').value || row.querySelector('.unit-price-text').textContent;
        const value = parseFloat(qty.replace(',', '.')) * parseFloat(price.replace(',', '.'));
        totalValue += value;

        tableRowsHtml += `<tr>
            <td>${index + 1}</td>
            <td>${name}</td>
            <td>${unit}</td>
            <td>${qty}</td>
            <td>${parseFloat(price).toFixed(2)}</td>
            <td>${value.toFixed(2)}</td>
        </tr>`;
    });

    const reportHtml = `
        <html>
        <head>
            <title>Spis z natury</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background-color: #f0f0f0; }
                h1, h2 { text-align: center; }
            </style>
        </head>
        <body>
            <h1>Spis z natury</h1>
            <p><strong>Firma/Właściciel:</strong> ${owner}</p>
            <p><strong>Data sporządzenia:</strong> ${date}</p>
            <table>
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Składnik remanentu</th>
                        <th>Jednostka miary</th>
                        <th>Ilość</th>
                        <th>Cena jednostkowa [PLN]</th>
                        <th>Wartość [PLN]</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRowsHtml}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><strong>Łączna wartość:</strong></td>
                        <td>${totalValue.toFixed(2)}</td>
                    </tr>
                </tfoot>
            </table>
            <p>Spis zakończono na pozycji ${rows.length}.</p>
            <p><strong>Podpisy osób sporządzających spis:</strong> ________________________</p>
            <p><strong>Podpis właściciela zakładu:</strong> ________________________</p>
        </body>
        </html>
    `;

    const newWindow = window.open('', '_blank');
    newWindow.document.write(reportHtml);
    newWindow.document.close();
});
</script>

</x-layout>
