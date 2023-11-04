<?php

namespace App\Exports;

use App\Models\Curso;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantsExport implements FromCollection, WithHeadings, ShouldAutoSize,  WithCustomStartCell
{
    protected $cursoId;

    public function headings(): array
    {
        return [
            'cedula',
            'nombre',
            'apellido',
            'email',
            'rol'
        ];
    }

    public function startCell(): string
    {
        return 'B1';
    }

    public function __construct($cursoId)
    {
        $this->cursoId = $cursoId;
    }

    public function collection()
{
    $curso = Curso::find($this->cursoId);

    if ($curso) {
        $participantes = $curso->participantes;

        // Usar la función map para agregar la columna 'rol' de la tabla pivote 'cursoparticipantes'
        $participantes = $participantes->map(function ($participante) use ($curso) {
            $cursoParticipante = $participante->cursoparticipantes->where('curso_fk', $curso->id)->first();
            $participante->rol = $cursoParticipante ? $cursoParticipante->rol : 'N/A';

            return $participante;
        });

        return $participantes;
    }

    return collect(); // En caso de que el curso no se encuentre
}

}


