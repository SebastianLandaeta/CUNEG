<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'f_inicio', 'f_finalizacion', "lista_cargada"];

    protected $attributes = [
        'lista_cargada' => false,
    ];

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'cursosparticipantes', 'curso_fk', 'participante_fk');
    }
}
