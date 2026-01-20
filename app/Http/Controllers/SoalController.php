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

    // ======================
    // GET STUDI KASUS LIST DENGAN STATUS BENAR
    // ======================
    public function getCaseList($encoded_kegiatan_id, $nip)
    {
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;
        $ptk = Ptk::where('nip', $nip)->first();

        if (!$ptk || !$kegiatan_id) {
            return response()->json([]);
        }

        $kegiatan = Kegiatan::find($kegiatan_id);
        $entity = $kegiatan->entity ?? null;

        // Ambil semua studi kasus berdasarkan entity
        $cases = SoalCase::where('entity', $entity)
            ->orderBy('sub_indikator_id')
            ->orderBy('no_urut')
            ->get();

        // Ambil data jawaban user dengan status "sudah melewati"
        $passedCases = DB::table('ptk_jawaban as pj')
            ->join('sub_indikator as si', 'si.sub_indikator_id', '=', 'pj.sub_indikator_id')
            ->join('soal_case as sc', 'sc.sub_indikator_id', '=', 'si.sub_indikator_id')
            ->where('pj.kegiatan_id', $kegiatan_id)
            ->where('pj.ptk_id', $ptk->ptk_id)
            ->where('pj.tahap', 2) // Quiz 2
            ->where('sc.entity', $entity)
            ->whereNotNull('pj.level') // Sudah mendapatkan level final
            ->select('sc.soal_case_id')
            ->distinct()
            ->pluck('soal_case_id')
            ->toArray();

        $formattedCases = [];
        foreach ($cases as $case) {
            $formattedCases[] = [
                'soal_case_id' => $case->soal_case_id,
                'title' => $case->tittle ?? "Studi Kasus " . $case->no_urut,
                'sub_indikator_id' => $case->sub_indikator_id,
                'no_urut' => $case->no_urut,
                'is_passed' => in_array($case->soal_case_id, $passedCases),
                'status_icon' => in_array($case->soal_case_id, $passedCases)
                    ? 'ri-checkbox-circle-line text-success'
                    : 'ri-checkbox-blank-circle-line text-secondary'
            ];
        }

        return response()->json($formattedCases);
    }


    // Modifikasi method quiz1 untuk menambahkan caseList
    public function quiz1($tahap, $encoded_kegiatan_id, $nip, $encoded_indikator_id, $encoded_no_urut)
    {
        $indikator_id = Hashids::decode($encoded_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        if (!$indikator_id || !$no_urut) {
            abort(404, 'Parameter tidak valid');
        }

        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) abort(404, 'Data PTK tidak ditemukan');

        $kegiatan = Kegiatan::find($kegiatan_id);
        $soal = Soal::where('indikator_id', $indikator_id)
            ->where('no_urut', $no_urut)
            ->where('tahap', 1)
            ->where('entity', $kegiatan->entity)
            ->first();

        if (!$soal) {
            // Cek apakah ada indikator berikutnya
            $currentIndikator = Indikator::find($indikator_id);
            $nextIndikator = Indikator::where('tahap', 1)
                ->where('no_urut', '>', $currentIndikator->no_urut ?? 0)
                ->orderBy('no_urut')
                ->first();

            if ($nextIndikator) {
                // Pindah ke indikator berikutnya
                return redirect()->route('quiz1.show', [
                    'tahap' => $tahap,
                    'encoded_kegiatan_id' => $encoded_kegiatan_id,
                    'nip' => $nip,
                    'encoded_indikator_id' => Hashids::encode($nextIndikator->indikator_id),
                    'encoded_no_urut' => Hashids::encode(1)
                ]);
            } else {
                // Semua indikator selesai
                return redirect()->route('quiz.finish', [
                    'encoded_kegiatan_id' => $encoded_kegiatan_id,
                    'nip' => $nip
                ]);
            }
        }

        $choices = SoalJawaban::where('soal_id', $soal->soal_id)->inRandomOrder()->get();

        // ====================================================
        // AMBIL DATA INDIKATOR LIST UNTUK QUIZ 1
        // ====================================================
        $indikators = Indikator::where('tahap', 1)
            ->orderBy('no_urut')
            ->get();

        // Ambil data jawaban user yang sudah selesai
        $passedIndikators = DB::table('ptk_jawaban')
            ->where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk->ptk_id)
            ->where('tahap', 1)
            ->whereNotNull('bobot')
            ->pluck('indikator_id')
            ->toArray();

        $indikatorList = [];
        foreach ($indikators as $item) {
            // Cek apakah indikator ini memiliki soal untuk entity ini
            $hasSoal = Soal::where('indikator_id', $item->indikator_id)
                ->where('tahap', 1)
                ->where('entity', $kegiatan->entity)
                ->exists();

            if (!$hasSoal) {
                continue;
            }

            $indikatorList[] = [
                'indikator_id' => $item->indikator_id,
                'title' => $item->indikator_name ?? "Indikator " . $item->no_urut,
                'no_urut' => $item->no_urut,
                'is_passed' => in_array($item->indikator_id, $passedIndikators),
                'is_current' => $item->indikator_id == $indikator_id,
                'status_icon' => in_array($item->indikator_id, $passedIndikators)
                    ? 'ri-checkbox-circle-line text-success'
                    : 'ri-checkbox-blank-circle-line text-secondary'
            ];
        }

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
            'ptk' => $ptk,
            'kegiatan' => $kegiatan,

            'indikatorList' => $indikatorList,
            'currentIndikatorId' => $indikator_id
        ]);
    }

    public function quiz2($tahap, $encoded_kegiatan_id, $nip, $encoded_sub_indikator_id, $encoded_no_urut)
    {
        session(['timesoal' => now()->format('H:i:s')]);
        if (Hashids::decode($encoded_no_urut)[0] == 1) {
            session(['timestart' => now()->format('H:i:s')]);
        }

        $sub_indikator_id = Hashids::decode($encoded_sub_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        if (!$sub_indikator_id || !$no_urut) abort(404, 'Parameter tidak valid');

        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) abort(404, 'Data PTK tidak ditemukan');

        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)->first();

        // ==============================================
        // VALIDASI: CEK APAKAH SOAL SUDAH DIJAWAB
        // ==============================================
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

        // Cek apakah soal ini sudah dijawab
        $alreadyAnswered = PtkJawabanDetail::where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk->ptk_id)
            ->where('soal_id', $soal->soal_id)
            ->where('tahap', 2)
            ->exists();

        // Jika sudah dijawab, redirect ke soal berikutnya
        if ($alreadyAnswered) {
            // Cari soal berikutnya yang belum dijawab dalam sub_indikator yang sama
            $nextSoal = Soal::where('sub_indikator_id', $sub_indikator_id)
                ->where('no_urut', '>', $no_urut)
                ->where('entity', $kegiatan->entity)
                ->orderBy('no_urut')
                ->first();

            if ($nextSoal) {
                // Redirect ke soal berikutnya
                return redirect()->route('quiz2.show', [
                    'tahap' => $tahap,
                    'encoded_kegiatan_id' => $encoded_kegiatan_id,
                    'nip' => $nip,
                    'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                    'encoded_no_urut' => Hashids::encode($nextSoal->no_urut)
                ])->with('warning', 'Soal ini sudah dijawab. Anda dialihkan ke soal berikutnya.');
            } else {
                // Cek apakah sudah ada level final
                $finalLevel = PtkJawaban::where('kegiatan_id', $kegiatan_id)
                    ->where('ptk_id', $ptk->ptk_id)
                    ->where('sub_indikator_id', $sub_indikator_id)
                    ->where('tahap', 2)
                    ->whereNotNull('level')
                    ->exists();

                if ($finalLevel) {
                    // Cari sub indikator berikutnya
                    $nextSubIndikator = SubIndikator::where('sub_indikator_id', '>', $sub_indikator_id)
                        ->orderBy('sub_indikator_id')
                        ->first();

                    if ($nextSubIndikator) {
                        // Cek apakah ada soal untuk sub indikator berikutnya
                        $hasSoal = Soal::where('sub_indikator_id', $nextSubIndikator->sub_indikator_id)
                            ->where('entity', $kegiatan->entity)
                            ->exists();

                        if ($hasSoal) {
                            return redirect()->route('quiz2.show', [
                                'tahap' => $tahap,
                                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                                'nip' => $nip,
                                'encoded_sub_indikator_id' => Hashids::encode($nextSubIndikator->sub_indikator_id),
                                'encoded_no_urut' => Hashids::encode(1)
                            ])->with('info', 'Sub indikator ini sudah selesai. Anda dialihkan ke sub indikator berikutnya.');
                        }
                    }
                }
            }
        }

        // ==============================================
        // PERHITUNGAN WAKTU SISA
        // ==============================================
        $remaining_seconds = PtkJawabanDetail::getLatestRemainingTimeFromDatabase($kegiatan_id, $ptk->ptk_id, 2);

        // Debug: Catat waktu yang didapat dari database
        \Log::info("quiz2 - Remaining time from database", [
            'kegiatan_id' => $kegiatan_id,
            'ptk_id' => $ptk->ptk_id,
            'remaining_seconds' => $remaining_seconds,
            'formatted_time' => gmdate("H:i:s", $remaining_seconds)
        ]);

        $remaining_time_formatted = PtkJawabanDetail::formatRemainingTime($remaining_seconds);

        // Cek apakah waktu sudah habis
        if ($remaining_seconds <= 0) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ])->with('error', 'Waktu pengerjaan telah habis');
        }

        // ==============================================
        // SIMPAN WAKTU YANG SAMA UNTUK FRONTEND DAN BACKEND
        // ==============================================
        session(['current_remaining_seconds' => $remaining_seconds]);
        session(['quiz2_display_time' => $remaining_time_formatted]);

        // Reset flag untuk localStorage
        $resetLocalStorage = false;
        if (session()->has('reset_localstorage') && session('reset_localstorage') === true) {
            $resetLocalStorage = true;
            session()->forget('reset_localstorage');
        }

        // ==============================================
        // AMBIL DATA LAINNYA
        // ==============================================
        $case = SoalCase::where('soal_case_id', $soal->soal_case_id)->first();
        $choices = SoalJawaban::where('soal_id', $soal->soal_id)->inRandomOrder()->get();

        // Ambil data case list
        $entity = $kegiatan->entity ?? null;
        $cases = SoalCase::where('entity', $entity)
            ->orderBy('sub_indikator_id')
            ->orderBy('no_urut')
            ->get();

        $passedCases = DB::table('ptk_jawaban as pj')
            ->join('sub_indikator as si', 'si.sub_indikator_id', '=', 'pj.sub_indikator_id')
            ->join('soal_case as sc', 'sc.sub_indikator_id', '=', 'si.sub_indikator_id')
            ->where('pj.kegiatan_id', $kegiatan_id)
            ->where('pj.ptk_id', $ptk->ptk_id)
            ->where('pj.tahap', 2)
            ->where('sc.entity', $entity)
            ->whereNotNull('pj.level')
            ->select('sc.soal_case_id')
            ->distinct()
            ->pluck('soal_case_id')
            ->toArray();

        $caseList = [];
        foreach ($cases as $caseItem) {
            $caseList[] = [
                'soal_case_id' => $caseItem->soal_case_id,
                'title' => !empty($caseItem->tittle) ? $caseItem->tittle : "Studi Kasus " . $caseItem->no_urut,
                'sub_indikator_id' => $caseItem->sub_indikator_id,
                'no_urut' => $caseItem->no_urut,
                'is_passed' => in_array($caseItem->soal_case_id, $passedCases),
                'is_current' => $caseItem->soal_case_id == ($soal->soal_case_id ?? 0),
                'status_icon' => in_array($caseItem->soal_case_id, $passedCases)
                    ? 'ri-checkbox-circle-line text-success'
                    : 'ri-checkbox-blank-circle-line text-secondary'
            ];
        }

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
            'ptk' => $ptk,
            'kegiatan' => $kegiatan,
            'caseList' => $caseList,
            'currentCaseId' => $soal->soal_case_id ?? 0,
            'remaining_seconds' => $remaining_seconds,
            'remaining_time_formatted' => $remaining_time_formatted,
            'reset_localstorage' => $resetLocalStorage,
            'already_answered' => $alreadyAnswered // Kirim flag ke view
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

        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) abort(404, 'Data PTK tidak ditemukan');

        // ==============================================
        // VALIDASI: CEK APAKAH SOAL SUDAH DIJAWAB
        // ==============================================
        $alreadyAnswered = PtkJawabanDetail::where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk->ptk_id)
            ->where('soal_id', $soal_id)
            ->where('tahap', 2)
            ->exists();

        if ($alreadyAnswered) {
            // Jika sudah dijawab, redirect ke soal berikutnya dengan pesan
            $sub_indikator_id = Hashids::decode($encoded_sub_indikator_id)[0];

            $nextSoal = Soal::where('sub_indikator_id', $sub_indikator_id)
                ->where('no_urut', '>', $current_no_urut)
                ->orderBy('no_urut')
                ->first();

            if ($nextSoal) {
                return redirect()->route('quiz2.show', [
                    'tahap' => $tahap,
                    'encoded_kegiatan_id' => $encoded_kegiatan_id,
                    'nip' => $nip,
                    'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                    'encoded_no_urut' => Hashids::encode($nextSoal->no_urut)
                ])->with('warning', 'Soal ini sudah dijawab sebelumnya. Anda dialihkan ke soal berikutnya.');
            } else {
                // Cari sub indikator berikutnya
                $nextSubIndikator = SubIndikator::where('sub_indikator_id', '>', $sub_indikator_id)
                    ->orderBy('sub_indikator_id')
                    ->first();

                if ($nextSubIndikator) {
                    return redirect()->route('quiz2.show', [
                        'tahap' => $tahap,
                        'encoded_kegiatan_id' => $encoded_kegiatan_id,
                        'nip' => $nip,
                        'encoded_sub_indikator_id' => Hashids::encode($nextSubIndikator->sub_indikator_id),
                        'encoded_no_urut' => Hashids::encode(1)
                    ])->with('info', 'Anda sudah menyelesaikan sub indikator ini.');
                }
            }

            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ]);
        }



        $soal = Soal::find($soal_id);
        $sub_indikator = SubIndikator::find(Hashids::decode($encoded_sub_indikator_id)[0]);
        $ptk = Ptk::where('nip', $nip)->first();

        // ==============================================
        // HITUNG WAKTU PENGERJAAN SOAL INI
        // ==============================================
        $startSoal = Carbon::createFromFormat('H:i:s', session('timesoal') ?? now()->format('H:i:s'));
        $endSoal = Carbon::createFromFormat('H:i:s', now()->format('H:i:s'));
        $durasi_soal = gmdate('H:i:s', $startSoal->diffInSeconds($endSoal));





        // Waktu jawaban disimpan
        $timestart = session('timestart');

        if (!$timestart || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $timestart)) {
            // fallback kalau session hilang
            $timestart = now()->format('H:i:s');
            session(['timestart' => $timestart]);
        }


        $start = Carbon::createFromFormat('H:i:s', $timestart);
        $end   = Carbon::createFromFormat('H:i:s', now()->format('H:i:s'));
        $durasi_sub = gmdate('H:i:s', $start->diffInSeconds($end));




        // ==============================================
        // AMBIL WAKTU DARI FRONTEND (PRIORITAS UTAMA)
        // ==============================================
        $frontend_remaining_seconds = $request->remaining_seconds;
        $frontend_time_string = $request->frontend_time_string;

        $sisa_duration_time = '';
        $new_remaining_seconds = 0;

        // JIKA ADA WAKTU DARI FRONTEND, GUNAKAN ITU
        if ($frontend_remaining_seconds && is_numeric($frontend_remaining_seconds)) {
            $new_remaining_seconds = (int) $frontend_remaining_seconds;

            // Jika ada string waktu dari frontend, gunakan itu
            if ($frontend_time_string && preg_match('/^\d{2}:\d{2}:\d{2}$/', $frontend_time_string)) {
                $sisa_duration_time = $frontend_time_string;
            } else {
                // Format dari seconds
                $sisa_duration_time = PtkJawabanDetail::formatRemainingTime($new_remaining_seconds);
            }

            \Log::info("submitq2 - Menggunakan waktu dari FRONTEND", [
                'frontend_seconds' => $frontend_remaining_seconds,
                'frontend_time' => $frontend_time_string,
                'hasil_sisa_time' => $sisa_duration_time,
                'kegiatan_id' => $kegiatan_id,
                'ptk_id' => $ptk->ptk_id,
                'soal_id' => $soal_id
            ]);
        }
        // JIKA TIDAK ADA WAKTU DARI FRONTEND, HITUNG DARI DATABASE
        else {
            $previous_remaining = PtkJawabanDetail::getLatestRemainingTimeFromDatabase($kegiatan_id, $ptk->ptk_id, 2);
            $time_used = $startSoal->diffInSeconds($endSoal);
            $new_remaining_seconds = max(0, $previous_remaining - $time_used);
            $sisa_duration_time = PtkJawabanDetail::formatRemainingTime($new_remaining_seconds);

            \Log::warning("submitq2 - Waktu dari frontend TIDAK ADA, menggunakan perhitungan backend", [
                'previous_remaining' => $previous_remaining,
                'time_used' => $time_used,
                'new_remaining' => $new_remaining_seconds,
                'sisa_duration_time' => $sisa_duration_time
            ]);
        }

        // ==============================================
        // VALIDASI WAKTU TIDAK NEGATIF
        // ==============================================
        if ($new_remaining_seconds < 0) {
            $new_remaining_seconds = 0;
            $sisa_duration_time = '00:00:00';
        }

        // ==============================================
        // SIMPAN KE DATABASE
        // ==============================================
        $detail = PtkJawabanDetail::updateOrCreate([
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
            'sisa_duration_time' => $sisa_duration_time, // DISIMPAN SAMA DENGAN YANG DITAMPILKAN
            'bobot' => $bobot
        ]);

        // LOG PERBANDINGAN
        $calculated_from_db = PtkJawabanDetail::getLatestRemainingTimeFromDatabase($kegiatan_id, $ptk->ptk_id, 2);
        \Log::info("submitq2 - Perbandingan akhir", [
            'waktu_dari_frontend' => $frontend_time_string ?? 'TIDAK ADA',
            'waktu_dihitung_backend' => PtkJawabanDetail::formatRemainingTime($calculated_from_db),
            'waktu_disimpan' => $sisa_duration_time,
            'selisih' => abs($new_remaining_seconds - $calculated_from_db) . ' detik'
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
                            'tahap' => 2,
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => Hashids::encode($current_no_urut + 1)
                        ]);
                    }
                } else {
                    $level_final = $soal->level == 2 ? 2 : $soal->level - 1;

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



    // Di dalam class SoalController.php

    // ======================
    // GET STUDI KASUS LIST UNTUK QUIZ 1
    // ======================
    public function getCaseListQuiz1($encoded_kegiatan_id, $nip)
    {
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;
        $ptk = Ptk::where('nip', $nip)->first();

        if (!$ptk || !$kegiatan_id) {
            return response()->json([]);
        }

        $kegiatan = Kegiatan::find($kegiatan_id);
        $entity = $kegiatan->entity ?? null;

        // Ambil semua indikator berdasarkan entity dan tahap 1
        $indikators = Indikator::where('tahap', 1)
            ->orderBy('no_urut')
            ->get();

        // Ambil data jawaban user yang sudah selesai
        $passedIndikators = DB::table('ptk_jawaban')
            ->where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk->ptk_id)
            ->where('tahap', 1)
            ->whereNotNull('bobot') // Sudah menyelesaikan 5 soal
            ->pluck('indikator_id')
            ->toArray();

        $formattedIndikators = [];
        foreach ($indikators as $indikator) {
            // Cek apakah indikator ini sudah memiliki soal untuk entity ini
            $hasSoal = Soal::where('indikator_id', $indikator->indikator_id)
                ->where('tahap', 1)
                ->where('entity', $entity)
                ->exists();

            if (!$hasSoal) {
                continue;
            }

            $formattedIndikators[] = [
                'indikator_id' => $indikator->indikator_id,
                'title' => $indikator->indikator_name ?? "Indikator " . $indikator->no_urut,
                'no_urut' => $indikator->no_urut,
                'is_passed' => in_array($indikator->indikator_id, $passedIndikators),
                'is_current' => false, // Akan diupdate di quiz1 method
                'status_icon' => in_array($indikator->indikator_id, $passedIndikators)
                    ? 'ri-checkbox-circle-line text-success'
                    : 'ri-checkbox-blank-circle-line text-secondary'
            ];
        }

        return response()->json($formattedIndikators);
    }

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










    /**
     * Mendapatkan posisi lanjutan quiz untuk user
     */
    public function getContinuePosition($kegiatan_id, $ptk_id)
    {
        // Ambil jawaban terakhir dari ptk_jawaban_detail
        $lastAnswer = DB::table('ptk_jawaban_detail as pjd')
            ->join('soal as s', 's.soal_id', '=', 'pjd.soal_id')
            ->where('pjd.kegiatan_id', $kegiatan_id)
            ->where('pjd.ptk_id', $ptk_id)
            ->where('pjd.tahap', 2)
            ->orderBy('pjd.created_at', 'desc')
            ->first();

        if (!$lastAnswer) {
            return null;
        }

        // Cek apakah sudah ada level final untuk sub_indikator ini
        $finalLevel = DB::table('ptk_jawaban')
            ->where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk_id)
            ->where('tahap', 2)
            ->where('sub_indikator_id', $lastAnswer->sub_indikator_id)
            ->whereNotNull('level')
            ->first();

        $result = [
            'last_soal_id' => $lastAnswer->soal_id,
            'sub_indikator_id' => $lastAnswer->sub_indikator_id,
            'no_urut' => $lastAnswer->no_urut,
            'has_final_level' => $finalLevel ? true : false
        ];

        return $result;
    }
}
