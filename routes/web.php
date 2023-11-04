<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;

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

Route::get('/', function () {
    return view('index');
});

Route::get('/cursos', [CursoController::class, 'index'])->name('cursos');

Route::post('/cursos', [CursoController::class, 'store'])->name('cursos');

Route::put('cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update');

Route::delete('cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');

Route::get('/cursos/buscar', [CursoController::class, 'search'])->name('cursos.search');

Route::post('curso/LoadList/{curso}', [CursoController::class, 'loadedList'])->name('curso.loadedList');

Route::get('/download-excel-example',[CursoController::class, 'downloadExcelExample'])->name('d-excel');

Route::get('curso/LoadList/{curso}', [CursoController::class, 'ExtractList'])->name('curso.ExtractList');

