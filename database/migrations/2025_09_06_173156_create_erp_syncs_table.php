<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('erp_syncs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_request_id')->constrained('product_requests')->cascadeOnDelete();
            $t->string('type', 30); // create_product | update_fiscal | reconcile
            $t->longText('payload')->nullable();
            $t->longText('response')->nullable();
            $t->string('status', 20)->default('PENDING'); // PENDING|OK|ERROR
            $t->unsignedTinyInteger('retries')->default(0);
            $t->text('last_error')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('erp_syncs'); }
};
