<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                // FK para users e roles (ajuste o nome da tabela se o seu model Role usar outro)
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

                // Chave primária composta evita duplicidade
                $table->primary(['user_id', 'role_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
