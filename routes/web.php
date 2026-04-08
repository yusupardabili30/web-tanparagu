<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UiController;
use App\Http\Controllers\PtkController;
use App\Http\Controllers\PtkEditController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LockScreenController;
use App\Http\Controllers\ApiLockScreenController;
use App\Http\Controllers\HasilInstrumenController;
use App\Http\Controllers\InstrumenController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\AnalisisRekomendasiGapController;
use App\Http\Controllers\ManagementUsersController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\ExportGapController;

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
| Biodata Regiester Routes
|--------------------------------------------------------------------------
*/
Route::prefix('biodata')->name('biodata.')->group(function () {
    Route::get('/', [BiodataController::class, 'index'])->name('index');
    Route::get('/pdf/{id}', [BiodataController::class, 'exportPdf'])->name('exportPdf');
    Route::get('/pdf-all/export', [BiodataController::class, 'exportAllPdf'])->name('exportAllPdf');
});
/*
|--------------------------------------------------------------------------
| Register Routes
|--------------------------------------------------------------------------
*/

// Route untuk register peserta
// Tambahkan route ini di bawah route register yang sudah ada
Route::get('/register/{encode_kegiatan_id}', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register/{encode_kegiatan_id}', [RegisterController::class, 'store'])->name('register.store');
Route::get('/register/{encode_kegiatan_id}/success', [RegisterController::class, 'success'])->name('register.success');

// Cek NIP + kegiatan_id (untuk autofill & double reg)
Route::get('/register/{encode_kegiatan_id}/cek-nip', [RegisterController::class, 'cekNipKegiatan'])
    ->name('register.cek-nip');


// Route untuk info sekolah lengkap
Route::get('/api/sekolah/{id}/info', function ($id) {
    $sekolah = \App\Models\Sekolah::find($id);

    if (!$sekolah) {
        return response()->json([
            'success' => false,
            'message' => 'Sekolah tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'nama_sekolah' => $sekolah->nama_sekolah,
        'npsn' => $sekolah->npsn,
        'alamat' => $sekolah->alamat,
        'kota' => $sekolah->kab_kota,
        'data' => $sekolah
    ]);
});

// Route untuk search sekolah (AJAX)
Route::get('/search/sekolah', [RegisterController::class, 'searchSekolah'])->name('search.sekolah');
// Route untuk mendapatkan alamat sekolah
Route::get('/api/sekolah/{id}/alamat', [RegisterController::class, 'getSekolahAlamat']);
Route::get('/lockscreen/api/search-sekolah-dapodik', [ApiLockscreenController::class, 'searchSekolahDapodik'])
    ->name('lockscreen.api.search-sekolah-dapodik');
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
Route::get('/lockscreen/api/cek-nip', [ApiLockscreenController::class, 'cekNip'])->name('lockscreen.api.cek-nip');
Route::get('/lockscreen/api/cek-nik', [ApiLockscreenController::class, 'cekNik'])->name('lockscreen.api.cek-nik');
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


Route::get(
    '/quiz/case-list/{encoded_kegiatan_id}/{nip}',
    [SoalController::class, 'getCaseList']
)->name('quiz.case.list');

// Route untuk mendapatkan list indikator dengan status
// Route untuk mendapatkan list indikator quiz 1
Route::get('/quiz1/case-list/{encoded_kegiatan_id}/{nip}', [SoalController::class, 'getCaseListQuiz1'])
    ->name('quiz1.case-list');


// web.php - tambahkan route ini
Route::get('/analisis', [AnalisisController::class, 'index'])->name('analisis.index');
Route::get('/ptk/continue/{encode_kegiatan_id}/{nip}', [PtkController::class, 'continueQuiz'])->name('ptk.continue-quiz');


// Pastikan di web.php routes sudah tepat
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [ManagementUsersController::class, 'index'])->name('index');
    Route::get('/get/{user_id}', [ManagementUsersController::class, 'get'])->name('get');
    Route::post('/submit', [ManagementUsersController::class, 'store'])->name('store'); // Perhatikan ini!
    Route::delete('/delete/{user_id}', [ManagementUsersController::class, 'delete'])->name('delete');
    Route::put('/reset-password/{user_id}', [ManagementUsersController::class, 'resetPassword'])->name('reset-password');
});



// Di dalam group yang sama dengan route analisis lainnya
Route::get('/analisis/ptk-belum-menjawab', [AnalisisController::class, 'getPtkBelumMenjawab'])->name('analisis.ptk-belum-menjawab');


Route::get('/ptk/export-hasil/{encode_kegiatan_id}/{nip}', [PtkController::class, 'exportHasilPdf'])
    ->name('ptk.export-hasil');


Route::get('/analisis/rekomendasi-gap', [AnalisisRekomendasiGapController::class, 'index'])
    ->name('analisis.rekomendasi-gap.index');
Route::get('/api/kegiatan/{id}/entity', [AnalisisController::class, 'getKegiatanEntity']);








/*
|--------------------------------------------------------------------------
| PTK Edit Routes (Edit Biodata)
|--------------------------------------------------------------------------
*/

Route::prefix('ptk/edit')->name('ptk.edit.')->group(function () {
    Route::get('/{encode_kegiatan_id}/{nip}', [PtkEditController::class, 'edit'])->name('index');
    Route::put('/{encode_kegiatan_id}/{nip}', [PtkEditController::class, 'update'])->name('update');
    Route::get('/search-sekolah', [PtkEditController::class, 'searchSekolah'])->name('search-sekolah');
    Route::get('/search-sekolah-dapodik', [PtkEditController::class, 'searchSekolahDapodik'])->name('search-sekolah-dapodik');
});



// Tambahkan di web.php
Route::prefix('pelatihan')->name('pelatihan.')->group(function () {
    Route::get('/{encoded_kegiatan_id}/{nip}', [PelatihanController::class, 'index'])
        ->name('index');

    Route::post('/{encoded_kegiatan_id}/{nip}/store', [PelatihanController::class, 'store'])
        ->name('store');
});




// // Tambahkan ini di atas route
// ini_set('memory_limit', '512M');
// ini_set('max_execution_time', 300); // 5 menit

Route::get('/hasil-instrumen/export-excel-all', [HasilInstrumenController::class, 'exportExcelAll'])->name('hasil-instrumen.export-excel-all');



// routes/web.php
Route::post('/analisis/export-excel', [AnalisisController::class, 'exportExcel'])->name('analisis.export-excel');
// ATAU
Route::get('/analisis/export-excel', [AnalisisController::class, 'exportExcel'])->name('analisis.export-excel');




// routes/web.php - tambahkan route ini
Route::get('/export/rekomendasi-gap', [ExportGapController::class, 'exportRekomendasiGap'])
    ->name('export.rekomendasi-gap');
