<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $primaryKey = 'cedula';
    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'email',
    ];
    
    public $timestamps = false;

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cursoParticipantes', 'participante_fk', 'curso_fk');
    }

    public function cursoParticipantes()
    {
        return $this->hasMany(CursoParticipante::class, 'participante_fk', 'cedula');
    }
}
