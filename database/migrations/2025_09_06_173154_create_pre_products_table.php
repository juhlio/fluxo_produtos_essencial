<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pre_products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_request_id')->constrained('product_requests')->cascadeOnDelete();

            // Cadastrais
            $t->string('descricao');
            $t->string('unidade', 10)->nullable();
            $t->string('marca')->nullable();
            $t->string('modelo')->nullable();
            $t->string('sku')->nullable();
            $t->string('familia')->nullable();
            $t->decimal('peso', 10, 3)->nullable();
            $t->string('dimensoes')->nullable();

            // Contábil
            $t->string('conta_contabil')->nullable();
            $t->string('natureza')->nullable();
            $t->string('centro_custo_padrao')->nullable();

            // Fiscal
            $t->string('ncm', 10)->nullable();
            $t->string('cest', 10)->nullable();
            $t->string('origem', 5)->nullable(); // 0..8
            $t->string('cfop_entrada', 10)->nullable();
            $t->string('cfop_saida', 10)->nullable();
            $t->string('cst_icms', 5)->nullable();
            $t->string('csosn', 5)->nullable();
            $t->decimal('aliq_icms', 5, 2)->nullable();
            $t->decimal('aliq_ipi', 5, 2)->nullable();
            $t->string('cst_pis', 5)->nullable();
            $t->decimal('aliq_pis', 5, 2)->nullable();
            $t->string('cst_cofins', 5)->nullable();
            $t->decimal('aliq_cofins', 5, 2)->nullable();
            $t->boolean('tem_st')->default(false);
            $t->decimal('mva_st', 6, 2)->nullable();

            // Prefeitura/ISS
            $t->string('cod_servico_municipal')->nullable();
            $t->decimal('aliq_iss', 5, 2)->nullable();
            $t->boolean('retencao_iss')->default(false);

            $t->json('fiscal_rules')->nullable();

            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pre_products'); }
};
