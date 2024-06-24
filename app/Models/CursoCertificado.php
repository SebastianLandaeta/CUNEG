<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curso;

class CursoCertificado extends Model
{
    use HasFactory;
    protected $table = 'cursoCertificados';
    protected $fillable = ['idCurso', 'data'];
    public $timestamps = false;
    
    // Definir la relación con el modelo Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'idCurso');
    }

}
