<?php

namespace App\Helpers\QrProcessing;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrProcessing
{
    public function Save_Qr($url)
    {
        $qrCodePNG = QrCode::format('png')->size(200)->generate($url);

        // Directorio donde se guardará el código QR (puedes cambiar la ruta según tu estructura de carpetas)
        $directory = public_path('qr_codes/consultas');

        // Verificar si el directorio no existe, y si no existe, crearlo
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Nombre del archivo
        $filename = 'qr_code_' . time() . '.png'; 

        // Ruta completa del archivo
        $filePath = $directory . '/' . $filename;

        // Guardar el código QR en el archivo
        file_put_contents($filePath, $qrCodePNG);
    }
    
}