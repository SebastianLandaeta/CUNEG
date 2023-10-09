<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Participante;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantesImport;


class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::simplePaginate(8);
        return view('cursos', ['cursos' => $cursos]);
    }

    public function store(Request $request)
    {
        $curso = $request->only(['nombre', 'descripcion', 'f_inicio', 'f_finalizacion']);

        Curso::create($curso);

        return redirect()->route('cursos');
    }

    public function update(Request $request, Curso $curso)
    {
        $curso->update($request->only(['nombre', 'descripcion', 'f_inicio', 'f_finalizacion']));
        return redirect()->route('cursos');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos');
    }

    public function search(Request $request)
    {
        $terminoBusqueda = $request->input('buscar');

        $cursos = Curso::where('nombre', 'like', '%' . $terminoBusqueda . '%')->paginate(8);;

        return view('cursos', compact('cursos'));
    }

    
    public function loadedList(Request $request, $curso)
    {
        $cursoModel = Curso::find($curso);

        if ($request->hasFile('documento')) {
            $path = $request->file('documento')->getRealPath();

            

            // Verifica si el archivo no está vacío
            if (file_exists($path) && filesize($path) > 0) {
                
                

                if ($cursoModel) {
                    if ($cursoModel->lista_cargada) {
                        $cursoModel->participantes()->detach();
                    }
                }
                
                // Utiliza la clase ParticipantesImport para importar los datos del archivo CSV
                $import = new ParticipantesImport($curso);
                Excel::import($import, $path);

                Curso::find($curso)->update(['lista_cargada' => true]);
                return redirect()->route('cursos');
                
            } else {
                return redirect()->back()->with('error', 'El archivo está vacío.');
            }
        }
    
        return redirect()->back()->withErrors(['error' => "No se seleccionó ningún archivo para el curso '{$cursoModel->nombre}'"]);
    }
}
