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
        return $rol === "Participante" || $rol === "Instructor" || $rol === "Facilitador";
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

            $existingParticipante = Participante::where('tipo_documento', $tipoDocumento)
                                                ->where('numero_documento', $numeroDocumento)
                                                ->first();

            if (!$existingParticipante) {
                $newParticipante = new Participante([
                    'tipo_documento' => $participante['tipo_documento'],
                    'numero_documento' => $participante['numero_documento'],
                    'nombre' => $participante['nombre'],
                    'apellido' => $participante['apellido'],
                    'email' => $participante['email'],
                ]);

                if ($newParticipante->save()) {
                    $newParticipants[] = $newParticipante;
                    CursoParticipante::create([
                        'curso_id' => $curso->id,
                        'participante_tipo_documento' => $newParticipante->tipo_documento,
                        'participante_numero_documento' => $newParticipante->numero_documento,
                        'rol' => $participante['rol']
                    ]);
                } else {
                    $failedParticipants[] = $newParticipante;
                }
            } else {
                $updated = false;
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
                    $updatedParticipants[] = $existingParticipante;
                }

                CursoParticipante::updateOrCreate(
                    [
                        'curso_id' => $curso->id,
                        'participante_tipo_documento' => $existingParticipante->tipo_documento,
                        'participante_numero_documento' => $existingParticipante->numero_documento,
                    ],
                    ['rol' => $participante['rol']]
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
