<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantesImport implements ToModel, WithHeadingRow
{
    private $participantes = []; // Array para almacenar los participantes

    public function model(array $row)
    {
        // Aplicar trim a todos los valores relevantes
        $participante = [
            'tipo_documento' => trim($row['tipo_de_documento']),
            'numero_documento' => trim($row['numero_de_documento']),
            'nombre' => trim($row['nombre']),
            'apellido' => trim($row['apellido']),
            'email' => trim($row['email']),
            'rol' => trim($row['rol']),
        ];

        // Añadir validación para evitar la creación de participantes sin documentos
        if (!is_null($participante['tipo_documento']) && !is_null($participante['numero_documento'])) {
            $this->participantes[] = $participante;
        }
    }

    public function getParticipantes()
    {
        return $this->participantes;
    }
}
