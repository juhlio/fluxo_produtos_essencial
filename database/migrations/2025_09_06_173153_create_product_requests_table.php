<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('requested_by_id')->constrained('users');
            $t->string('status', 40)->default('RASCUNHO');
            $t->string('current_sector', 30)->default('SOLICITANTE');
            $t->string('erp_product_code')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('product_requests'); }
};
