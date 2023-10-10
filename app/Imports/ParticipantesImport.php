<?php

namespace App\Imports;

use App\Models\Curso;
use App\Models\Participante;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantesImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    private $cursoId;

    public function __construct($cursoId)
    {
        $this->cursoId = $cursoId;
    }
    public function model(array $row)
    {
            
        $cedula = intval($row['cedula']);

        if ($cedula>0){
            $existingParticipante = Participante::where('cedula', $cedula)->first();

            if (!$existingParticipante){
                $participante = new Participante([
                'cedula' => $cedula,
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'email' => $row['email'],
                'rol' => $row['rol']
            ]);
                
            $participante->save();

            }else{
                $participante = $existingParticipante;
            }

            $curso = Curso::find($this->cursoId);
            

            if ($curso) {
                $participante->cursos()->attach($curso);
            }
        }
    } 
}
