<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curso;

class CursoParticipante extends Model
{
    use HasFactory;

    protected $table = 'curso_participantes';

    protected $fillable = [
        'curso_id',
        'participante_id', // Actualizamos para usar la nueva clave primaria
        'rol'
    ];

    public $timestamps = false;

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
