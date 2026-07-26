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
    Schema::create('pedidos', function (Blueprint $table) {

        $table->id();

        // Código visible del pedido
        $table->string('codigo')->unique();

        // Cliente
        $table->foreignId('cliente_id')
              ->constrained()
              ->cascadeOnDelete();

        // Fechas
        $table->date('fecha_pedido');

        $table->date('fecha_entrega');

        $table->time('hora_entrega');

        // retiro | domicilio
        $table->string('tipo_entrega');

        // Solo si es domicilio
        $table->string('direccion_entrega')->nullable();

        // Observaciones del cliente
        $table->text('observaciones')->nullable();

        // Total del pedido
        $table->decimal('total',10,2)->default(0);

        // Estado del pedido
        $table->string('estado')->default('Pendiente');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
