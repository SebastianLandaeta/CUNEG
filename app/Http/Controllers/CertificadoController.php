<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\CursoCertificado;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificacionMail;
use Dompdf\Options;
use Dompdf\Dompdf;



class CertificadoController extends Controller
{
    public function index(Curso $curso)
    {
        // Obtener la lista de participantes del curso
        $curso->participantes;

        return view('emitir_certificados_vista', compact('curso'));
    }

    public function imprimirCertificados(Curso $curso)
    {
        // Recuperar el participante según el ID proporcionado
        $certificado = CursoCertificado::where('idCurso', $curso->id)->first();

        $data = json_decode($certificado->data);

        return view('emitir_certificados_funcion', compact('data', 'curso'));
    }

    public function guardadoCertificado(Request $request){
        $imageData = $request->input('image');
        $idParticipante = $request->input('cedulaParticipante');
        $idCurso = $request->input('idCurso');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); 
        $options->set('isPhpEnabled', true); 
        $options->set('dpi', 300); // Ajusta la resolución a 300 DPI
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4','landscape');
        

        // Decodificar el contenido base64 y guardar la imagen en una carpeta específica
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

        $html = '<div style="text-align: center;"><img src="' . $imageData . '" alt="imagen" style="width: auto; height: 100%;"></div>';

        $dompdf->loadHtml($html);

        // Renderizar el PDF
        $dompdf->render();

        // Obtener el contenido del PDF generado
        $pdfOutput = $dompdf->output();

        $filename = 'Certificado'.$idParticipante.'.pdf'; // Nombre del archivo

        $directory = public_path('certificados/curso_'.$idCurso); // Ruta donde se guardará la imagen


        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $filename;

        // Guardar la imagen en la carpeta específica
        file_put_contents($path,$pdfOutput);

        $filename = 'Certificado'.$idParticipante.'.png';
        $path = $directory . '/' . $filename;

        file_put_contents($path,$decodedImage);
        

        // Puedes hacer cualquier otro procesamiento aquí, como guardar la ruta en la base de datos
        //return response()->json(['message' => 'Imagen guardada exitosamente.']);
    }


    public function correo_envio(Request $request){
        // Decodificar los datos JSON en objetos PHP
        $data = json_decode($request->getContent());

        // Acceder a los participantes y a los datos
        $participante = $data->participante;
        $curso = $data->curso;
    
        // Ahora puedes trabajar con los datos y enviar el correo
        // Por ejemplo, enviar un correo a cada participante con los datos proporcionados
        
        //Mail::to($participante->email)->send(new CertificacionMail($curso,$participante));
        
        return 'Correo(s) enviado(s) correctamente';
    }
}    
