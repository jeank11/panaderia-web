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
    Schema::table('ventas', function (Blueprint $table) {

        $table->enum('tipo_pago', [
            'contado',
            'fiado'
        ])->default('contado')->after('total');

        $table->enum('estado_pago', [
            'pagada',
            'pendiente',
            'parcial'
        ])->default('pagada')->after('tipo_pago');

        $table->decimal('saldo_pendiente', 10, 2)
              ->default(0)
              ->after('estado_pago');

    });
}

public function down(): void
{
    Schema::table('ventas', function (Blueprint $table) {

        $table->dropColumn([
            'tipo_pago',
            'estado_pago',
            'saldo_pendiente'
        ]);

    });
}
};
