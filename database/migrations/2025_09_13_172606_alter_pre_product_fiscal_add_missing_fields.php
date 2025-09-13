<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pre_product_fiscal', function (Blueprint $table) {
            // renomeia para bater com o form (se quiser manter o nome antigo no form, pule isto)
            if (Schema::hasColumn('pre_product_fiscal', 'cod_serv_iss')
                && !Schema::hasColumn('pre_product_fiscal', 'cod_servico_municipal')) {
                // se precisar de doctrine/dbal: composer require doctrine/dbal
                $table->renameColumn('cod_serv_iss', 'cod_servico_municipal');
            }

            // campos que a tela usa e que não existem hoje
            if (!Schema::hasColumn('pre_product_fiscal', 'cfop_entrada')) {
                $table->string('cfop_entrada', 5)->nullable()->after('origem');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'cfop_saida')) {
                $table->string('cfop_saida', 5)->nullable()->after('cfop_entrada');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'cst_icms')) {
                $table->string('cst_icms', 3)->nullable()->after('cfop_saida');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'csosn')) {
                $table->string('csosn', 3)->nullable()->after('cst_icms');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'cst_pis')) {
                $table->string('cst_pis', 2)->nullable()->after('csosn');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'aliq_pis')) {
                $table->decimal('aliq_pis', 8, 2)->nullable()->after('cst_pis');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'cst_cofins')) {
                $table->string('cst_cofins', 2)->nullable()->after('aliq_pis');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'aliq_cofins')) {
                $table->decimal('aliq_cofins', 8, 2)->nullable()->after('cst_cofins');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'mva_st')) {
                $table->decimal('mva_st', 8, 2)->nullable()->after('aliq_cofins');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'tem_st')) {
                $table->boolean('tem_st')->default(false)->after('mva_st');
            }
            if (!Schema::hasColumn('pre_product_fiscal', 'retencao_iss')) {
                $table->boolean('retencao_iss')->default(false)->after('aliq_iss');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_product_fiscal', function (Blueprint $table) {
            foreach ([
                'cfop_entrada','cfop_saida','cst_icms','csosn',
                'cst_pis','aliq_pis','cst_cofins','aliq_cofins',
                'mva_st','tem_st','retencao_iss'
            ] as $col) {
                if (Schema::hasColumn('pre_product_fiscal', $col)) $table->dropColumn($col);
            }
            // (não faço o rename de volta para simplificar)
        });
    }
};
