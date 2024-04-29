<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion', 'f_inicio', 'f_finalizacion', "lista_cargada", "certificado_cargado"];

    protected $attributes = [
        'lista_cargada' => false,
        'certificado_cargado' => false
    ];

    public function cursoParticipantes()
    {
        return $this->hasMany(CursoParticipante::class, 'curso_fk', 'id');
    }

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'cursoParticipantes', 'curso_fk', 'participante_fk');
    }

    public function certificado()
    {
        return $this->hasOne(CursoCertificado::class, 'idCurso');
    }
}
