<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pre_product_fiscal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_product_id')->unique()->constrained('pre_products')->cascadeOnDelete();

            $table->string('ncm',20)->nullable();
            $table->string('cest',20)->nullable();
            $table->string('pos_ipi_ncm',50)->nullable();
            $table->string('ex_nbm',50)->nullable();
            $table->string('ex_ncm',50)->nullable();
            $table->string('especie_tipi',50)->nullable();
            $table->string('cod_serv_iss',50)->nullable();
            $table->string('origem',20)->nullable();
            $table->string('class_fiscal',50)->nullable();
            $table->string('grupo_trib',50)->nullable();
            $table->string('cont_seg_soc',50)->nullable();
            $table->string('imposto_renda',50)->nullable();
            $table->enum('calcula_inss',['S','N'])->nullable();

            // Aliquotas e reduções
            $table->string('aliq_icms',50)->nullable();
            $table->string('aliq_ipi',50)->nullable();
            $table->string('aliq_iss',50)->nullable();
            $table->string('red_inss',50)->nullable();
            $table->string('red_irrf',50)->nullable();
            $table->string('red_pis',50)->nullable();
            $table->string('red_cofins',50)->nullable();
            $table->string('perc_pis',50)->nullable();
            $table->string('perc_cofins',50)->nullable();
            $table->string('perc_csll',50)->nullable();
            $table->string('proprio_icms',50)->nullable();
            $table->string('icms_pauta',50)->nullable();
            $table->string('ipi_pauta',50)->nullable();
            $table->string('aliq_famad',50)->nullable();
            $table->string('aliq_fecp',50)->nullable();
            $table->string('solid_saida',50)->nullable();
            $table->string('solid_entrada',50)->nullable();
            $table->string('imp_zfranca',50)->nullable();

            // Flags
            $table->boolean('tem_st')->nullable();
            $table->boolean('retencao_iss')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pre_product_fiscal');
    }
};
