<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Ptk;
use App\Models\Soal;
use App\Models\Kegiatan;
use App\Models\SoalCase;
use App\Models\Indikator;
use App\Models\PtkJawaban;
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

    public function quiz1($tahap, $encoded_kegiatan_id, $nip, $encoded_indikator_id, $encoded_no_urut)
    {
        // return Hashids::encode($encoded_no_urut);
        // return Hashids::decode($encoded_no_urut)[0];
        // Decode semua parameter yang di-encode
        $indikator_id = Hashids::decode($encoded_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;

        if (!$indikator_id || !$no_urut) {
            abort(404, 'Parameter tidak valid');
        }

        // Verifikasi PTK
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            abort(404, 'Data PTK tidak ditemukan');
        }

        $no_urut = request()->get('no_urut', $no_urut);

        // Ambil SOAL
        $soal = Soal::where('indikator_id', $indikator_id)
            ->where('no_urut', $no_urut)
            ->first(); 

        if (!$soal) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ]);
        }

        // Ambil STUDI KASUS
        //$case = SoalCase::where('soal_case_id', $soal->soal_case_id)->first();

        // Ambil pilihan jawaban
        $choices = SoalJawaban::where('soal_id', $soal->soal_id)
            //->inRandomOrder()
            ->get();
        return view('quiz.quiz1', [
            'soal' => $soal,
            'tahap' => $tahap,
            //'case' => $case,
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
        
        if (!session()->has('timestart')) {
            session(['timestart' => now()->format('H:i:s')]);
        }

        // Decode semua parameter yang di-encode
        $sub_indikator_id = Hashids::decode($encoded_sub_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        if (!$sub_indikator_id || !$no_urut) {
            abort(404, 'Parameter tidak valid');
        }

        // Verifikasi PTK
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            abort(404, 'Data PTK tidak ditemukan');
        }

        $no_urut = request()->get('no_urut', $no_urut);
        if($no_urut==1){
            session(['timestart' => now()->format('H:i:s')]);
        }

        // Ambil SOAL
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

        // Ambil STUDI KASUS
        $case = SoalCase::where('soal_case_id', $soal->soal_case_id)->first();

        // Ambil pilihan jawaban
        $choices = SoalJawaban::where('soal_id', $soal->soal_id)
            ->inRandomOrder()
            ->get();

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
        
        if (!session()->has('timestart')) {
            session(['timestart' => now()->format('H:i:s')]);
        }

        // Decode semua parameter yang di-encode
        $sub_indikator_id = Hashids::decode($encoded_sub_indikator_id)[0] ?? 0;
        $no_urut = Hashids::decode($encoded_no_urut)[0] ?? 0;
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        if (!$sub_indikator_id || !$no_urut) {
            abort(404, 'Parameter tidak valid');
        }

        // Verifikasi PTK
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            abort(404, 'Data PTK tidak ditemukan');
        }

        $no_urut = request()->get('no_urut', $no_urut);
        if($no_urut==1){
            session(['timestart' => now()->format('H:i:s')]);
        }

        // Ambil SOAL
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

        // Ambil STUDI KASUS
        $case = SoalCase::where('soal_case_id', $soal->soal_case_id)->first();

        // Ambil pilihan jawaban
        $choices = SoalJawaban::where('soal_id', $soal->soal_id)
            ->inRandomOrder()
            ->get();

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

    public function submitq1(Request $request)
    {
        //return 'submit 1';
        $soal_id = $request->soal_id;
        $tahap = $request->tahap;
        $sub_indikator_id = $request->sub_indikator_id;
        $jawaban_id = $request->pilihan_jawaban_id;

        // Ambil encoded values untuk redirect
        $encoded_kegiatan_id = $request->encoded_kegiatan_id;
        $encoded_indikator_id = $request->encoded_indikator_id;
        $encoded_no_urut = $request->encoded_no_urut;
        $nip = $request->nip;
        $bobot = $request->bobot;

        // Decode current no_urut
        $current_no_urut = Hashids::decode($encoded_no_urut)[0];

        // Decode kegiatan_id untuk penyimpanan
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0];

        $soal = Soal::where('soal_id', $soal_id)->first();
        if (!$soal) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan');
        }
        $indikator = Indikator::where('indikator_id', Hashids::decode($encoded_indikator_id)[0])->first();
        if (!$indikator) {
            return redirect()->back()->with('error', 'Sub Indikator Tidak ditemukan');
        }

        $ptk = Ptk::where('nip', $nip)->first();
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)->first();
        $nextSoal = Soal::where('indikator_id', Hashids::decode($encoded_indikator_id)[0])
                        ->where('no_urut', $current_no_urut + 1)
                        ->first();
        

        // start insert db
        //--------------------
        // Ambil session
        $answered = session("answered_{$encoded_indikator_id}", []);

        // 1. CEK APAKAH SOAL SUDAH DIJAWAB → USER KLIK BACK
        if (in_array($soal_id, $answered)) {
            //soal sudah dijawab
            $next_encoded_no_urut = Hashids::encode($current_no_urut + 1);
            return redirect()->route('quiz1.show', [
                'tahap' => $tahap, 
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip,
                'encoded_indikator_id' => $encoded_indikator_id,
                'encoded_no_urut' => $next_encoded_no_urut
            ]);
        }

        $answered[] = $soal_id;
        session(["answered_{$encoded_indikator_id}" => $answered]);

        $currentTotal = session("total_bobot_$encoded_indikator_id", 0);
        $newTotal     = $currentTotal + $bobot;
        session(["total_bobot_$encoded_indikator_id" => $newTotal]);

        if (count($answered) >= 5) {
            $total = session("total_bobot_{$encoded_indikator_id}");

            // Simpan ke database
            PtkJawaban::updateOrCreate([
                'kegiatan_id' => Hashids::decode($encoded_kegiatan_id)[0],
                'indikator_id' => Hashids::decode($encoded_indikator_id)[0],
                'indikator_code' => $indikator->indikator_code,
                'tahap' => $tahap,
                'ptk_id' => $ptk->ptk_id
            ], [
                'bobot' => $total
            ]);

            // RESET session ketika pindah indikator
            session()->forget("answered_{$encoded_indikator_id}");
            session()->forget("total_bobot_{$encoded_indikator_id}");

            // Redirect ke indikator berikutnya
            //return redirect()->route('indikator.next', $indikator_id);
        }
        //---------------------
        // end insert db
        $next_encoded_no_urut = Hashids::encode($current_no_urut + 1);
        return redirect()->route('quiz1.show', [
            'tahap' => $tahap, 
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip,
            'encoded_indikator_id' => $encoded_indikator_id,
            'encoded_no_urut' => $next_encoded_no_urut
        ]);        
    }

    public function submitq2(Request $request)
    {
        $soal_id = $request->soal_id;
        $tahap = $request->tahap;
        $sub_indikator_id = $request->sub_indikator_id;
        $jawaban_id = $request->pilihan_jawaban_id;

        // Ambil encoded values untuk redirect
        $encoded_kegiatan_id = $request->encoded_kegiatan_id;
        $encoded_sub_indikator_id = $request->encoded_sub_indikator_id;
        $encoded_no_urut = $request->encoded_no_urut;
        $nip = $request->nip;
        $bobot = $request->bobot;

        // Decode current no_urut
        $current_no_urut = Hashids::decode($encoded_no_urut)[0];

        // Decode kegiatan_id untuk penyimpanan
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        // Logika yang sama seperti sebelumnya...
        $soal = Soal::where('soal_id', $soal_id)->first();
        if (!$soal) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan');
        }

        $sub_indikator = SubIndikator::where('sub_indikator_id', Hashids::decode($encoded_sub_indikator_id)[0])->first();
        if (!$sub_indikator) {
            return redirect()->back()->with('error', 'Sub Indikator Tidak ditemukan');
        }

        // start logic algoritma
        $ptk = Ptk::where('nip', $nip)->first();
        switch ($soal->level) {
            case 2:
            case 3:
                if ($bobot >= 3) {
                    $nextSoal = Soal::where('sub_indikator_id', Hashids::decode($encoded_sub_indikator_id)[0])
                        ->where('no_urut', $current_no_urut + 1)
                        ->first();

                    if ($nextSoal) {
                        // Encode next no_urut
                        $next_encoded_no_urut = Hashids::encode($current_no_urut + 1);

                        return redirect()->route('quiz2.show', [
                            'tahap' => 2, //tes hardcode dulu
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => $next_encoded_no_urut
                        ]);
                    }
                }else{
                    //do simpan jawaban ptk
                    if($soal->level==2){
                        $level_kompetensi = 2;
                    }else{
                        $level_kompetensi = $soal->level - 1;
                    }
                    //return $level_kompetensi;
                    // session(['timeanswer' => now()->format('H:i:s')]);
                    PtkJawaban::updateOrCreate([
                        'kegiatan_id' => Hashids::decode($encoded_kegiatan_id)[0],
                        'sub_indikator_id' => Hashids::decode($encoded_sub_indikator_id)[0],
                        'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                        'tahap' => $tahap,
                        'time_start' => session('timestart'),
                        'time_end' => now()->format('H:i:s'),
                        'selisih' => now()->format('H:i:s'),
                        'ptk_id' => $ptk->ptk_id
                    ], [
                        'level' => $level_kompetensi
                    ]);
                }
                break;
            case 4:
            case 5 :
                if ($bobot == 4) {   
                    //do insert ke level 5
                    if($soal->level==5){
                        $level_kompetensi = 5;
                        //return $level_kompetensi;
                        PtkJawaban::updateOrCreate([
                            'kegiatan_id' => Hashids::decode($encoded_kegiatan_id)[0],
                            'sub_indikator_id' => Hashids::decode($encoded_sub_indikator_id)[0],
                            'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                            'tahap' => $tahap,
                            'time_start' => session('timestart'),
                            'time_end' => now()->format('H:i:s'),
                            'selisih' => now()->format('H:i:s'),
                            'ptk_id' => $ptk->ptk_id
                        ], [
                            'level' => $level_kompetensi
                        ]);
                    }              
                    $nextSoal = Soal::where('sub_indikator_id', Hashids::decode($encoded_sub_indikator_id)[0])
                        ->where('no_urut', $current_no_urut + 1)
                        ->first();
                    
                    if ($nextSoal) {
                        // Encode next no_urut
                        $next_encoded_no_urut = Hashids::encode($current_no_urut + 1);

                        return redirect()->route('quiz2.show', [
                            'tahap' => $tahap, //tes hardcode dulu
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => $next_encoded_no_urut
                        ]);
                    }
                }else{
                    //do simpan jawaban ptk
                    $level_kompetensi = $soal->level - 1;
                    
                    //return $level_kompetensi;
                    PtkJawaban::updateOrCreate([
                        'kegiatan_id' => Hashids::decode($encoded_kegiatan_id)[0],
                        'sub_indikator_id' => Hashids::decode($encoded_sub_indikator_id)[0],
                        'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                        'tahap' => $tahap,
                        'time_start' => session('timestart'),
                        'time_end' => now()->format('H:i:s'),
                        'ptk_id' => $ptk->ptk_id
                    ], [
                        'level' => $level_kompetensi
                    ]);
                }
                break;
            default:
                return "Invalid Algoritma";
        }
        // end logic algoritma      

        // Jika bobot != 4 → pindah ke sub_indikator berikutnya
        $nextSubIndikator = Hashids::decode($encoded_sub_indikator_id)[0] + 1;

        $cekSoal = Soal::where('sub_indikator_id', $nextSubIndikator)->first();
        if (!$cekSoal) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ]);
        }

        // Encode next sub_indikator_id dan mulai dari no_urut = 1
        $next_encoded_sub_indikator_id = Hashids::encode($nextSubIndikator);
        $next_encoded_no_urut = Hashids::encode(1);

        return redirect()->route('quiz2.show', [
            'tahap' => $tahap,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip,
            'encoded_sub_indikator_id' => $next_encoded_sub_indikator_id,
            'encoded_no_urut' => $next_encoded_no_urut
        ]);
    }

    public function submitq2_review(Request $request)
    {
        $soal_id = $request->soal_id;
        $tahap = $request->tahap;
        $sub_indikator_id = $request->sub_indikator_id;
        $jawaban_id = $request->pilihan_jawaban_id;

        // Ambil encoded values untuk redirect
        $encoded_kegiatan_id = $request->encoded_kegiatan_id;
        $encoded_sub_indikator_id = $request->encoded_sub_indikator_id;
        $encoded_no_urut = $request->encoded_no_urut;
        $nip = $request->nip;
        $bobot = $request->bobot;

        // Decode current no_urut
        $current_no_urut = Hashids::decode($encoded_no_urut)[0];

        // Decode kegiatan_id untuk penyimpanan
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;

        // Logika yang sama seperti sebelumnya...
        $soal = Soal::where('soal_id', $soal_id)->first();
        if (!$soal) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan');
        }

        $sub_indikator = SubIndikator::where('sub_indikator_id', Hashids::decode($encoded_sub_indikator_id)[0])->first();
        if (!$sub_indikator) {
            return redirect()->back()->with('error', 'Sub Indikator Tidak ditemukan');
        }

        // start logic algoritma
        $ptk = Ptk::where('nip', $nip)->first();
        switch ($soal->level) {
            case 2:
            case 3:
                if ($bobot >= 3) {
                    $nextSoal = Soal::where('sub_indikator_id', Hashids::decode($encoded_sub_indikator_id)[0])
                        ->where('no_urut', $current_no_urut + 1)
                        ->first();

                    if ($nextSoal) {
                        // Encode next no_urut
                        $next_encoded_no_urut = Hashids::encode($current_no_urut + 1);

                        return redirect()->route('quiz2review.show', [
                            'tahap' => 2, //tes hardcode dulu
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => $next_encoded_no_urut,
                            'token' => 123,
                        ]);
                    }
                }else{
                    //do simpan jawaban ptk
                    if($soal->level==2){
                        $level_kompetensi = 2;
                    }else{
                        $level_kompetensi = $soal->level - 1;
                    }
                    //return $level_kompetensi;
                    // session(['timeanswer' => now()->format('H:i:s')]);
                    PtkJawaban::updateOrCreate([
                        'kegiatan_id' => Hashids::decode($encoded_kegiatan_id)[0],
                        'sub_indikator_id' => Hashids::decode($encoded_sub_indikator_id)[0],
                        'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                        'tahap' => $tahap,
                        'time_start' => session('timestart'),
                        'time_end' => now()->format('H:i:s'),
                        'ptk_id' => $ptk->ptk_id
                    ], [
                        'level' => $level_kompetensi
                    ]);
                }
                break;
            case 4:
            case 5 :
                if ($bobot == 4) {   
                    //do insert ke level 5
                    if($soal->level==5){
                        $level_kompetensi = 5;
                        //return $level_kompetensi;
                        PtkJawaban::updateOrCreate([
                            'kegiatan_id' => Hashids::decode($encoded_kegiatan_id)[0],
                            'sub_indikator_id' => Hashids::decode($encoded_sub_indikator_id)[0],
                            'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                            'tahap' => $tahap,
                            'time_start' => session('timestart'),
                            'time_end' => now()->format('H:i:s'),
                            'ptk_id' => $ptk->ptk_id
                        ], [
                            'level' => $level_kompetensi
                        ]);
                    }              
                    $nextSoal = Soal::where('sub_indikator_id', Hashids::decode($encoded_sub_indikator_id)[0])
                        ->where('no_urut', $current_no_urut + 1)
                        ->first();
                    
                    if ($nextSoal) {
                        // Encode next no_urut
                        $next_encoded_no_urut = Hashids::encode($current_no_urut + 1);

                        return redirect()->route('quiz2review.show', [
                            'tahap' => $tahap, //tes hardcode dulu
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => $next_encoded_no_urut,
                            'token' => 123
                        ]);
                    }
                }else{
                    //do simpan jawaban ptk
                    $level_kompetensi = $soal->level - 1;
                    
                    //return $level_kompetensi;
                    PtkJawaban::updateOrCreate([
                        'kegiatan_id' => Hashids::decode($encoded_kegiatan_id)[0],
                        'sub_indikator_id' => Hashids::decode($encoded_sub_indikator_id)[0],
                        'sub_indikator_code' => $sub_indikator->sub_indikator_code,
                        'tahap' => $tahap,
                        'time_start' => session('timestart'),
                        'time_end' => now()->format('H:i:s'),
                        'ptk_id' => $ptk->ptk_id
                    ], [
                        'level' => $level_kompetensi
                    ]);
                }
                break;
            default:
                return "Invalid Algoritma";
        }
        // end logic algoritma      

        // Jika bobot != 4 → pindah ke sub_indikator berikutnya
        $nextSubIndikator = Hashids::decode($encoded_sub_indikator_id)[0] + 1;

        $cekSoal = Soal::where('sub_indikator_id', $nextSubIndikator)->first();
        if (!$cekSoal) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ]);
        }

        // Encode next sub_indikator_id dan mulai dari no_urut = 1
        $next_encoded_sub_indikator_id = Hashids::encode($nextSubIndikator);
        $next_encoded_no_urut = Hashids::encode(1);

        return redirect()->route('quiz2review.show', [
            'tahap' => $tahap,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip,
            'encoded_sub_indikator_id' => $next_encoded_sub_indikator_id,
            'encoded_no_urut' => $next_encoded_no_urut,
            'token' => 123
        ]);
    }

    /**
     * Tampilan finish/selesai quiz
     */
    public function finish($encoded_kegiatan_id, $nip)
    {
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;
        $ptk = Ptk::where('nip', $nip)->first();

        if (!$ptk) {
            abort(404, 'Data PTK tidak ditemukan');
        }

        $kegiatan = Kegiatan::find($kegiatan_id);
        if (!$kegiatan) {
            abort(404, 'Kegiatan tidak ditemukan');
        }

        // Hitung jumlah jawaban yang sudah disimpan
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
                    })->values()
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
