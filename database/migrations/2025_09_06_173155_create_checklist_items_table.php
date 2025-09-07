<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_request_id')->constrained('product_requests')->cascadeOnDelete();
            $t->string('sector', 30); // ESTOQUE|FISCAL|CONTABIL
            $t->string('key', 60);    // ex: validar_ncm
            $t->foreignId('checked_by_id')->nullable()->constrained('users');
            $t->timestamp('checked_at')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('checklist_items'); }
};
