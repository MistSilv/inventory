<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChonkiBoi extends Seeder
{
    public function run()
    {
        $total = 200000;
        $batchSize = 400; // SQL Server limit 2100 parametrów
        $created = 0;

        // Wczytaj istniejące EAN-y z CSV
        $existingEans = [];
        $csvPath = database_path('db/products.csv');
        if (file_exists($csvPath) && ($handle = fopen($csvPath, 'r')) !== false) {
            fgetcsv($handle, 1000, ','); // nagłówki
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $existingEans[] = $data[0];
            }
            fclose($handle);
        }

        // Pobierz ID jednostek
        $unitIds = DB::table('units')->pluck('id')->toArray();

        while ($created < $total) {
            $productsBatch = [];
            $barcodesBatch = [];

            // Przygotuj batch produktów
            for ($i = 0; $i < $batchSize && $created < $total; $i++, $created++) {
                $name = 'Produkt ' . Str::random(8);
                $price = number_format(rand(100, 10000) / 100, 2, '.', ''); // 1.00 - 100.00
                $unitId = $unitIds[array_rand($unitIds)];

                $productsBatch[] = [
                    'name' => $name,
                    'price' => $price,
                    'unit_id' => $unitId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Wstaw produkty do tabeli
            DB::table('products')->insert($productsBatch);

            // Pobierz ID wstawionych produktów
            $lastId = DB::getPdo()->lastInsertId();
            $firstId = $lastId - count($productsBatch) + 1;
            $insertedIds = range($firstId, $lastId);

            // Generuj unikalne kody EAN dla batcha
            foreach ($insertedIds as $productId) {
                do {
                    $ean = '590' . rand(1000000000, 9999999999); // 13-cyfrowy EAN
                } while (in_array($ean, $existingEans));

                $existingEans[] = $ean;

                $barcodesBatch[] = [
                    'product_id' => $productId,
                    'barcode' => $ean,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Wstaw batch kodów EAN
            DB::table('barcodes')->insert($barcodesBatch);

            $this->command->info("Inserted batch of $batchSize products. Total created: $created");
        }
    }
}
