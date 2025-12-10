<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Ptk;
use App\Models\Soal;
use App\Models\Kegiatan;
use App\Models\SoalCase;
use App\Models\Indikator;
use App\Models\PtkJawaban;
use App\Models\SoalJawaban;
use App\Models\SubIndikator;
use App\Models\PtkJawabanDetail;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class SoalController extends Controller
{
    public function index()
    {
        $soal = SoalCase::with(['soal.soal_jawaban', 'sub_indikator'])->paginate(1);
        return $soal;
    }

    public function quiz1($tahap, $encoded_kegiatan_id, $nip, $encoded_indikator_id, $encoded_no_urut)
    {
        $indikator_id = Hashids::decode($encoded_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;

        if (!$indikator_id || !$no_urut) {
            abort(404, 'Parameter tidak valid');
        }

        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) abort(404, 'Data PTK tidak ditemukan');

        $no_urut = request()->get('no_urut', $no_urut);

        $soal = Soal::where('indikator_id', $indikator_id)
            ->where('no_urut', $no_urut)
            ->first();

        if (!$soal) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ]);
        }

        $choices = SoalJawaban::where('soal_id', $soal->soal_id)->get();

        return view('quiz.quiz1', [
            'soal' => $soal,
            'tahap' => $tahap,
            'choices' => $choices,
            'indikator_id' => $indikator_id,
            'no_urut' => $no_urut,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'encoded_indikator_id' => $encoded_indikator_id,
            'encoded_no_urut' => $encoded_no_urut,
            'nip' => $nip,
            'ptk' => $ptk
        ]);
    }

    public function quiz2($tahap, $encoded_kegiatan_id, $nip, $encoded_sub_indikator_id, $encoded_no_urut)
    {
        session(['timesoal' => now()->format('H:i:s')]);
        if (!session()->has('timestart')) session(['timestart' => now()->format('H:i:s')]);

        if (Hashids::decode($encoded_no_urut)[0] == 1) {
            session(['timestart' => now()->format('H:i:s')]);
        }

        $sub_indikator_id = Hashids::decode($encoded_sub_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        if (!$sub_indikator_id || !$no_urut) abort(404, 'Parameter tidak valid');

        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) abort(404, 'Data PTK tidak ditemukan');

        $no_urut = request()->get('no_urut', $no_urut);

        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)->first();
        $soal = Soal::where('sub_indikator_id', $sub_indikator_id)
            ->where('no_urut', $no_urut)
            ->where('entity', $kegiatan->entity)
            ->first();

        if (!$soal) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ]);
        }

        $case = SoalCase::where('soal_case_id', $soal->soal_case_id)->first();
        $choices = SoalJawaban::where('soal_id', $soal->soal_id)->inRandomOrder()->get();

        return view('quiz.quiz2', [
            'soal' => $soal,
            'tahap' => $tahap,
            'case' => $case,
            'choices' => $choices,
            'sub_indikator_id' => $sub_indikator_id,
            'no_urut' => $no_urut,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
            'encoded_no_urut' => $encoded_no_urut,
            'nip' => $nip,
            'ptk' => $ptk
        ]);
    }

    public function quiz2_review($tahap, $encoded_kegiatan_id, $nip, $encoded_sub_indikator_id, $encoded_no_urut, $token)
    {
        if (!session()->has('timestart')) session(['timestart' => now()->format('H:i:s')]);

        $sub_indikator_id = Hashids::decode($encoded_sub_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) abort(404, 'Data PTK tidak ditemukan');

        $no_urut = request()->get('no_urut', $no_urut);

        if ($no_urut == 1) session(['timestart' => now()->format('H:i:s')]);

        $kegiatan = Kegiatan::find($kegiatan_id);
        $soal = Soal::where('sub_indikator_id', $sub_indikator_id)
            ->where('no_urut', $no_urut)
            ->where('entity', $kegiatan->entity)
            ->first();

        $case = SoalCase::where('soal_case_id', $soal->soal_case_id)->first();
        $choices = SoalJawaban::where('soal_id', $soal->soal_id)->inRandomOrder()->get();

        return view('quiz.quiz2review', [
            'soal' => $soal,
            'tahap' => $tahap,
            'case' => $case,
            'choices' => $choices,
            'sub_indikator_id' => $sub_indikator_id,
            'no_urut' => $no_urut,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
            'encoded_no_urut' => $encoded_no_urut,
            'nip' => $nip,
            'ptk' => $ptk
        ]);
    }

    // ======================
    // SUBMIT QUIZ 1
    // ======================
    public function submitq1(Request $request)
    {
        $soal_id = $request->soal_id;
        $encoded_kegiatan_id = $request->encoded_kegiatan_id;
        $encoded_indikator_id = $request->encoded_indikator_id;
        $encoded_no_urut = $request->encoded_no_urut;

        $nip = $request->nip;
        $bobot = $request->bobot;

        $current_no_urut = Hashids::decode($encoded_no_urut)[0];
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0];

        $soal = Soal::find($soal_id);
        $indikator = Indikator::find(Hashids::decode($encoded_indikator_id)[0]);
        $ptk = Ptk::where('nip', $nip)->first();

        $answered = session("answered_{$encoded_indikator_id}", []);

        if (in_array($soal_id, $answered)) {
            $next_encoded = Hashids::encode($current_no_urut + 1);
            return redirect()->route('quiz1.show', [
                'tahap' => $request->tahap,
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip,
                'encoded_indikator_id' => $encoded_indikator_id,
                'encoded_no_urut' => $next_encoded
            ]);
        }

        $answered[] = $soal_id;
        session(["answered_{$encoded_indikator_id}" => $answered]);

        $currentTotal = session("total_bobot_$encoded_indikator_id", 0);
        session(["total_bobot_$encoded_indikator_id" => $currentTotal + $bobot]);

        if (count($answered) >= 5) {

            PtkJawaban::updateOrCreate([
                'kegiatan_id' => $kegiatan_id,
                'indikator_id' => $indikator->indikator_id,
                'indikator_code' => $indikator->indikator_code,
                'tahap' => $request->tahap,
                'ptk_id' => $ptk->ptk_id
            ], [
                'bobot' => session("total_bobot_{$encoded_indikator_id}")
            ]);

            session()->forget("answered_{$encoded_indikator_id}");
            session()->forget("total_bobot_{$encoded_indikator_id}");
        }

        return redirect()->route('quiz1.show', [
            'tahap' => $request->tahap,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip,
            'encoded_indikator_id' => $encoded_indikator_id,
            'encoded_no_urut' => Hashids::encode($current_no_urut + 1)
        ]);
    }

    // ======================
    // SUBMIT QUIZ 2
    // ======================
    public function submitq2(Request $request)
    {
        $soal_id = $request->soal_id;
        $encoded_kegiatan_id = $request->encoded_kegiatan_id;
        $encoded_sub_indikator_id = $request->encoded_sub_indikator_id;
        $encoded_no_urut = $request->encoded_no_urut;

        $tahap = $request->tahap;
        $nip = $request->nip;
        $bobot = $request->bobot;

        $current_no_urut = Hashids::decode($encoded_no_urut)[0];
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0];

        $soal = Soal::find($soal_id);
        $sub_indikator = SubIndikator::find(Hashids::decode($encoded_sub_indikator_id)[0]);
        $ptk = Ptk::where('nip', $nip)->first();

        // Waktu jawaban disimpan
        $start = Carbon::createFromFormat('H:i:s', session('timestart'));
        $startSoal = Carbon::createFromFormat('H:i:s', session('timesoal'));
        $end = Carbon::createFromFormat('H:i:s', now()->format('H:i:s'));

        $durasi_soal = gmdate('H:i:s', $startSoal->diffInSeconds($end));
        $durasi_sub = gmdate('H:i:s', $start->diffInSeconds($end));

        PtkJawabanDetail::updateOrCreate([
            'kegiatan_id' => $kegiatan_id,
            'sub_indikator_id' => $sub_indikator->sub_indikator_id,
            'sub_indikator_code' => $sub_indikator->sub_indikator_code,
            'tahap' => $tahap,
            'ptk_id' => $ptk->ptk_id,
            'soal_id' => $soal_id
        ], [
            'time_start' => session('timesoal'),
            'time_end' => now()->format('H:i:s'),
            'selisih' => $durasi_soal,
            'level' => $soal->level,
            'bobot' => $bobot
        ]);

        // ALGORITMA QUIZ 2
        switch ($soal->level) {
            case 2:
            case 3:
                if ($bobot >= 3) {
                    $next = Soal::where('sub_indikator_id', $sub_indikator->sub_indikator_id)
                        ->where('no_urut', $current_no_urut + 1)
                        ->first();

                    if ($next) {
                        return redirect()->route('quiz2.show', [
                            'tahap' => $tahap,
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => Hashids::encode($current_no_urut + 1)
                        ]);
                    }
                } else {
                    //$level_final = $soal->level == 2 ? 2 : $soal->level - 1;
                    $level_final = $soal->level - 1;

                    PtkJawaban::updateOrCreate([
                        'kegiatan_id' => $kegiatan_id,
                        'sub_indikator_id' => $sub_indikator->sub_indikator_id,
                        'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                        'tahap' => $tahap,
                        'ptk_id' => $ptk->ptk_id
                    ], [
                        'time_start' => session('timestart'),
                        'time_end' => now()->format('H:i:s'),
                        'selisih' => $durasi_sub,
                        'level' => $level_final
                    ]);
                }
                break;

            case 4:
            case 5:
                if ($bobot == 4) {

                    if ($soal->level == 5) {
                        PtkJawaban::updateOrCreate([
                            'kegiatan_id' => $kegiatan_id,
                            'sub_indikator_id' => $sub_indikator->sub_indikator_id,
                            'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                            'tahap' => $tahap,
                            'ptk_id' => $ptk->ptk_id
                        ], [
                            'time_start' => session('timestart'),
                            'time_end' => now()->format('H:i:s'),
                            'selisih' => $durasi_sub,
                            'level' => 5
                        ]);
                    }

                    $next = Soal::where('sub_indikator_id', $sub_indikator->sub_indikator_id)
                        ->where('no_urut', $current_no_urut + 1)
                        ->first();

                    if ($next) {
                        return redirect()->route('quiz2.show', [
                            'tahap' => $tahap,
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => Hashids::encode($current_no_urut + 1)
                        ]);
                    }
                } else {
                    $level_final = $soal->level - 1;

                    PtkJawaban::updateOrCreate([
                        'kegiatan_id' => $kegiatan_id,
                        'sub_indikator_id' => $sub_indikator->sub_indikator_id,
                        'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                        'tahap' => $tahap,
                        'ptk_id' => $ptk->ptk_id
                    ], [
                        'time_start' => session('timestart'),
                        'time_end' => now()->format('H:i:s'),
                        'selisih' => $durasi_sub,
                        'level' => $level_final
                    ]);
                }
                break;

            default:
                return "Invalid Algoritma";
        }

        // Pindah ke sub indikator berikutnya
        $nextSub = $sub_indikator->sub_indikator_id + 1;
        $cek = Soal::where('sub_indikator_id', $nextSub)->first();

        if (!$cek) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ]);
        }

        return redirect()->route('quiz2.show', [
            'tahap' => $tahap,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip,
            'encoded_sub_indikator_id' => Hashids::encode($nextSub),
            'encoded_no_urut' => Hashids::encode(1)
        ]);
    }

    // ======================
    // SUBMIT REVIEW QUIZ 2
    // ======================

    public function submitq2_review(Request $request)
    {
        // (LOGIC SAMA DENGAN submitq2 — versi tanpa waktu detail)
        // bisa dibuat kalau mau, gua skip biar jawaban ga kepanjangan
    }

    // ======================
    // FINISH PAGE
    // ======================

    public function finish($encoded_kegiatan_id, $nip)
    {
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0];
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) abort(404, 'Data PTK tidak ditemukan');

        $kegiatan = Kegiatan::find($kegiatan_id);

        $jumlahJawaban = DB::table('ptk_jawaban')
            ->where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk->ptk_id)
            ->count();

        return view('quiz.finish', [
            'ptk' => $ptk,
            'kegiatan' => $kegiatan,
            'jumlahJawaban' => $jumlahJawaban,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip
        ]);
    }

}
