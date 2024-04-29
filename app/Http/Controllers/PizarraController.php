<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\CursoCertificado;
use App\Models\Curso;

class PizarraController extends Controller
{
    // Método para mostrar la vista Pizarra
    public function index(Curso $curso)
    {
        return view('pizarra', compact('curso'));
    }
    
    public function guardar(Request $request, Curso $curso) 
    {
        // Obtener los datos de la sesión
        $formasEspeciales = json_decode($request->input('formasEspeciales'), true);
        $formasSimples = json_decode($request->input('formasSimples'), true);
        $dimensionesPizarra = json_decode($request->input('canvasDimensions'), true);
        
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
        CursoCertificado::where('idCurso', $curso->id)->delete();

        // Guardar la nueva pizarra
        $cursoCertificado = new CursoCertificado();
        $cursoCertificado->idCurso = $curso->id;
        $cursoCertificado->data = json_encode(['dimensionesCanvas' => $dimensionesPizarra , 'formasEspeciales' => $formasEspeciales, 'formasSimples' => $formasSimples]);
        $cursoCertificado->save();

        $curso->update(['certificado_cargado' => true]);

        return redirect()->route('cursos');
    }

    public function visualizarPizarra(Request $request)
    {
        $idCurso = $request->query('idCurso');
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

    public function delete(Curso $curso){
        $curso->certificado->delete();
        $curso->certificado_cargado = false ;
        $curso->save();
        return redirect()->route('cursos');
    }
}
