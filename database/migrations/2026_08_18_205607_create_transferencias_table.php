
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
        Schema::create('transferencias', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------
            | Cliente que realizó la transferencia
            |--------------------------------------------------------------
            */

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');


            /*
            |--------------------------------------------------------------
            | Datos de la transferencia
            |--------------------------------------------------------------
            */

            $table->decimal('monto', 10, 2);

            $table->date('fecha_transferencia');

            $table->string('referencia', 100);


            /*
            |--------------------------------------------------------------
            | Comprobante
            |--------------------------------------------------------------
            */

            $table->string('comprobante')->nullable();


            /*
            |--------------------------------------------------------------
            | Estado de la solicitud
            |--------------------------------------------------------------
            |
            | pendiente = esperando revisión del administrador
            | aprobado  = pago confirmado
            | rechazado = transferencia rechazada
            |
            */

            $table->enum('estado', [
                'pendiente',
                'aprobado',
                'rechazado'
            ])->default('pendiente');


            /*
            |--------------------------------------------------------------
            | Observación del administrador
            |--------------------------------------------------------------
            */

            $table->text('observacion')->nullable();


            /*
            |--------------------------------------------------------------
            | Fecha de revisión
            |--------------------------------------------------------------
            */

            $table->timestamp('fecha_revision')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferencias');
    }
};

