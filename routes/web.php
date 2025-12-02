<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\PtkController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LockScreenController;


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

/*
|--------------------------------------------------------------------------
| Routes UI
|--------------------------------------------------------------------------
*/

Route::get('/ui', [UiController::class, 'index'])->name('ui');
/*
|--------------------------------------------------------------------------
| End Routes UI
|--------------------------------------------------------------------------
*/


Route::get('/', function () {
    //return view('home.index');
    return redirect()->route('login');
});
Route::get('/auth/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/auth/authenticate/logout', [AuthController::class, 'logout'])->name('logout');


//Tanparagu
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Kegiatan Routes
Route::post('/kegiatan/extend/{kegiatan_id}', [KegiatanController::class, 'extend']);
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/get/{kegiatan_id}', [KegiatanController::class, 'get'])->name('kegiatan.get');
Route::delete('/kegiatan/delete/{kegiatan_id}', [KegiatanController::class, 'delete'])->name('kegiatan.delete');
Route::post('/kegiatan/submit', [KegiatanController::class, 'store'])->name('kegiatan.store');

//Lockscreen Routes
Route::get('/lockscreen/{encode_kegiatan_id}', [LockScreenController::class, 'index'])->name('lockscreen');
Route::post('/lockscreen/unlock', [LockScreenController::class, 'unlock_screen'])->name('lockscreen.unlock');
Route::get('/lockscreen/logout', [LockScreenController::class, 'logout'])->name('lockscreen.logout');


// 3. Route untuk cek session (opsional, untuk AJAX)
Route::get('/check-session', function () {
    return response()->json([
        'authenticated' => session()->has('lockscreen_authenticated'),
        'kegiatan_name' => session('kegiatan_name'),
        'kegiatan_id' => session('lockscreen_kegiatan_id')
    ]);
});

// PTK Routes
Route::get('/ptk', [PtkController::class, 'index'])->name('ptk');
Route::get('/ptk/{nik}', [PtkController::class, 'search'])->name('ptk.search');
Route::get('/ptk/start/{kegiatan_id}', [PtkController::class, 'startQuiz'])->name('ptk.start-quiz');
Route::get('/ptk/continue/{kegiatan_id}', [PtkController::class, 'continueQuiz'])->name('ptk.continue-quiz');

// Quiz Routes (existing routes)
Route::get('/quiz/{sub_indikator_id}/{no_urut}', [SoalController::class, 'quiz'])->name('quiz.show');
Route::post('/quiz/submit', [SoalController::class, 'submit'])->name('quiz.submit');

// Instrumen Routes (existing routes)
Route::get('/instrumen/survey', [InstrumenController::class, 'index'])->name('instrumen');
Route::get('/instrumen/soal/{sub_indikator_id}', [SoalController::class, 'getSoalBySubIndikator'])->name('getSoalBySubIndikator');

// Soal Routes (existing routes)
Route::get('/soal/{soal_id}', [SoalController::class, 'getSingleSoal']);
Route::post('/submit-jawaban', [SoalController::class, 'submitJawaban']);

//yusup