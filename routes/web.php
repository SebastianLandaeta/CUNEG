<?php

use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\PizarraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Inicio
Route::get('/', function () {
    return view('index');
})->name('index');

// Curso 
Route::get('/cursos', [CursoController::class, 'index'])->name('cursos');

Route::post('/cursos', [CursoController::class, 'store'])->name('cursos');

Route::put('cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update');

Route::delete('cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');

Route::get('/cursos/buscar', [CursoController::class, 'search'])->name('cursos.search');

Route::post('curso/LoadList/{curso}', [CursoController::class, 'loadedList'])->name('curso.loadedList');

Route::get('/download-excel-example',[CursoController::class, 'downloadExcelExample'])->name('d-excel');

Route::get('curso/LoadList/{curso}', [CursoController::class, 'ExtractList'])->name('curso.ExtractList');

Route::delete('/curso/{curso}/deleteList', [CursoController::class, 'deleteList'])->name('curso.deleteList');


//QR 
Route::get('certificate_search_qr', [QrController::class, 'index'])->name('qr.search');

Route::post('certificate_search_qr', [QrController::class, 'generateQr'])->name('qr.search.generate');

Route::get('certificate_search_qr/{cursoId}/{participanteId}', [QrController::class,'responseQr'])->name('qr_response');

//Pizarra
Route::prefix('pizarra')->group(function () {
    // Vista para el diseño de certificado
    Route::get('/crear/{idCurso}', [PizarraController::class, 'index'])->name('pizarra.index');

    Route::post('/visualizar_curso', [PizarraController::class, 'visualizarPizarra'])->name('pizarra.visualizar');

    // Guardar el diseño del certificado
    Route::post('/guardar/{idCurso}', [PizarraController::class, 'guardar'])->name('pizarra.guardar');

    // Vista para editar el diseño del certificado
    Route::get('/editar/{id}', [PizarraController::class, 'editar'])->name('pizarra.editar');

    // Actualizar el diseño del certificado
    Route::put('/actualizar/{id}', [PizarraController::class, 'actualizar'])->name('pizarra.actualizar');

    // Eliminar el diseño del certificado
    Route::delete('/eliminar/{id}', [PizarraController::class, 'eliminar'])->name('pizarra.eliminar');

    Route::get('/canvas-modificado/{idCurso}', [PizarraController::class, 'canvasModificado'])->name('pizarra.canvas_modificado');
});


