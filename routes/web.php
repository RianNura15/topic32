<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('mahasiswa/pdf', [MahasiswaController::class, 'cetak_pdf']);
// Route::resource('mahasiswa', MahasiswaController::class);

Route::prefix('mahasiswa')->group(function () {
    Route::get('index', [MahasiswaController::class, 'index'])->name('index');
    Route::get('create', [MahasiswaController::class, 'create'])->name('create');
    Route::post('create/store', [MahasiswaController::class, 'store'])->name('store');
    Route::get('edit/{mahasiswa}', [MahasiswaController::class, 'edit'])->name('edit');
    Route::post('edit/update/{mahasiswa}', [MahasiswaController::class, 'update'])->name('update');
    Route::get('delete/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('delete');
    Route::get('cetak_pdf', [MahasiswaController::class, 'cetak_pdf'])->name('cetakpdf');
});
