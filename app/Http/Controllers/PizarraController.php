<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\CursoCertificado;
use App\Models\Curso;

class PizarraController extends Controller
{
    // Método para mostrar la vista Pizarra
    public function index($idCurso)
    {
        return view('Pizarra', compact('idCurso'));
    }
    

    public function guardar(Request $request, $idCurso) 
    {
        // Obtener los datos de la sesión
        $formasEspeciales = json_decode($request->input('formasEspeciales'), true);
        $formasSimples = json_decode($request->input('formasSimples'), true);
        
        /*
        //TESTING DE QUE SE ESTA ENVIANDO Y RECIBIENDO EN EL JSON
        //-------------------------------------------------------------------------------------------
        $objetos = [
            'formasEspeciales' => $formasEspeciales,
            'formasSimples' => $formasSimples,
        ];

        $canvainfo = json_encode($objetos);

        return response()->json(json_decode($canvainfo));
        //-------------------------------------------------------------------------------------------
        */
        //NO SE ESTA USANDO REALMENTE ESTOS DATOS PERO ES PARA PROBAR EL ENVIO DE DATOS DE UNA VISTA A OTRA
        $datosUsuario = [
            'cedula' => '11.111.111',
            'nombre'=>'Victor Diaz',
            'email' => 'VictorDiaz@gmail.com',
        ]; 

        //Guardar los distintos tamaños de los qr's posibles.
        
        foreach ($formasEspeciales as &$elemento) {
            // Verificar si el elemento es de tipo 'qr'
            if ($elemento['type'] === 'qr') {
                $anchoQR = $elemento['width'];

                $qrCodePNG = QrCode::format('png')->size($anchoQR)->generate('Este es un qr de ejemplo para el enlace de certificado');

                $qrCodeBase64 = base64_encode($qrCodePNG);

                $elemento['qr_encode']=$qrCodeBase64;
            }
        }  

        // Eliminar todas las pizarras asociadas al curso específico
        CursoCertificado::where('idCurso', $idCurso)->delete();

        // Guardar la nueva pizarra
        $cursoCertificado = new CursoCertificado();
        $cursoCertificado->idCurso = $idCurso;
        $cursoCertificado->data = json_encode(['formasEspeciales' => $formasEspeciales, 'formasSimples' => $formasSimples]);
        $cursoCertificado->save();

        $curso = Curso::find($cursoCertificado->idCurso);
        $curso->update(['certificado_cargado' => true]);

        $request->session()->put('formasEspeciales', $formasEspeciales);
        $request->session()->put('formasSimples', $formasSimples);
        $request->session()->put('datosUsuario', $datosUsuario);

        //Ejemplo de procesamiento: simplemente pasar los datos a la vista de prueba
        //return redirect()->route('pizarra.canvas_modificado', ['idCurso' => $idCurso]);

        return redirect()->route('cursos');
    }
    //VENTANA PARA PRUEBAS DE VISUALIZACION CORRECTA DE LOS DATOS DE LA PIZARRA
    public function canvasModificado(Request $request)
    {
        // Obtener los datos de la sesión
        $formasEspeciales = $request->session()->get('formasEspeciales');
        $formasSimples = $request->session()->get('formasSimples');
        $datosUsuario = $request->session()->get('datosUsuario');

        $objetos = [
            'formasEspeciales' => $formasEspeciales,
            'formasSimples' => $formasSimples,
            'datosUsuario' => $datosUsuario,
        ];

        //TESTING DE QUE SE ESTA ENVIANDO Y RECIBIENDO EN EL JSON
        /*$canvainfo = json_encode($objetos);

        return response()->json(json_decode($canvainfo));*/
        
        // Renderizar la vista canvas_modificado con los datos obtenidos
        return view('canvas_modificado', [
            'formasEspeciales' => $formasEspeciales,
            'formasSimples' => $formasSimples,
            'datosUsuario' => $datosUsuario,
        ]);
    }

    public function visualizarPizarra(Request $request)
    {
        $idCurso = $request->input('idCurso');
        // Obtener el curso certificado asociado al curso específico desde la base de datos
        $cursoCertificado = CursoCertificado::where('idCurso', $idCurso)->first();

        // Verificar si se encontró un curso certificado
        if ($cursoCertificado) {
            // Obtener los datos de la pizarra desde la columna 'data'
            $pizarraData = json_decode($cursoCertificado->data, true);

            return response()->json($pizarraData);
        } else {
            return response()->json(['error' => 'No se encontró un curso certificado para el curso específico'], 404);
        }
    }
    ///(NO FUNCIONA / NO HACE NADA / NO APLICA)Funcion proxima para emitir cerificado
    public function emitir_certificados(Request $request,$idCurso){
        $formasEspeciales = $request->session()->get('formasEspeciales');
        $formasSimples = $request->session()->get('formasSimples');
        $datosUsuario = $request->session()->get('datosUsuario');

        $datosUsuario = [
            'cedula' => '1',
            'nombre'=>'Victor Diaz',
            'email' => 'VictorDiaz@gmail.com',
        ]; 

        $url = route('qr_response', ['cursoId' => $idCurso, 'participanteId' => $datosUsuario['cedula']]);

        $Ipv4 = "192.168.1.110"; //<----- ipv4 del equipo donde se este ejecutando el xampp
        $puerto = "80"; // <----- Puerto configurado en el xampp

        $url = str_replace('localhost', "$Ipv4:$puerto", $url);

        //Guardar los distintos tamaños de los qr's posibles.
        
        foreach ($formasEspeciales as &$elemento) {
            // Verificar si el elemento es de tipo 'qr'
            if ($elemento['type'] === 'qr') {
                $anchoQR = $elemento['width'];

                $qrCodePNG = QrCode::format('png')->size($anchoQR)->generate($url);

                $qrCodeBase64 = base64_encode($qrCodePNG);

                $elemento['qr_encode']=$qrCodeBase64;
            }
        }  

    }
}
