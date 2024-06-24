<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participante;
use App\Models\Curso;

class ParticipanteController extends Controller
{
    public function modificar_participante(Participante $participante)
    {
        $documentos_validos = ['V', 'J', 'E', 'G'];
        $cursos = Curso::all();
        return view('modificarParticipante', compact('participante', 'documentos_validos', 'cursos'));
    }

    public function verificarDocumento(Request $request)
    {
        // Acceder a los datos enviados en el JSON
        $tipo_documento = $request->input('tipo_documento');
        $numero_documento = $request->input('numero_documento');
        $current_tipo_documento = $request->input('current_tipo_documento');
        $current_numero_documento = $request->input('current_numero_documento');

        // Verificar si el documento ya existe en la base de datos
        $existe = Participante::where('tipo_documento', $tipo_documento)
            ->where('numero_documento', $numero_documento)
            ->where(function ($query) use ($current_tipo_documento, $current_numero_documento) {
                $query->where('tipo_documento', '!=', $current_tipo_documento)
                      ->orWhere('numero_documento', '!=', $current_numero_documento);
            })
            ->exists();

        // Devolver la respuesta en formato JSON
        return response()->json(['existe' => $existe]);
    }


    public function actualizar_participante(Request $request, Participante $participante)
    {
        // Aplicar trim a los datos del request
        $data = $request->all();
        $data['nombre'] = trim($data['nombre']);
        $data['apellido'] = trim($data['apellido']);
        $data['email'] = trim($data['email']);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tipo_documento' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:255',
            'roles' => 'required|array', // Roles por curso
            'roles.*' => 'in:Participante,Instructor,Facilitador', // Validar que los roles sean válidos
        ]);

        // Actualizar los datos del participante
        $participante->nombre = $request->nombre;
        $participante->apellido = $request->apellido;
        $participante->email = $request->email;
        $participante->tipo_documento = $request->tipo_documento;
        $participante->numero_documento = $request->numero_documento;

        // Guardar los cambios en el participante
        if ($participante->save()) {
            // Actualizar los roles por cada curso seleccionado
            foreach ($request->roles as $curso_id => $rol) {
                $participante->cursos()->updateExistingPivot($curso_id, ['rol' => $rol]);
            }

            return redirect()->route('cursos.index')->with('success', 'Datos actualizados correctamente.');
        } else {
            return redirect()->back()->with('error', 'Error al actualizar los datos del participante.');
        }
    }




}
