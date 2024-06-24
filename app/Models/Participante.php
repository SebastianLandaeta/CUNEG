<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre',
        'apellido',
        'email'
    ];

    protected $primaryKey = 'id'; // Establece la clave primaria en la columna 'id'
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_participantes', 'participante_id', 'curso_id')
                    ->withPivot('rol');
    }

    
}
