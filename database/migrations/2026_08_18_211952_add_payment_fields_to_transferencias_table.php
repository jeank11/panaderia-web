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
        // Los campos de transferencia ya existen
        // en la tabla transferencias.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacemos cambios porque los campos
        // ya pertenecen a la estructura existente.
    }
};




