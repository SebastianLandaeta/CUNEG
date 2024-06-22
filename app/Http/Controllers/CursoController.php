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
        
        $cursos = Curso::with(['cursoParticipantes', 'cursoParticipantes.participante'])
        ->simplePaginate(8);

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
        $participantes = $curso->cursoParticipantes()->get();
    
        // Iterar sobre cada participante
        foreach ($participantes as $cursoParticipante) {
            // Acceder al participante a través del modelo CursoParticipante
            $participante = $cursoParticipante->participante;
    
            // Verificar si el participante está asociado a otro curso
            if ($participante->cursoParticipante()->count() <= 1) {
                // Si no está asociado a otro curso, eliminar el participante
                $participante->delete();
            } else {
                // Si está asociado a otro curso, eliminar solo la relación con el curso actual
                CursoParticipante::where('curso_id', $curso->id)
                ->where('participante_tipo_documento', $participante->tipo_documento)
                ->where('participante_numero_documento', $participante->numero_documento)
                ->delete();
            }
        }
    
        // Eliminar los registros de la tabla intermedia
        $curso->cursoParticipantes()->delete();
    
        // Finalmente, eliminar el curso
        $curso->delete();
    
        return redirect()->route('cursos.index');
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
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'rol' => 'required',
        ]);

        // Verificar si el participante ya existe
        $participante = Participante::firstOrCreate([
            'tipo_documento' => $validatedData['tipo_documento'],
            'numero_documento' => $validatedData['numero_documento']
        ], [
            'nombre' => $validatedData['nombre'],
            'apellido' => $validatedData['apellido'],
            'email' => $validatedData['email']
        ]);

        // Eliminar relación previa si existe
        CursoParticipante::where('curso_id', $curso->id)
            ->where('participante_tipo_documento', $participante->tipo_documento)
            ->where('participante_numero_documento', $participante->numero_documento)
            ->delete();

        // Asociar el participante al curso con la nueva relación
        CursoParticipante::create([
            'curso_id' => $curso->id,
            'participante_tipo_documento' => $participante->tipo_documento,
            'participante_numero_documento' => $participante->numero_documento,
            'rol' => $validatedData['rol']
        ]);

        return redirect()->back()->with('success', 'Participante agregado correctamente.');
    }


    public function downloadExcelExample()
    {
        $rutaArchivo = storage_path('app\public\Listado-Ejemplo.xlsx');

        return response()->download($rutaArchivo, 'Listado_Participantes.xlsx');
    }


    public function ExtractList(Curso $curso, Excel $excel)
    {
        $fileName = 'Participantes_' . $curso->nombre . '_' . $curso->id . '.xlsx';

        return $excel->download(new ParticipantsExport($curso->id), $fileName);
    }

    public function deleteSelectedParticipants(Request $request, Curso $curso)
    {
        $participantes = $request->input('participantes', []);

        foreach ($participantes as $participante) {
            $participante = json_decode($participante, true);
            $tipo_documento = $participante['tipo_documento'];
            $numero_documento = $participante['numero_documento'];

            // Eliminar la relación entre el curso y los participantes seleccionados
            CursoParticipante::where([
                ['curso_id', '=', $curso->id],
                ['participante_tipo_documento', '=', $tipo_documento],
                ['participante_numero_documento', '=', $numero_documento]
            ])->delete();

            // Verificar si el participante está asociado a otros cursos
            $otrosCursos = CursoParticipante::where([
                ['participante_tipo_documento', '=', $tipo_documento],
                ['participante_numero_documento', '=', $numero_documento]
            ])->exists();

            // Si no está asociado a otros cursos, eliminar el participante
            if (!$otrosCursos) {
                Participante::where([
                    ['tipo_documento', '=', $tipo_documento],
                    ['numero_documento', '=', $numero_documento]
                ])->delete();
            }
        }

        return redirect()->back()->with('success', 'Participantes eliminados correctamente.');
    }
}
