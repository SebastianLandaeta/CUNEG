<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use \Awobaz\Compoships\Compoships;
    
    public $incrementing = false; // Las claves compuestas no son autoincrementales
    public $timestamps = false;
    protected $primaryKey = ['tipo_documento', 'numero_documento'];
    protected $keyType = 'string'; // Ajusta esto si tus claves son enteros

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre',
        'apellido',
        'email',
    ];

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_participantes', 'participante_numero_documento', 'curso_id')
                    ->withPivot('rol', 'participante_tipo_documento');
    }

    public function cursoParticipantes()
    {
        return $this->hasMany(CursoParticipante::class, ['participante_tipo_documento', 'participante_numero_documento'], ['tipo_documento', 'numero_documento']);
    }
}
