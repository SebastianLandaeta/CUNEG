<?php

namespace App\Http\Controllers;

use App\Exports\ParticipantsExport;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Participante;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;


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
        $curso->cursosParticipantes()->delete();
        
        Participante::whereDoesntHave('cursoParticipantes')->delete();

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

    $helper = new \App\Helpers\FileProcessing\FileProcessing();

    if ($request->hasFile('documento')) {
        $path = $request->file('documento')->getRealPath();
            
            $validFormats = ['xlsx', 'xls'];
            $extension = $request->file('documento')->getClientOriginalExtension();
    
            if (in_array($extension, $validFormats)) {
                $participantes = $helper->importOfParticipantes($path);
            
                if (count($participantes)<1) {
                    return redirect()->back()->withErrors(['error' => "El archivo para '{$cursoModel->nombre}' está vacío o tiene estructura incorrecta"]);
                }
    
                if ($helper->validateParticipantes($participantes) == false) {
    
                    return response()->view('DataError', compact('participantes'));
                        
                } else {
                    $helper->saveParticipantes($participantes, $curso);
                    return redirect()->route('cursos');
                }
            } else {
                return redirect()->back()->withErrors(['error' => "El archivo para '{$cursoModel->nombre}' no está en una extension válida (xlsx o xls)."]);
            }
        }
        return redirect()->back()->withErrors(['error' => "No se seleccionó ningún archivo para el curso '{$cursoModel->nombre}'"]);
    }

    public function downloadExcelExample()
    {
        $rutaArchivo = storage_path('app\public\Listado-Ejemplo.xlsx'); 

        return response()->download($rutaArchivo, 'Listado_Participantes.xlsx');
    }


    public function ExtractList($cursoId, Excel $excel)
    {
        $curso = Curso::find($cursoId);

        $fileName = 'Participantes_' . $curso->nombre . '_' . $cursoId . '.xlsx';

        return $excel->download(new ParticipantsExport($cursoId), $fileName);
    }


}
