<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pre_product_operational', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_product_id')->unique()->constrained('pre_products')->cascadeOnDelete();

            $table->string('armazem_padrao',50)->nullable();
            $table->string('grupo',50)->nullable();
            $table->string('seg_un_medi',10)->nullable();
            $table->string('te_padrao',10)->nullable();
            $table->string('ts_padrao',10)->nullable();
            $table->string('fator_conv',50)->nullable();
            $table->enum('tipo_conv',['M','D'])->nullable();
            $table->string('alternativo',100)->nullable();
            $table->integer('base_estrut')->nullable();
            $table->string('fornecedor_padrao',100)->nullable();
            $table->string('loja_padrao',100)->nullable();
            $table->enum('rastro',['S','N'])->nullable();

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pre_product_operational');
    }
};
