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
        Schema::create('curso_participantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_id');
            $table->string('participante_tipo_documento');
            $table->string('participante_numero_documento');
            $table->string('rol');
            
            // Definir las claves foráneas
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->foreign(['participante_tipo_documento', 'participante_numero_documento'])
            ->references(['tipo_documento', 'numero_documento'])
            ->on('participantes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_participantes');
    }
};

