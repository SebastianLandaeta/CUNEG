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
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();// ID único
            $table->string('tipo_documento'); 
            $table->string('numero_documento'); 
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->unique(['tipo_documento', 'numero_documento']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
