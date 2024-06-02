<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ParticipantesImport implements ToModel, WithHeadingRow
{
    private $participantes = []; // Creamos un array para almacenar los participantes

    public function model(array $row)
    {
        if ($row['cedula'] > 0 || $row['cedula'] != null){
            $participante = [
                'cedula' => $row['cedula'],
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'email' => $row['email'],
                'rol' => $row['rol']
            ];

            $this->participantes[] = $participante;
        } 
    }

    public function getParticipantes()
    {
        return $this->participantes; 
    }

}

