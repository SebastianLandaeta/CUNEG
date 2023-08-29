<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::all();
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

        $cursos = Curso::where('nombre', 'like', '%' . $terminoBusqueda . '%')->get();

        return view('cursos', compact('cursos'));
    }
}
