<?php

namespace App\Helpers\FileProcessing;

use App\Imports\ParticipantesImport;
use App\Models\Curso;
use App\Models\Participante;
use Maatwebsite\Excel\Facades\Excel;

class FileProcessing
{
    
    public function importOfParticipantes($path)
    {
        $import = new ParticipantesImport;
        Excel::import($import, $path);
        return $import->getParticipantes();
    }


    public function valid_ci($cedula){
        if (preg_match("/^[0-9]+$/", $cedula)){
            return true;
        } else {
            return false;
        }
    }

    public function valid_rol($rol){
        if ($rol === "participante" || $rol === "instructor") {
            return true;
        } else {
            return false;
        }
    }

    function verify_ci($cedula, $participantes) {
        $cedulaCount = 0;
    
        foreach ($participantes as $participante) {
            if ($participante['cedula'] == $cedula) {
                $cedulaCount++;
                if ($cedulaCount >= 2) {
                    return false;
                }
            }
        }
    
        return true;
    }

    function verify_email($email, $participantes) {
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


    public function validateParticipantes($participantes)
    {

        foreach ($participantes as $participante) {
            
            if ($this->valid_ci($participante['cedula']) == false) {
                return false;
            }
            if ($this->valid_rol($participante['rol']) == false){
                return false;
            }
            if ($this->verify_ci($participante['cedula'],$participantes) == false){
                return false;
            }
            if ($this->verify_email($participante['email'],$participantes) == false){
                return false;
            }
        }

        return true;
    }

    public function saveParticipantes($participantes,Curso $curso)
    {
        
        if (!$curso) {
            return redirect()->back()->with('error', 'Curso no encontrado');
        }

        // Eliminar todas las relaciones existentes en la tabla cursoparticipantes
        $curso->participantes()->detach();
        Participante::whereDoesntHave('cursoParticipantes')->delete();

        foreach ($participantes as $participante) {
            $cedula = intval($participante['cedula']);

            $existingParticipante = Participante::where('cedula', $cedula)->first();

            if (!$existingParticipante) {
                
                $newParticipante = new Participante([
                    'cedula' => $participante['cedula'],
                    'nombre' => $participante['nombre'],
                    'apellido' => $participante['apellido'],
                    'email' => $participante['email'],
                ]);

                $newParticipante->save();

                // Guardar el rol en la tabla de relación (cursoparticipante)
                $newParticipante->cursos()->attach($curso, ['rol' => $participante['rol']]);
            } else {

                if ($existingParticipante->nombre !== $participante['nombre']) {
                    $existingParticipante->nombre = $participante['nombre'];
                }

                if ($existingParticipante->apellido !== $participante['apellido']) {
                    $existingParticipante->apellido = $participante['apellido'];
                }

                if ($existingParticipante->email !== $participante['email']) {
                    $existingParticipante->email = $participante['email'];
                }

            // Guarda el nuevo rol en la tabla de relación (cursoparticipante)
                $existingParticipante->cursos()->syncWithoutDetaching([$curso->id => ['rol' => $participante['rol']]]);
            
                $existingParticipante->save();
            }
        }

        $curso->update(['lista_cargada' => true]);
        
        return redirect()->route('cursos');
    }


    public function searchRol (Curso $curso, Participante $participante) {
        $cursoParticipante = $participante->cursoparticipantes->where('curso_fk', $curso->id)->first();
        if ($cursoParticipante){
            return $cursoParticipante -> rol;
        }else{
            return 'N/A';
        }
    }

}