<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoParticipante extends Model
{
    use \Awobaz\Compoships\Compoships;

    protected $table = 'curso_participantes';

    protected $fillable = ['curso_id', 'participante_tipo_documento', 'participante_numero_documento', 'rol'];

    public $timestamps = false;

    public function participante()
    {
        return $this->belongsTo(Participante::class, ['participante_tipo_documento', 'participante_numero_documento'], ['tipo_documento', 'numero_documento']);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
