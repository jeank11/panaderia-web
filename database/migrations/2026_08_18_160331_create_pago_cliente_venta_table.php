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
        Schema::create('pago_cliente_venta', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pago_cliente_id')
                  ->constrained('pagos_cliente')
                  ->cascadeOnDelete();

            $table->foreignId('venta_id')
                  ->constrained('ventas')
                  ->cascadeOnDelete();

            $table->decimal('monto_aplicado', 10, 2);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_cliente_venta');
    }
};