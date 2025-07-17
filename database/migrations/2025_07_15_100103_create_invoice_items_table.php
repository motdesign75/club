<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('quantity', 8, 2)->default(1);
            $table->string('unit')->nullable(); // z. B. Stück, Stunde, Monat
            $table->decimal('unit_price', 10, 2);
            $table->decimal('tax_rate', 4, 2)->default(0.00); // z. B. 19.00
            $table->decimal('discount', 6, 2)->nullable(); // optional: Rabatt in %
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
