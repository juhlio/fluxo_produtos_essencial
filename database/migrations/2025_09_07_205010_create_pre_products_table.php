<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pre_products')) {
            Schema::create('pre_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_request_id')
                      ->constrained('product_requests')
                      ->cascadeOnDelete();

                $table->string('descricao');
                $table->string('unidade')->nullable();
                $table->string('marca')->nullable();
                $table->string('modelo')->nullable();
                $table->string('sku')->nullable();
                $table->string('familia')->nullable();
                $table->decimal('peso', 12, 3)->nullable();
                $table->string('dimensoes')->nullable();

                $table->string('conta_contabil')->nullable();
                $table->string('natureza')->nullable();
                $table->string('centro_custo_padrao')->nullable();

                $table->string('ncm', 10)->nullable();
                $table->string('cest', 10)->nullable();
                $table->string('origem', 2)->nullable();
                $table->string('cfop_entrada', 5)->nullable();
                $table->string('cfop_saida', 5)->nullable();
                $table->string('cst_icms', 3)->nullable();
                $table->string('csosn', 3)->nullable();
                $table->decimal('aliq_icms', 5, 2)->nullable();
                $table->decimal('aliq_ipi', 5, 2)->nullable();
                $table->string('cst_pis', 2)->nullable();
                $table->decimal('aliq_pis', 5, 2)->nullable();
                $table->string('cst_cofins', 2)->nullable();
                $table->decimal('aliq_cofins', 5, 2)->nullable();
                $table->boolean('tem_st')->default(false);
                $table->decimal('mva_st', 6, 2)->nullable();
                $table->string('cod_servico_municipal', 20)->nullable();
                $table->decimal('aliq_iss', 5, 2)->nullable();
                $table->boolean('retencao_iss')->default(false);
                $table->json('fiscal_rules')->nullable();

                $table->timestamps();
            });
        } else {
            // Se precisar, aqui você acrescenta colunas que faltem.
            // Exemplo:
            Schema::table('pre_products', function (Blueprint $table) {
                if (!Schema::hasColumn('pre_products', 'fiscal_rules')) {
                    $table->json('fiscal_rules')->nullable()->after('retencao_iss');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_products');
    }
};
