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
        'rol'
    ];
    public $timestamps = false;

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cursosparticipantes', 'participante_fk', 'curso_fk');
    }
}
