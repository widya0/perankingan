<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataCalonController;
use App\Http\Controllers\DataKriteriaController;
use App\Http\Controllers\FCalonController;
use App\Http\Controllers\FKriteriaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\PerankinganController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TambahAkunController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Halaman yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::post('/beranda', [BerandaController::class, 'index'])->name('beranda');
    
    Route::get('/datakriteria', [DataKriteriaController::class, 'index'])->name('datakriteria');

    Route::get('/fkriteria', [FKriteriaController::class, 'TambahKriteria'])->name('TambahKriteria');
    Route::post('/fkriteria', [FKriteriaController::class, 'SimpanKriteria'])->name('SimpanKriteria');
    Route::get('/fkriteriaedit/{id_kriteria}', [FKriteriaController::class, 'EditKriteria'])->name('EditKriteria');
    Route::get('/fkriterialihat/{id_kriteria}', [FKriteriaController::class, 'LihatKriteria'])->name('LihatKriteria');
    Route::put('/fkriteria/{id_kriteria}', [FKriteriaController::class, 'UpdateKriteria'])->name('UpdateKriteria');
    Route::delete('/fkriteria/{id_kriteria}', [FKriteriaController::class, 'HapusKriteria'])->name('HapusKriteria');

    Route::get('/datacalon', [DataCalonController::class, 'index'])->name('datacalon');
    Route::post('/datacalon', [DataCalonController::class, 'index'])->name('datacalon');
    Route::post('/datacalon/{id}', [DataCalonController::class, 'verifikasi'])->name('verifikasi');

    Route::get('/fcalon', [FCalonController::class, 'index'])->name('fcalon');
    Route::post('/fcalon', [FCalonController::class, 'TambahCalon'])->name('TambahCalon');
    Route::get('/fcalonedit/{id_kriteria}', [FCalonController::class, 'EditCalon'])->name('EditCalon');
    Route::put('/fcalonedit/{id_kriteria}', [FCalonController::class, 'UpdateCalon'])->name('UpdateCalon');
    Route::post('/fcalonedit/{id_kriteria}', [FCalonController::class, 'verifikasi'])->name('verifikasi');
    Route::delete('/fcalon/{id_kriteria}', [FCalonController::class, 'HapusCalon'])->name('HapusCalon');

    Route::get('/perankingan', [PerankinganController::class, 'index'])->name('perankingan');
    Route::get('/perankingan/hitung', [PerankinganController::class, 'HitungPeringkat'])->name('HitungPeringkat');
    Route::get('/perankingan/preview-pdf', [PerankinganController::class, 'PreviewPDFRanking'])->name('PreviewPDFRanking');
    Route::get('/perankingan/download-pdf', [PerankinganController::class, 'DownloadPDFRanking'])->name('DownloadPDFRanking');
    Route::post('/perankingan/ajukan', [PerankinganController::class, 'AjukanPeringkat'])->name('AjukanPeringkat');

    Route::get('/penerima', [PenerimaController::class, 'index'])->name('penerima');
    Route::get('/penerima/preview-pdf', [PenerimaController::class, 'PreviewPDFPenerima'])->name('PreviewPDFPenerima');
    Route::get('/penerima/download-pdf', [PenerimaController::class, 'DownloadPDFPenerima'])->name('DownloadPDFPenerima');
    Route::put('/penerima/{id}/editstatus', [PenerimaController::class, 'EditStatusPenerima'])->name('EditStatusPenerima');
    Route::put('/penerima/{id}/update-status', [PenerimaController::class, 'UpdateStatusPenerima'])->name('UpdateStatusPenerima');
    Route::post('/penerima/kirim-dashboard', [PenerimaController::class, 'KirimDashboard'])->name('KirimDashboard');

    Route::post('/profil', [ProfilController::class, 'EditProfile'])->name('EditProfile');


    Route::get('/tambahakun', [TambahAkunController::class, 'index'])->name('tambahakun');
    Route::post('/tambahakun', [TambahAkunController::class, 'TambahAkun'])->name('TambahAkun');
    Route::put('/tambahakun/{id}', [TambahAkunController::class, 'EditAkun'])->name('EditAkun');
    Route::delete('/tambahakun/{id}', [TambahAkunController::class, 'HapusAkun'])->name('HapusAkun');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
