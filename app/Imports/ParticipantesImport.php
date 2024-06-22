<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantesImport implements ToModel, WithHeadingRow
{
    private $participantes = []; // Array para almacenar los participantes

    public function model(array $row)
    {
        
        $participante = [
            'tipo_documento' => $row['tipo_de_documento'],
            'numero_documento' => $row['numero_de_documento'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'email' => $row['email'] ,
            'rol' => $row['rol'] ,
        ];


        $this->participantes[] = $participante;

        // Añadir validación para evitar la creación de participantes sin documentos
        /*if (!is_null($participante['tipo_documento']) && !is_null($participante['numero_documento'])) {
            $this->participantes[] = $participante;
        }/*/
    }

    public function getParticipantes()
    {
        return $this->participantes; 
    }
}