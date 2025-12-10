<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UiController;
use App\Http\Controllers\PtkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LockScreenController;
use App\Http\Controllers\HasilInstrumenController;
use App\Http\Controllers\InstrumenController;

/*
|--------------------------------------------------------------------------
| Routes UI
|--------------------------------------------------------------------------
*/

Route::get('/ui', [UiController::class, 'index'])->name('ui');
Route::get('/grafik', [UiController::class, 'index'])->name('ui.grafik');

/*
|--------------------------------------------------------------------------
| Default Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/auth/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/auth/authenticate/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Tanparagu Home
|--------------------------------------------------------------------------
*/

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Kegiatan Routes
|--------------------------------------------------------------------------
*/

Route::post('/kegiatan/extend/{kegiatan_id}', [KegiatanController::class, 'extend']);
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/get/{kegiatan_id}', [KegiatanController::class, 'get'])->name('kegiatan.get');
Route::delete('/kegiatan/delete/{kegiatan_id}', [KegiatanController::class, 'delete'])->name('kegiatan.delete');
Route::post('/kegiatan/submit', [KegiatanController::class, 'store'])->name('kegiatan.store');

/*
|--------------------------------------------------------------------------
| Lockscreen Routes
|--------------------------------------------------------------------------
*/

Route::get('/lockscreen/{encode_kegiatan_id}', [LockScreenController::class, 'index'])->name('lockscreen');
Route::post('/lockscreen/authenticate', [LockScreenController::class, 'authenticate'])->name('lockscreen.authenticate');
Route::post('/lockscreen/register', [LockScreenController::class, 'register'])->name('lockscreen.register');
Route::get('/lockscreen/logout', [LockScreenController::class, 'logout'])->name('lockscreen.logout');

Route::get('/error/inactive-kegiatan', function () {
    return view('errors.inactive-kegiatan', [
        'title' => 'Kegiatan Tidak Aktif',
        'message' => 'Kegiatan sudah tidak aktif. Silakan hubungi administrator untuk informasi lebih lanjut.'
    ]);
})->name('error.inactive-kegiatan');

/*
|--------------------------------------------------------------------------
| PTK Routes
|--------------------------------------------------------------------------
*/

Route::get('/ptk/riwayat/kegiatan/{encode_kegiatan_id}/user/{nip}', [PtkController::class, 'riwayatKegiatan'])->name('ptk.riwayat');

Route::get('/ptk/detail-riwayat/{encode_kegiatan_id}/user/{nip}', [PtkController::class, 'detailRiwayat'])->name('ptk.detailriwayat');

Route::get('/ptk/kegiatan/{encode_kegiatan_id}/user/{nip}', [PtkController::class, 'index'])->name('ptk.show');
Route::get('/ptk/start/{encode_kegiatan_id}/{nip}', [PtkController::class, 'startQuiz'])->name('ptk.start-quiz');
Route::get('/ptk/continue/{encode_kegiatan_id}/{nip}', [PtkController::class, 'continueQuiz'])->name('ptk.continue-quiz');

/*
|--------------------------------------------------------------------------
| Quiz Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/quiz/tahap/{tahap}/kegiatan/{encoded_kegiatan_id}/user/{nip}/ind/{encoded_indikator_id}/no/{encoded_no_urut}',
    [SoalController::class, 'quiz1']
)->name('quiz1.show');

Route::get(
    '/quiz/tahap/{tahap}/kegiatan/{encoded_kegiatan_id}/user/{nip}/sub/{encoded_sub_indikator_id}/no/{encoded_no_urut}',
    [SoalController::class, 'quiz2']
)->name('quiz2.show');

Route::get(
    '/quiz/tahap/{tahap}/kegiatan/{encoded_kegiatan_id}/user/{nip}/sub/{encoded_sub_indikator_id}/no/{encoded_no_urut}/review/{token}',
    [SoalController::class, 'quiz2_review']
)->name('quiz2review.show');

Route::post('/quiz1/submit', [SoalController::class, 'submitq1'])->name('quiz1.submit');
Route::post('/quiz2/submit', [SoalController::class, 'submitq2'])->name('quiz2.submit');
Route::post('/submitq2_review/submit', [SoalController::class, 'submitq2_review'])->name('submitq2_review.submit');

Route::get('/quiz/finish/{encoded_kegiatan_id}/{nip}', [SoalController::class, 'finish'])->name('quiz.finish');
Route::get('/quiz/finish', [SoalController::class, 'finish'])->name('quiz.finish');

/*
|--------------------------------------------------------------------------
| Instrumen Routes
|--------------------------------------------------------------------------
*/

Route::get('/instrumen/survey', [InstrumenController::class, 'index'])->name('instrumen');
Route::get('/instrumen/soal/{sub_indikator_id}', [SoalController::class, 'getSoalBySubIndikator'])->name('getSoalBySubIndikator');

/*
|--------------------------------------------------------------------------
| Soal Routes
|--------------------------------------------------------------------------
*/

Route::get('/soal/{soal_id}', [SoalController::class, 'getSingleSoal']);
Route::post('/submit-jawaban', [SoalController::class, 'submitJawaban']);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/api/search-sekolah', [LockScreenController::class, 'searchSekolah'])->name('api.search-sekolah');

/*
|--------------------------------------------------------------------------
| Hasil Instrumen Routes
|--------------------------------------------------------------------------
*/

Route::get('/hasil-instrumen', [HasilInstrumenController::class, 'index'])->name('hasil-instrumen.index');
Route::get('/hasil-instrumen/export/{ptk_id}', [HasilInstrumenController::class, 'export'])->name('hasil-instrumen.export');
Route::get('/hasil-instrumen/export-all', [HasilInstrumenController::class, 'exportAllPdf'])->name('hasil-instrumen.export-all');
Route::get('/hasil-instrumen/export-excel', [HasilInstrumenController::class, 'exportExcel'])->name('hasil-instrumen.export-excel');
Route::delete('/ptk-jawaban/delete/{id}', [HasilInstrumenController::class, 'destroy'])->name('ptk-jawaban.destroy');
