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
    Schema::create('clientes', function (Blueprint $table) {

        $table->id();

        $table->string('nombre',100);

        $table->string('apellido',100);

        $table->string('documento',20)->unique();

        $table->string('telefono',30);

        $table->string('email')->unique();

        $table->string('direccion')->nullable();

        $table->date('fecha_nacimiento')->nullable();

        $table->boolean('estado')->default(true);

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
