
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('requests')) {
            Schema::create('requests', function (Blueprint $table) {
                $table->id();
                $table->string('descricao');
                $table->string('unidade')->nullable();
                $table->string('marca')->nullable();
                $table->string('modelo')->nullable();
                $table->string('sku')->nullable();
                $table->string('familia')->nullable();
                $table->decimal('peso', 12, 4)->nullable();
                $table->string('dimensoes')->nullable();
                $table->json('extras')->nullable(); // todos os demais campos do formulário
                $table->timestamps();
            });
            return;
        }

        // Se a tabela já existe, garante as colunas necessárias
        Schema::table('requests', function (Blueprint $table) {
            if (!Schema::hasColumn('requests', 'unidade'))   $table->string('unidade')->nullable()->after('descricao');
            if (!Schema::hasColumn('requests', 'marca'))     $table->string('marca')->nullable()->after('unidade');
            if (!Schema::hasColumn('requests', 'modelo'))    $table->string('modelo')->nullable()->after('marca');
            if (!Schema::hasColumn('requests', 'sku'))       $table->string('sku')->nullable()->after('modelo');
            if (!Schema::hasColumn('requests', 'familia'))   $table->string('familia')->nullable()->after('sku');
            if (!Schema::hasColumn('requests', 'peso'))      $table->decimal('peso', 12, 4)->nullable()->after('familia');
            if (!Schema::hasColumn('requests', 'dimensoes')) $table->string('dimensoes')->nullable()->after('peso');
            if (!Schema::hasColumn('requests', 'extras'))    $table->json('extras')->nullable()->after('dimensoes');
        });
    }

    public function down(): void
    {
        // Remover apenas a coluna JSON adicionada agora (seguro para rollback)
        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                if (Schema::hasColumn('requests', 'extras')) {
                    $table->dropColumn('extras');
                }
            });
        }
    }
};
