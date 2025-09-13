<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pre_product_basics', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_product_basics', 'marca')) {
                $table->string('marca', 120)->nullable()->after('unidade');
            }
            if (!Schema::hasColumn('pre_product_basics', 'modelo')) {
                $table->string('modelo', 120)->nullable()->after('marca');
            }
            if (!Schema::hasColumn('pre_product_basics', 'familia')) {
                $table->string('familia', 120)->nullable()->after('sku');
            }
            if (!Schema::hasColumn('pre_product_basics', 'peso')) {
                $table->decimal('peso', 12, 4)->nullable()->after('familia');
            }
            if (!Schema::hasColumn('pre_product_basics', 'dimensoes')) {
                $table->string('dimensoes', 60)->nullable()->after('peso');
            }
            if (!Schema::hasColumn('pre_product_basics', 'codigo')) {
                $table->string('codigo', 60)->nullable()->after('dimensoes');
                $table->index('codigo');
            }
            if (!Schema::hasColumn('pre_product_basics', 'tipo')) {
                $table->string('tipo', 10)->nullable()->after('codigo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_product_basics', function (Blueprint $table) {
            if (Schema::hasColumn('pre_product_basics', 'tipo')) $table->dropColumn('tipo');
            if (Schema::hasColumn('pre_product_basics', 'codigo')) {
                $table->dropIndex(['codigo']);
                $table->dropColumn('codigo');
            }
            if (Schema::hasColumn('pre_product_basics', 'dimensoes')) $table->dropColumn('dimensoes');
            if (Schema::hasColumn('pre_product_basics', 'peso')) $table->dropColumn('peso');
            if (Schema::hasColumn('pre_product_basics', 'familia')) $table->dropColumn('familia');
            if (Schema::hasColumn('pre_product_basics', 'modelo')) $table->dropColumn('modelo');
            if (Schema::hasColumn('pre_product_basics', 'marca')) $table->dropColumn('marca');
        });
    }
};
