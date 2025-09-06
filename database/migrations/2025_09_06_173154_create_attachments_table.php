<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attachments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_request_id')->constrained('product_requests')->cascadeOnDelete();
            $t->string('original_name');
            $t->string('path');
            $t->string('type', 40)->nullable(); // manual, foto, etc.
            $t->foreignId('uploaded_by_id')->constrained('users');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('attachments'); }
};
