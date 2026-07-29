<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->boolean('permite_fiado')
              ->default(false)
              ->after('estado');

        $table->decimal('limite_credito', 10, 2)
              ->default(0)
              ->after('permite_fiado');
    });
}

public function down(): void
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->dropColumn([
            'permite_fiado',
            'limite_credito'
        ]);
    });
}
};
