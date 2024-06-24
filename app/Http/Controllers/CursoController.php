<?php

namespace App\Http\Controllers;


use App\Helpers\ProcesarParticipantes;
use App\Exports\ParticipantsExport;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Participante;
use App\Models\CursoParticipante;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;


class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with(['participantes'])->simplePaginate(8);
        return view('cursos', compact('cursos'));
    }


    public function create(Request $request)
    {
        $curso = $request->only(['nombre', 'descripcion', 'f_inicio', 'f_finalizacion', 'horas_academicas']);
        Curso::create($curso);
        return redirect()->route('cursos.index');
    }

    public function update(Request $request, Curso $curso)
    {
        $curso->update($request->only(['nombre', 'descripcion', 'f_inicio', 'f_finalizacion', 'horas_academicas']));
        return redirect()->route('cursos.index');
    }

    public function destroy(Curso $curso)
    {
        // Obtener los participantes asociados al curso que se está eliminando
        $participantes = $curso->participantes()->get();
    
        // Iterar sobre cada participante
        foreach ($participantes as $participante) {
            // Eliminar la relación entre el curso y el participante en la tabla intermedia
            $curso->participantes()->detach($participante->id);
    
            // Verificar si el participante está asociado a otros cursos
            if ($participante->cursos()->count() == 0) {
                // Si no está asociado a otro curso, eliminar el participante
                $participante->delete();
            }
        }
    
        // Eliminar el curso
        $curso->delete();
    
        return redirect()->route('cursos.index')->with('success', 'Curso y participantes asociados eliminados correctamente.');
    }
    
    
    public function search(Request $request)
    {
        $terminoBusqueda = $request->input('buscar');

        $cursos = Curso::where('nombre', 'like', '%' . $terminoBusqueda . '%')->paginate(8);;

        return view('cursos', compact('cursos'));
    }


    public function loadList(Request $request, Curso $curso)
    {
        if ($request->hasFile('documento')) {
            $path = $request->file('documento')->getRealPath();

            $validFormats = ['xlsx', 'xls'];
            $extension = $request->file('documento')->getClientOriginalExtension();

            if (in_array($extension, $validFormats)) {
                $participantes = ProcesarParticipantes::importOfParticipantes($path);

                if (count($participantes) < 1) {
                    return redirect()->back()->withErrors(['error' => "El archivo para '{$curso->nombre}' está vacío o tiene estructura incorrecta"]);
                }

                $validationResults = ProcesarParticipantes::validateParticipantes($participantes);

                $invalidParticipants = $validationResults['invalid'];
                $validParticipants = $validationResults['valid'];

                // Guardar participantes válidos
                $saveResults = ProcesarParticipantes::saveParticipantes($validParticipants, $curso);

                // Redirigir a la vista de resultados
                return view('ParticipantesResult', [
                    'newParticipants' => $saveResults['new'],
                    'updatedParticipants' => $saveResults['updated'],
                    'failedParticipants' => $saveResults['failed'],
                    'invalidParticipants' => $invalidParticipants
                ]);
            } else {
                return redirect()->back()->withErrors(['error' => "El archivo para '{$curso->nombre}' no está en una extensión válida (xlsx o xls)."]);
            }
        }

        return redirect()->back()->withErrors(['error' => "No se seleccionó ningún archivo para el curso '{$curso->nombre}'"]);
    }

  
    public function addParticipante(Request $request, Curso $curso)
    {
        $validatedData = $request->validate([
            'tipo_documento' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rol' => 'required|string|max:255',
        ]);
    
        // Verificar si el participante ya existe
        $participante = Participante::where('tipo_documento', $validatedData['tipo_documento'])
            ->where('numero_documento', $validatedData['numero_documento'])
            ->first();
    
        if ($participante) {
            // Verificar si ya está asociado al curso
            $existsInCurso = CursoParticipante::where('curso_id', $curso->id)
                ->where('participante_id', $participante->id)
                ->exists();
    
            if ($existsInCurso) {
                $errorMessage = "El participante {$participante->tipo_documento}-{$participante->numero_documento}, {$participante->nombre} {$participante->apellido}  ya está registrado en el curso {$curso->nombre}.";
                return redirect()->back()->withErrors(['error' => $errorMessage])->withInput();
            }
        } else {
            // Crear el participante si no existe
            $participante = Participante::create([
                'tipo_documento' => $validatedData['tipo_documento'],
                'numero_documento' => $validatedData['numero_documento'],
                'nombre' => $validatedData['nombre'],
                'apellido' => $validatedData['apellido'],
                'email' => $validatedData['email'],
            ]);
        }
    
        // Asociar el participante al curso con el nuevo rol
        CursoParticipante::create([
            'curso_id' => $curso->id,
            'participante_id' => $participante->id,
            'rol' => $validatedData['rol'],
        ]);
    
        return redirect()->back()->with('success', 'Participante agregado correctamente.');
    }
    


    public function downloadExcelExample()
    {
        $rutaArchivo = storage_path('app\public\Listado-Ejemplo.xlsx');

        return response()->download($rutaArchivo, 'Listado_Participantes.xlsx');
    }

    public function deleteSelectedParticipants(Request $request, Curso $curso)
    {
        $participantesIds = $request->input('participantes', []);

        foreach ($participantesIds as $participanteId) {
            
            $participante = Participante::findOrFail($participanteId);

            
            CursoParticipante::where('curso_id', $curso->id)
                ->where('participante_id', $participanteId) 
                ->delete();

            $otrosCursos = CursoParticipante::where('participante_id', $participanteId)
                ->exists();

            if (!$otrosCursos) {
                $participante->delete();
            }
        }

        return redirect()->back()->with('success', 'Participantes eliminados correctamente.');
    }

    public function ExtractList(Curso $curso, Excel $excel)
    {
        $fileName = 'Participantes_' . $curso->nombre . '_' . $curso->id . '.xlsx';
        return $excel->download(new ParticipantsExport($curso->id), $fileName);
    }
}
