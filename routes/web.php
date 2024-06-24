<?php

use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\PizarraController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\ParticipanteController;

// Inicio
Route::get('/', function () {
    return view('index');
})->name('index');

//Cursos
Route::prefix('cursos')->group(function () {
    Route::get('/', [CursoController::class, 'index'])->name('cursos.index');
    Route::post('/', [CursoController::class, 'create'])->name('cursos.create');

    Route::put('/{curso}', [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');

    Route::post('/cargarLista/{curso}', [CursoController::class, 'loadList'])->name('curso.loadList');
    Route::post('/add-participante/{curso}', [CursoController::class, 'addParticipante'])->name('cursos.addParticipante');

    Route::delete('/deleteSelectedParticipants/{curso}', [CursoController::class, 'deleteSelectedParticipants'])->name('curso.deleteSelectedParticipants');
});


//Participantes (Accciones que afectan directamente a los usuarios)
Route::prefix('participantes')->group(function () {
    Route::get('modificar/{participante}', [ParticipanteController::class, 'modificar_participante'])->name('participante.modificar');
    Route::post('verificar-documento', [ParticipanteController::class, 'verificarDocumento'])->name('participante.verificarDocumento');
    Route::put('actualizar/{participante}', [ParticipanteController::class, 'actualizar_participante'])->name('participante.actualizar');
});


Route::get('/cursos/buscar', [CursoController::class, 'search'])->name('cursos.search');



Route::get('/download-excel-example',[CursoController::class, 'downloadExcelExample'])->name('d-excel');


// QR
Route::get('certificate_search_qr', [QrController::class, 'index'])->name('qr.search');

Route::post('certificate_search_qr', [QrController::class, 'generateQr'])->name('qr.search.generate');

Route::post('qr_participant', [QrController::class, 'generarQrParticipante'])->name('qr.participant');

Route::get('certificate_search_qr/{cursoId}/{participanteId}', [QrController::class,'responseQr'])->name('qr_response');

// Pizarra
Route::prefix('pizarra')->group(function () {
    // Vista para el diseño de certificado
    Route::get('/crear/{curso}', [PizarraController::class, 'index'])->name('pizarra.index');

    // Guardar el diseño del certificado
    Route::post('/{curso}', [PizarraController::class, 'guardar'])->name('pizarra.guardar');

    // Visualizar certificado
    Route::get('/visualizar_curso', [PizarraController::class, 'visualizarPizarra'])->name('pizarra.visualizar');

    // Eliminar el diseño del certificado
    Route::delete('/{curso}', [PizarraController::class, 'delete'])->name('pizarra.eliminar');
});

// Emitir certificado
Route::get('/emitir-certificados/{curso}', [CertificadoController::class, 'index'])->name('emitir_certificados');

Route::post('/emitir-certificados/{curso}', [CertificadoController::class, 'imprimirCertificados'])->name('emitir_certificados.impresion');

Route::post('/guardar-certificado', [CertificadoController::class, 'guardadoCertificado'])->name('emitir_certificados.guardado');

// Cerficado mail
Route::post('/correo-certificado', [CertificadoController::class, 'correo_envio'])->name('correo_envio');


