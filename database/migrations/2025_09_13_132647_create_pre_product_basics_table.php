<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pre_product_basics', function (Blueprint $table) {
            $table->id();

            // FK para pre_products (1-para-1)
            $table->unsignedBigInteger('pre_product_id');
            $table->foreign('pre_product_id')
                ->references('id')->on('pre_products')
                ->onDelete('cascade');

            // Campos "básicos" (use os que fizerem sentido)
            $table->string('descricao', 255)->nullable();
            $table->string('unidade', 50)->nullable();
            $table->string('sku', 120)->nullable();

            $table->timestamps();

            // Garante relação 1-para-1
            $table->unique('pre_product_id');
            $table->index(['sku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_product_basics');
    }
};
