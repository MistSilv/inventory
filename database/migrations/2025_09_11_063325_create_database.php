<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::table('units')->insert([
            ['code' => 'szt', 'name' => 'sztuka', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'kg',  'name' => 'kilogram', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'l',   'name' => 'litr', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'opak','name' => 'opakowanie', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Products table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('id_abaco')->nullable()->index();
            $table->string('name', 255)->collation('Latin1_General_100_CI_AS_SC_UTF8');
            $table->decimal('price', 15, 2)->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete();
        });

        // EAN codes table
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('barcode', 13);
            $table->timestamps();
        });

        // Regions table
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::table('regions')->insert([
            ['code' => 'magazyn', 'name' => 'Magazyn', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'sklep', 'name' => 'Sklep', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'garmaz', 'name' => 'Garmaż', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'piekarnia', 'name' => 'Piekarnia', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Stocktakings
        Schema::create('stocktakings', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['draft','in_progress','closed','under_revision','signed_off'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->foreignId('signed_off_by')->nullable()->constrained('users')->onDelete('NO ACTION');
            $table->timestamps();
        });

        // Region Stocktakings
        Schema::create('region_stocktakings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stocktaking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->constrained();
            $table->timestamps();
        });

        // Physical Census
        Schema::create('physical_censuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_stocktaking_id')->constrained()->cascadeOnDelete();
            $table->string('location')->nullable(); // e.g., "Freezer A", "Shelf 3"
            $table->timestamps();
        });

        // Physical Census Products (pivot table)
        Schema::create('physical_census_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('physical_census_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->decimal('quantity', 15, 3)->default(0);
            $table->decimal('price', 15, 2)->nullable(); // optional
            $table->timestamps();
        });

       // Accountant Adjustments
        Schema::create('stocktaking_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_stocktaking_id')->constrained()->cascadeOnDelete();

            // zamiast tylko product_id, przechowujemy wszystkie dane
            $table->unsignedBigInteger('product_id')->nullable(); // zachowujemy referencję
            $table->string('product_name');  // Towar
            $table->string('barcode')->nullable(); // Barcode
            $table->string('unit')->nullable();    // Jm
            $table->decimal('quantity', 15, 3);    // Ilość
            $table->decimal('unit_price', 15, 2)->nullable(); // Cena jedn.
            
            $table->foreignId('adjusted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });


        Schema::create('stocktaking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // who did it
            $table->string('action'); // e.g., 'created_census', 'updated_quantity', 'adjusted_unit'
            $table->string('entity_type')->nullable(); // e.g., 'physical_census', 'product', 'adjustment'
            $table->unsignedBigInteger('entity_id')->nullable(); // the ID of the affected row
            $table->text('details')->nullable(); // JSON or plain text with extra info
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocktaking_adjustments');
        Schema::dropIfExists('physical_census_products');
        Schema::dropIfExists('physical_censuses');
        Schema::dropIfExists('region_stocktakings');
        Schema::dropIfExists('stocktakings');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('barcodes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('units');
        Schema::dropIfExists('stocktaking_logs');
    }
};
