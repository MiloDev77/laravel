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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_fr');
            $table->text('description_en');
            $table->text('description_fr');
            $table->string('gtin', 14)->unique()->index();
            $table->string('brand');
            $table->foreignId('category_id')->constrained(
                table: 'categories',
                indexName: 'products_category_id',
            )->restrictOnDelete();
            $table->foreignId('company_id')->constrained(
                table: 'companies',
                indexName: 'products_company_id',
            )->restrictOnDelete();
            $table->string('country')->default('France');
            $table->decimal('gross_weight', 8, 3);
            $table->decimal('net_weight', 8, 3);
            $table->string('unit_weight', 20);
            $table->string('image_path')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
