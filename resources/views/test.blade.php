<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>QR Code Scanner</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        #reader {
            width: 300px;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1>QR Scanner</h1>
    <div id="reader"></div>
    <p id="scan-result">Oczekiwanie na skan...</p>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scanner = new Html5Qrcode("reader");

            function onScanSuccess(decodedText) {
                document.getElementById('scan-result').innerText = `Scanned text: ${decodedText}`;
            }

            Html5Qrcode.getCameras().then(devices => {
                if (!devices.length) {
                    alert("Nie znaleziono kamer.");
                    return;
                }

                scanner.start(
                    { facingMode: "environment" }, // kamera tylna
                    { fps: 10, qrbox: 250 },
                    onScanSuccess
                ).catch(err => alert("Błąd uruchamiania skanera: " + err));
            }).catch(err => alert("Błąd pobierania kamer: " + err));
        });
    </script>
</body>
</html>
