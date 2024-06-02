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
        Schema::create('servicio_comunitario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_servicio');
            $table->string('lugar');
            $table->integer('impacto_social_hombres');
            $table->integer('impacto_social_mujeres');
            $table->integer('impacto_social_discapacitados');
            $table->integer('impacto_social_adultos_mayores');
            $table->integer('impacto_social_ninos');
            
            // Asesor académico referencia a participantes
            $table->string('asesor_academico_tipo_documento');
            $table->string('asesor_academico_numero_documento');

            // Asesor comunitario referencia a participantes
            $table->string('asesor_comunitario_tipo_documento');
            $table->string('asesor_comunitario_numero_documento');
            
            // Definir las claves foráneas
            $table->foreign(['asesor_academico_tipo_documento', 'asesor_academico_numero_documento'])
                ->references(['tipo_documento', 'numero_documento'])
                ->on('participantes')
                ->onDelete('cascade');

            $table->foreign(['asesor_comunitario_tipo_documento', 'asesor_comunitario_numero_documento'])
                ->references(['tipo_documento', 'numero_documento'])
                ->on('participantes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_comunitario');
    }
};
