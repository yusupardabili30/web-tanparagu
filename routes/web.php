<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SoalController;
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

Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/get/{kegiatan_id}', [KegiatanController::class, 'get'])->name('kegiatan.get');
Route::post('/kegiatan/submit', [KegiatanController::class, 'store'])->name('kegiatan.store');

Route::get('/lockscreen/{encode_kegiatan_id}', [LockScreenController::class, 'index'])->name('lockscreen');
Route::post('/lockscreen/unlock', [LockScreenController::class, 'unlock_screen'])->name('lockscreen.unlock');

Route::get('/ptk', [PtkController::class, 'index'])->name('ptk');
Route::get('/ptk/{nik}', [PtkController::class, 'search'])->name('ptk.search');

Route::get('/quiz/{sub_indikator_id}/{no_urut}', [SoalController::class, 'quiz'])->name('quiz.show');
Route::post('/quiz/submit', [SoalController::class, 'submit'])->name('quiz.submit');

Route::get('/instrumen/survey', [InstrumenController::class, 'index'])->name('instrumen');
Route::get('/instrumen/soal/{sub_indikator_id}', [SoalController::class, 'getSoalBySubIndikator'])->name('getSoalBySubIndikator');

Route::get('/soal/{soal_id}', [SoalController::class, 'getSingleSoal']);
Route::post('/submit-jawaban', [SoalController::class, 'submitJawaban']);



