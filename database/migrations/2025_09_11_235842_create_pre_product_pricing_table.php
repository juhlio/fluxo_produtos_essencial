<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pre_product_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_product_id')->unique()->constrained('pre_products')->cascadeOnDelete();

            $table->string('preco_venda',50)->nullable();
            $table->string('custo_stand',50)->nullable();
            $table->string('moeda_cstd',10)->nullable();
            $table->date('ult_calculo')->nullable();
            $table->string('ult_preco',50)->nullable();
            $table->date('ult_compra')->nullable();
            $table->string('peso_liquido',50)->nullable();
            $table->date('ult_revisao')->nullable();
            $table->date('dt_referenc')->nullable();
            $table->string('apropriacao',100)->nullable();
            $table->string('cta_contabil',50)->nullable();
            $table->string('centro_custo',50)->nullable();
            $table->string('item_conta',50)->nullable();
            $table->string('perc_comissao',50)->nullable();
            $table->string('cod_barras',100)->nullable();

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pre_product_pricing');
    }
};
