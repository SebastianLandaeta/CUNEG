<?php

namespace App\Http\Controllers;

use App\Exports\ParticipantsExport;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Participante;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::simplePaginate(8);
        return view('cursos', ['cursos' => $cursos]);
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
            if ($participante->cursos()->count() <= 1) {
                // Si no está asociado a otro curso, eliminar el participante
                $participante->delete();
            } else {
                // Si está asociado a otro curso, eliminar solo la relación con el curso actual
                $participante->cursos()->detach($curso->id);
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


    public function loadedList(Request $request,Curso $curso)
    {
        $helper = new \App\Helpers\FileProcessing\FileProcessing();

        if ($request->hasFile('documento')) {
            $path = $request->file('documento')->getRealPath();

            $validFormats = ['xlsx', 'xls'];
            $extension = $request->file('documento')->getClientOriginalExtension();

            if (in_array($extension, $validFormats)) {
                $participantes = $helper->importOfParticipantes($path);

                if (count($participantes)<1) {
                    return redirect()->back()->withErrors(['error' => "El archivo para '{$curso->nombre}' está vacío o tiene estructura incorrecta"]);
                }

                if ($helper->validateParticipantes($participantes) == false) {
                    return response()->view('DataError', compact('participantes'));

                } else {
                    $helper->saveParticipantes($participantes, $curso);
                    return redirect()->route('cursos');
                }
            } else {
                return redirect()->back()->withErrors(['error' => "El archivo para '{$curso->nombre}' no está en una extension válida (xlsx o xls)."]);
            }
        }

        return redirect()->back()->withErrors(['error' => "No se seleccionó ningún archivo para el curso '{$curso->nombre}'"]);
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

    public function deleteList(Curso $curso)
    {
        // Obtener los participantes asociados al curso que se está eliminando
        $participantes = $curso->participantes()->get();

        // Iterar sobre cada participante
        foreach ($participantes as $participante) {
            // Verificar si el participante está asociado a otro curso
            if ($participante->cursos()->count() <= 1) {
                // Si no está asociado a otro curso, eliminarlo
                $curso->cursoParticipantes()->delete();
                Participante::whereDoesntHave('cursoParticipantes')->delete();
            } else {
                // Si está asociado a otro curso, eliminar solo la relación con el curso actual
                $participante->cursos()->detach($curso->id);
            }
        }

        // Actualizar el estado de lista cargada del curso
        $curso->lista_cargada = false;
        $curso->save();

        return redirect()->back()->with('success', 'Lista eliminada correctamente');
    }
}
