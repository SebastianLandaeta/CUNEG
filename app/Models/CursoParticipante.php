<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoParticipante extends Model
{
    use HasFactory;

    protected $table = "cursosparticipantes";

    protected $fillable = ['rol'];
    public $timestamps = false;

    // Define la relación con la tabla 'participantes'
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_fk', 'cedula');
    }

    // Define la relación con la tabla 'cursos'
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_fk', 'id');
    }
}

