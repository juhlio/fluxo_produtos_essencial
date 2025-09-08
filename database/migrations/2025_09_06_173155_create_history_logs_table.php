<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('history_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_request_id')->constrained('product_requests')->cascadeOnDelete();
            $t->foreignId('actor_id')->constrained('users');
            $t->string('action', 60);
            $t->string('from_status', 40)->nullable();
            $t->string('to_status', 40)->nullable();
            $t->text('message')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('history_logs'); }
};
