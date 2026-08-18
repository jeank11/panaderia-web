<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar migración.
     */
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {

            $table->enum('tipo_pago', [
                'contado',
                'fiado'
            ])->default('contado')->after('total');

        });
    }

    /**
     * Revertir migración.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {

            $table->dropColumn('tipo_pago');

        });
    }
};