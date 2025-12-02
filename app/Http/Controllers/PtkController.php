<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class PtkController extends Controller
{
    public function index()
    {
        // Periksa apakah sudah login melalui lockscreen
        if (!session()->has('lockscreen_authenticated')) {
            return redirect('/')->with('error', 'Silakan login melalui lockscreen terlebih dahulu');
        }

        // Ambil kegiatan_id dari session
        $kegiatan_id = session('lockscreen_kegiatan_id');

        // Ambil data kegiatan lengkap
        $kegiatan = Kegiatan::find($kegiatan_id);

        if (!$kegiatan) {
            session()->forget(['lockscreen_authenticated', 'lockscreen_kegiatan_id', 'kegiatan_name']);
            return redirect('/')->with('error', 'Kegiatan tidak ditemukan');
        }

        // Periksa apakah kegiatan masih aktif
        if ($kegiatan->status !== 'Active') {
            session()->forget(['lockscreen_authenticated', 'lockscreen_kegiatan_id', 'kegiatan_name']);
            return redirect('/')->with('error', 'Kegiatan sudah tidak aktif');
        }

        // Simpan token ke session
        session(['kegiatan_token' => $kegiatan->instrumen_token]);

        // Format tanggal untuk display
        $start_date = \Carbon\Carbon::parse($kegiatan->start_date)->format('d/m/Y');
        $end_date = \Carbon\Carbon::parse($kegiatan->end_date)->format('d/m/Y');

        return view('ptk.index', [
            'title' => 'Kegiatan - ' . $kegiatan->kegiatan_name,
            'kegiatan' => $kegiatan,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function search($nik)
    {
        // Implementasi pencarian PTK jika diperlukan
        if (!session()->has('lockscreen_authenticated')) {
            return redirect('/')->with('error', 'Silakan login melalui lockscreen terlebih dahulu');
        }

        // Logika pencarian...
    }

    // Method untuk mulai quiz
    public function startQuiz($kegiatan_id)
    {
        // Periksa session lockscreen
        if (!session()->has('lockscreen_authenticated')) {
            return redirect('/')->with('error', 'Sesi telah berakhir, silakan login kembali');
        }

        // Verifikasi kegiatan_id sesuai dengan session
        $session_kegiatan_id = session('lockscreen_kegiatan_id');
        if ($kegiatan_id != $session_kegiatan_id) {
            return redirect()->route('ptk')->with('error', 'Akses tidak valid');
        }

        // Simpan informasi bahwa quiz sudah dimulai
        session(['quiz_started' => true]);

        // Redirect ke halaman quiz pertama
        return redirect()->route('quiz.show', [
            'sub_indikator_id' => 1, // ID sub indikator pertama
            'no_urut' => 1 // Soal pertama
        ]);
    }

    // Method untuk melihat progress quiz
    public function continueQuiz($kegiatan_id)
    {
        // Periksa session lockscreen
        if (!session()->has('lockscreen_authenticated')) {
            return redirect('/')->with('error', 'Sesi telah berakhir, silakan login kembali');
        }

        // Verifikasi kegiatan_id sesuai dengan session
        $session_kegiatan_id = session('lockscreen_kegiatan_id');
        if ($kegiatan_id != $session_kegiatan_id) {
            return redirect()->route('ptk')->with('error', 'Akses tidak valid');
        }

        // Ambil progress terakhir dari database atau session
        // Misalnya: ambil sub_indikator_id dan no_urut terakhir yang dijawab
        $last_progress = $this->getLastProgress($kegiatan_id);

        return redirect()->route('quiz.show', [
            'sub_indikator_id' => $last_progress['sub_indikator_id'],
            'no_urut' => $last_progress['no_urut']
        ]);
    }

    // Method untuk mendapatkan progress terakhir (contoh)
    private function getLastProgress($kegiatan_id)
    {
        // Di sini Anda bisa query ke database untuk mendapatkan progress
        // Misalnya dari tabel jawaban atau session
        // Contoh sederhana:
        return [
            'sub_indikator_id' => 1,
            'no_urut' => 1
        ];
    }
}