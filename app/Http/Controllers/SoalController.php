<?php

namespace App\Http\Controllers;

use App\Models\Ptk; // TAMBAHKAN INI
use App\Models\Kegiatan; // TAMBAHKAN INI
use App\Models\Soal;
use App\Models\SoalCase;
use App\Models\SoalJawaban;
use App\Models\SubIndikator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class SoalController extends Controller
{
    public function index()
    {
        $soal = SoalCase::with('soal')->paginate(10);
        $soal = SoalCase::with(['soal.soal_jawaban', 'sub_indikator'])->paginate(1);
        return $soal;
        $soal = DB::table('users')
                ->join('contacts', 'users.id', '=', 'contacts.user_id')
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->select('users.*', 'contacts.phone', 'orders.price')
                ->get();
        return view('soal.index', [
            'tittle' => 'Soal',
            'data' => $soal
        ]);
    }

    public function quiz($encoded_kegiatan_id, $nip, $encoded_sub_indikator_id, $encoded_no_urut)
    {
        // Decode semua parameter yang di-encode
        $sub_indikator_id = Hashids::decode($encoded_sub_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;

        if (!$sub_indikator_id || !$no_urut) {
            abort(404, 'Parameter tidak valid');
        }

        // Verifikasi PTK dan Kegiatan (jika diperlukan)
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            abort(404, 'Data PTK tidak ditemukan');
        }

        // Lanjutkan dengan logika yang sudah ada, hanya ubah parameter input
        $soal = Soal::find(1);
        
        $case = $soal->soal_case;


        $no_urut = request()->get('no_urut', $no_urut);

        //ambil SOAL (bukan soal_case)
        $soal = Soal::where('sub_indikator_id', $sub_indikator_id)
            ->where('no_urut', $no_urut)
            ->first();

        if (!$soal) {
            return redirect()->route('quiz.finish');
        }

        //ambil STUDI KASUS
        $case = SoalCase::where('soal_case_id', $soal->soal_case_id)->first();

        //ambil pilihan jawaban berdasarkan soal_id (inRandomOrder)
        $choices = SoalJawaban::where('soal_id', $soal->soal_id)
            ->inRandomOrder()
            ->get();

        return view('quiz.index', [
            'soal' => $soal,
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

    public function submit(Request $request)
    {

        try {
        //     $request->validate([
        //     'soal_id' => 'required',
        //     'sub_indikator_id' => 'required',
        //     'pilihan_jawaban_id' => 'required',
        //     'encoded_kegiatan_id' => 'required', // Tambahkan
        //     'encoded_sub_indikator_id' => 'required', // Tambahkan
        //     'encoded_no_urut' => 'required', // Tambahkan
        //     'nip' => 'required' // Tambahkan
        // ]);
        } catch (\Throwable $th) {
            //throw $th;
        }


        $soal_id = $request->soal_id;
        $sub_indikator_id = $request->sub_indikator_id;
        $jawaban_id = $request->pilihan_jawaban_id;

        // Ambil encoded values untuk redirect
        $encoded_kegiatan_id = $request->encoded_kegiatan_id;
        $encoded_sub_indikator_id = $request->encoded_sub_indikator_id;
        $encoded_no_urut = $request->encoded_no_urut;
        $nip = $request->nip;

        // Decode current no_urut
        $current_no_urut = Hashids::decode($encoded_no_urut)[0];

        // Logika yang sama seperti sebelumnya...
        $soal = Soal::where('soal_id', $soal_id)->first();
        if (!$soal) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan');
        }

        $jawaban = SoalJawaban::where('soal_jawaban_id', $jawaban_id)->first();
        

        if (!$jawaban) {
            return redirect()->back()->with('error', 'Jawaban tidak ditemukan');
        }

        $bobot = $jawaban->bobot;

        // Jika bobot = 4 → lanjut ke soal berikutnya
        if ($bobot == 4) {
            $nextSoal = Soal::where('sub_indikator_id', $sub_indikator_id)
                ->where('no_urut', $current_no_urut + 1)
                ->first();

            if ($nextSoal) {
                // Encode next no_urut
                $next_encoded_no_urut = Hashids::encode($current_no_urut + 1);

                return redirect()->route('quiz.show', [
                    'encoded_kegiatan_id' => $encoded_kegiatan_id,
                    'nip' => $nip,
                    'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                    'encoded_no_urut' => $next_encoded_no_urut
                ]);
            }
        }

        // Jika bobot != 4 → pindah ke sub_indikator berikutnya
        $nextSubIndikator = $sub_indikator_id + 1;

        $cekSoal = Soal::where('sub_indikator_id', $nextSubIndikator)->first();
        if (!$cekSoal) {
            return redirect()->route('quiz.finish');
        }

        // Encode next sub_indikator_id dan mulai dari no_urut = 1
        $next_encoded_sub_indikator_id = Hashids::encode($nextSubIndikator);
        $next_encoded_no_urut = Hashids::encode(1);

        return redirect()->route('quiz.show', [
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip,
            'encoded_sub_indikator_id' => $next_encoded_sub_indikator_id,
            'encoded_no_urut' => $next_encoded_no_urut
        ]);
    }
    /**
     * Ambil soal berdasarkan sub_indikator_id
     */
    public function getSoalBySubIndikator($sub_indikator_id)
    {
        // Ambil case berdasarkan sub_indikator
        $case = SoalCase::where('sub_indikator_id', $sub_indikator_id)->first();

        if (!$case) {
            return response()->json([
                "status" => false,
                "message" => "Case tidak ditemukan"
            ]);
        }

        // Ambil soal beserta pilihan jawabannya
        $questions = Soal::with("soal_jawaban")
            ->where("sub_indikator_id", $sub_indikator_id)
            ->orderBy("no_urut", "asc")
            ->get();

        // Bentuk JSON sesuai format
        $response = [
            "status" => true,
            "case" => [
                "id" => $case->soal_case_id,
                "title" => $case->tittle,
                "content" => $case->case
            ],
            "questions" => $questions->map(function ($q) {
                return [
                    "id" => $q->soal_id,
                    "question_text" => $q->soal,
                    "level" => $q->level,
                    "choices" => $q->soal_jawaban->shuffle()->map(function ($c) {
                        return [
                            "id" => $c->soal_jawaban_id,
                            "pilihan_jawaban" => $c->pilihan_jawaban,
                            "bobot" => $c->bobot
                        ];
                    })->values() // supaya index 0,1,2,3 rapi
                ];
            })
        ];
        return response()->json($response);
    }

    public function getSingleSoal($soal_id)
    {
        $soal = Soal::with(['soal_jawaban' => function ($q) {
            $q->inRandomOrder();
        }])->find($soal_id);

        if (!$soal) {
            return response()->json([
                "status" => false,
                "message" => "Soal tidak ditemukan"
            ]);
        }

        return response()->json([
            "status" => true,
            "question" => [
                "id" => $soal->soal_id,
                "question_text" => $soal->soal,
                "level" => $soal->level,
                "choices" => $soal->soal_jawaban->map(function ($c) {
                    return [
                        "id" => $c->soal_jawaban_id,
                        "text" => $c->pilihan_jawaban,
                        "bobot" => $c->bobot
                    ];
                })
            ]
        ]);
    }

    public function submitJawaban(Request $request)
    {
        $request->validate([
            'soal_id' => 'required|integer',
            'jawaban_id' => 'required|integer'
        ]);

        // Ambil soal yang dijawab
        $soal = Soal::find($request->soal_id);

        if (!$soal) {
            return response()->json([
                "status" => false,
                "message" => "Soal tidak ditemukan"
            ]);
        }

        // Ambil pilihan jawaban yang dipilih user
        $jawaban = SoalJawaban::find($request->jawaban_id);

        if (!$jawaban) {
            return response()->json([
                "status" => false,
                "message" => "Jawaban tidak ditemukan"
            ]);
        }

        $bobot = $jawaban->bobot;

        // Jika bobot != 4 → pindah ke subindikator berikutnya
        if ($bobot != 4) {

            $nextSubIndikator = Soal::where('sub_indikator_id', '>', $soal->sub_indikator_id)
                ->orderBy('sub_indikator_id', 'asc')
                ->value('sub_indikator_id');

            return response()->json([
                "status" => true,
                "next" => "sub_indikator",
                "next_sub_indikator_id" => $nextSubIndikator
            ]);
        }

        // Jika bobot == 4 → lanjut soal berikutnya dalam subindikator yang sama
        $nextSoal = Soal::where('sub_indikator_id', $soal->sub_indikator_id)
            ->where('no_urut', '>', $soal->no_urut)
            ->orderBy('no_urut', 'asc')
            ->first();

        if ($nextSoal) {
            return response()->json([
                "status" => true,
                "next" => "soal",
                "next_soal_id" => $nextSoal->soal_id
            ]);
        }

        // Jika soal terakhir → tetap pindah ke subindikator berikutnya
        $nextSubIndikator = Soal::where('sub_indikator_id', '>', $soal->sub_indikator_id)
            ->orderBy('sub_indikator_id', 'asc')
            ->value('sub_indikator_id');

        return response()->json([
            "status" => true,
            "next" => "sub_indikator",
            "next_sub_indikator_id" => $nextSubIndikator
        ]);
    }
}
