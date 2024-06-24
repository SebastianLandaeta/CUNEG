<?php

namespace App\Helpers;

use App\Imports\ParticipantesImport;
use App\Models\Curso;
use App\Models\Participante;
use App\Models\CursoParticipante;
use Maatwebsite\Excel\Facades\Excel;

class ProcesarParticipantes
{
    public static function importOfParticipantes($path)
    {
        $import = new ParticipantesImport;
        Excel::import($import, $path);
        return $import->getParticipantes();
    }

    public static function valid_tipo_documento($t_documento)
    {
        $documentos_validos = ['V','J','E','G'];
        return in_array($t_documento, $documentos_validos);
    }

    public static function valid_nro_documento($nro_documento)
    {
        return preg_match("/^[0-9]+$/", $nro_documento);
    }

    public static function valid_rol($rol)
    {
        return in_array($rol, ['Participante', 'Instructor', 'Facilitador']);
    }

    public static function verify_documento_repetido($tipoDocumento, $numeroDocumento, $participantes)
    {
        $documentoCount = 0;

        foreach ($participantes as $participante) {
            if ($participante['tipo_documento'] == $tipoDocumento && 
                $participante['numero_documento'] == $numeroDocumento) {
                $documentoCount++;
                if ($documentoCount >= 2) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function verify_email_repetido($email, $participantes)
    {
        $emailCount = 0;

        foreach ($participantes as $participante) {
            if ($participante['email'] == $email) {
                $emailCount++;
                if ($emailCount >= 2) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function validateParticipantes($participantes)
    {
        $validParticipants = [];
        $invalidParticipants = [];
        $duplicateParticipants = [];

        foreach ($participantes as $participante) {
            $isValid = self::valid_tipo_documento($participante['tipo_documento']) &&
                       self::valid_nro_documento($participante['numero_documento']) &&
                       self::valid_rol($participante['rol']) &&
                       self::verify_documento_repetido($participante['tipo_documento'], $participante['numero_documento'], $participantes) &&
                       self::verify_email_repetido($participante['email'], $participantes);

            if ($isValid) {
                $validParticipants[] = $participante;
            } else {
                $invalidParticipants[] = $participante;
            }
        }

        return [
            'valid' => $validParticipants,
            'invalid' => $invalidParticipants,
            'duplicates' => $duplicateParticipants
        ];
    }

    public static function saveParticipantes($participantes, Curso $curso)
    {
        $newParticipants = [];
        $updatedParticipants = [];
        $failedParticipants = [];

        // Carga de nuevos participantes
        foreach ($participantes as $participante) {
            $tipoDocumento = $participante['tipo_documento'];
            $numeroDocumento = $participante['numero_documento'];
            $rol = $participante['rol']; // Asegúrate de que el rol esté disponible aquí

            $existingParticipante = Participante::where('tipo_documento', $tipoDocumento)
                                                ->where('numero_documento', $numeroDocumento)
                                                ->first();

            if (!$existingParticipante) {
                $newParticipante = new Participante([
                    'tipo_documento' => $participante['tipo_documento'],
                    'numero_documento' => $participante['numero_documento'],
                    'nombre' => $participante['nombre'],
                    'apellido' => $participante['apellido'],
                    'email' => $participante['email']
                ]);

                if ($newParticipante->save()) {
                    $newParticipants[] = [
                        'data' => $newParticipante,
                        'rol' => $rol // Guarda el rol junto con el participante nuevo
                    ];

                    CursoParticipante::create([
                        'curso_id' => $curso->id,
                        'participante_id' => $newParticipante->id,
                        'rol' => $rol // Aquí se usa el rol almacenado
                    ]);
                } else {
                    $failedParticipants[] = [
                        'data' => $newParticipante,
                        'rol' => $rol // Guarda el rol junto con el participante que falló al guardar
                    ];
                }
            } else {
                $updated = true;
                $oldAttributes = $existingParticipante->getAttributes();
                $newAttributes = $participante;

                if ($existingParticipante->nombre !== $participante['nombre']) {
                    $existingParticipante->nombre = $participante['nombre'];
                    $updated = true;
                }

                if ($existingParticipante->apellido !== $participante['apellido']) {
                    $existingParticipante->apellido = $participante['apellido'];
                    $updated = true;
                }

                if ($existingParticipante->email !== $participante['email']) {
                    $existingParticipante->email = $participante['email'];
                    $updated = true;
                }

                if ($updated && $existingParticipante->save()) {
                    $updatedParticipants[] = [
                        'participante' => $existingParticipante,
                        'oldAttributes' => $oldAttributes,
                        'newAttributes' => $newAttributes,
                        'rol' => $rol // Guarda el rol junto con el participante actualizado
                    ];
                }

                CursoParticipante::updateOrCreate(
                    [
                        'curso_id' => $curso->id,
                        'participante_id' => $existingParticipante->id,
                    ],
                    ['rol' => $rol] // Aquí se usa el rol almacenado
                );
            }
        }

        return [
            'new' => $newParticipants,
            'updated' => $updatedParticipants,
            'failed' => $failedParticipants
        ];
    }


}
