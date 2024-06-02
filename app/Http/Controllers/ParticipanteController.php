<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participante;

class ParticipanteController extends Controller
{
    public function create()
    {
        return view('prueba_participantes');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'tipo_documento' => 'required',
            'numero_documento' => 'required|unique:participantes',
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:participantes',
        ]);

        // Crear un nuevo participante en la base de datos
        Participante::create($request->all());

        // Redireccionar a alguna página después de guardar los datos
        return redirect()->route('participantes.store')->with('success', 'Participante agregado correctamente.');
    }
}
