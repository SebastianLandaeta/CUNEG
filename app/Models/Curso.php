<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion', 'f_inicio', 'f_finalizacion', 'horas_academicas'];

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'curso_participantes', 'curso_id', 'participante_id')
                    ->withPivot('rol'); 
    }

    public function cursoParticipantes()
    {
        return $this->hasMany(CursoParticipante::class, 'curso_id', 'id');
    }
    

    public function certificado()
    {
        return $this->hasOne(CursoCertificado::class, 'idCurso');
    }
}
