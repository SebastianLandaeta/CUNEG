<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\Curso;
use App\Models\Participante;


class QrController extends Controller
{
    public function index(){
        return view('requestQr');
    }

    public function generateQr(Request $request)
    {
        $helper = new \App\Helpers\QrProcessing\QrProcessing();

        $Ipv4 = "192.168.1.109"; //<----- ipv4 del equipo donde se este ejecutando el xampp
        $puerto = "80"; // <----- Puerto configurado en el xampp
        
        $idCurso = $request->input('idCurso');
        $cedulaEstudiante = $request->input('cedulaEstudiante');

        $url = route('qr_response', ['cursoId' => $idCurso, 'participanteId' => $cedulaEstudiante]);// <--- SOLO SIRVE DE FORMA LOCAL
        
        $url = str_replace('localhost', "$Ipv4:$puerto", $url);

        // Generar el código QR en formato SVGWS
        $qrCode = QrCode::size(200)->generate($url);

        $helper->Save_Qr($url); //<--- Salva en carpeta

        // Devolver el código QR en formato SVG como respuesta JSON
        return response($qrCode)
        ->header('Content-Type', 'image/svg+xml');
    }
    
    public function responseQr($cursoId, $participanteId)
    {
        // Buscar el curso por su ID
        $curso = Curso::find($cursoId);

        // Buscar el participante por su ID
        $participante = Participante::find($participanteId);
        
        // Verificar si se encontraron el curso y el participante
        if ($curso && $participante && $curso->participantes->contains($participante)) {
            return view('responseQr')
                ->with('curso', $curso)
                ->with('participante', $participante)
                ->with('participanteEnCurso', true);
        } else {
            // Enviar un mensaje de error si no se encuentran el curso o el participante
            $errorMessage = 'No se encontró el curso o el participante.';
            return view('responseQr')
            ->with('participanteEnCurso', false);
        }
    }

    

}
