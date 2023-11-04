<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosParticipantesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cursosparticipantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_fk');
            $table->unsignedBigInteger('participante_fk');
            $table->string('rol'); 

            $table->foreign('curso_fk')->references('id')->on('cursos');
            $table->foreign('participante_fk')->references('cedula')->on('participantes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursosparticipantes');
    }
}
