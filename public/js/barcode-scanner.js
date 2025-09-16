document.addEventListener('DOMContentLoaded', function () {
    const scanner = new Html5Qrcode("reader");
    let isScanning = false;

    const startBtn = document.getElementById('start-scan');
    const stopBtn = document.getElementById('stop-scan');

    const tableBody = document.querySelector('#products-table tbody');

    // Add product row function (same as before)
    function addProductRow(productId, productName, quantity, unit, price) {
        const index = tableBody.children.length;
        const step = (unit === 'szt' || unit === 'opak') ? 1 : 0.001;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-2">
                ${productName} (${parseFloat(price).toFixed(2)} zł)
                <input type="hidden" name="products[${index}][id]" value="${productId}">
                <input type="hidden" name="products[${index}][price]" value="${price}">
            </td>
            <td class="px-4 py-2">
                <input type="number" step="${step}" min="0"
                       name="products[${index}][quantity]" class="border rounded px-2 py-1 w-24" value="${quantity}">
            </td>
            <td class="px-4 py-2">${unit}</td>
            <td class="px-4 py-2">
                <button type="button" class="remove-btn bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Remove</button>
            </td>
        `;
        tableBody.appendChild(row);

        row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
    }

    // Handle scanning result
    async function onScanSuccess(decodedText) {
        document.getElementById('scan-result').innerText = `Scanned: ${decodedText}`;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const res = await fetch('/api/Barcode_check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ barcode: decodedText })
            });

            const data = await res.json();
            if (!res.ok || !data.product) {
                alert(data.message || "Produkt nie znaleziony");
                return;
            }

            const product = data.product;
            const qty = prompt(`Enter quantity for product: ${product.name}`, "1");

            if (qty && !isNaN(qty) && parseFloat(qty) > 0) {
                addProductRow(
                    product.id.toString(),
                    product.name,
                    parseFloat(qty),
                    product.unit || '',
                    product.price || 0
                );
            } else {
                alert("Invalid quantity.");
            }

        } catch (err) {
            alert(err.message || "Error checking barcode.");
        }
    }

    // Start scanning
    startBtn.addEventListener('click', () => {
        if (isScanning) return;

        Html5Qrcode.getCameras().then(devices => {
            if (!devices.length) {
                alert("No cameras found.");
                return;
            }

            document.getElementById('reader').style.display = 'block';
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');

            scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                onScanSuccess
            ).then(() => {
                isScanning = true;
            }).catch(err => alert("Error starting scanner: " + err));

        }).catch(err => alert("Error getting cameras: " + err));
    });

    // Stop scanning
    stopBtn.addEventListener('click', () => {
        if (!isScanning) return;

        scanner.stop().then(() => {
            isScanning = false;
            document.getElementById('reader').style.display = 'none';
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
            document.getElementById('scan-result').innerText = "Scanning stopped.";
        }).catch(err => alert("Error stopping scanner: " + err));
    });
});
