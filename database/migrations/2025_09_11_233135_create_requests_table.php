<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requested_by_id')->constrained('users');
            $table->string('status', 30)->default('RASCUNHO');
            $table->string('current_sector', 30)->default('SOLICITANTE');

            // Campos livres do cabeçalho (se quiser manter aqui também)
            $table->string('descricao')->nullable();
            $table->string('unidade',10)->nullable();
            $table->string('marca',100)->nullable();
            $table->string('modelo',100)->nullable();
            $table->string('sku',100)->nullable();
            $table->string('familia',100)->nullable();
            $table->string('peso',50)->nullable();
            $table->string('dimensoes',100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
