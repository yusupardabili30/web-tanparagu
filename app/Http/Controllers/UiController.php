<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UiController extends Controller
{
    public function index(Request $request)
    {
        // ====== HALAMAN MANA YANG MAU DITAMPILKAN ======
        // Tinggal ubah "ui.grafik" → "ui.quiz" → "ui.profil" → dll.
        $page = 'ui.grafik';   // <<< GANTI INI 

        // ====== DATA DUMMY GRAFIK (TETAP ADA, TAPI HANYA DIKIRIM KE VIEW GRAFIK) ======
        $daerahList = ['Bandung', 'Bogor', 'Cimahi', 'Bekasi', 'Serang'];

        $baseInstrumen = [
            'Instrumen 1'  => [70, 55, 60, 58, 62],
            'Instrumen 2'  => [65, 52, 55, 50, 58],
            'Instrumen 3'  => [60, 48, 50, 46, 52],
            'Instrumen 4'  => [72, 60, 65, 62, 70],
            'Instrumen 5'  => [68, 54, 58, 55, 60],
            'Instrumen 6'  => [75, 62, 66, 63, 68],
            'Instrumen 7'  => [80, 70, 72, 69, 74],
            'Instrumen 8'  => [67, 55, 60, 58, 62],
            'Instrumen 9'  => [72, 62, 65, 60, 70],
            'Instrumen 10' => [65, 55, 58, 54, 60],
            'Instrumen 11' => [70, 60, 62, 58, 65],
            'Instrumen 12' => [75, 65, 68, 63, 70],
            'Instrumen 13' => [68, 58, 60, 57, 63],
        ];

        // Jika halaman yang dibuka BUKAN ui.grafik → langsung load view lain tanpa data
        if ($page !== 'ui.grafik') {
            return view($page);
        }

        // ====== KALO PAGE = ui.grafik, BARU PAKAI SEMUA DATA DUMMY DI BAWAH ======

        $guruData     = $baseInstrumen;
        $kepsekData   = $baseInstrumen;
        $pengawasData = $baseInstrumen;

        $selectedDaerah = $request->query('daerah');

        if ($selectedDaerah && in_array($selectedDaerah, $daerahList)) {

            $i = array_search($selectedDaerah, $daerahList);

            foreach ($guruData as $key => $values) {
                $guruData[$key] = [$values[$i]];
            }
            foreach ($kepsekData as $key => $values) {
                $kepsekData[$key] = [$values[$i]];
            }
            foreach ($pengawasData as $key => $values) {
                $pengawasData[$key] = [$values[$i]];
            }

            $labels = [$selectedDaerah];

        } else {
            $labels = $daerahList;
        }

        function getTopInstrumen($data)
        {
            $maxVal = -1;
            $maxKey = null;

            foreach ($data as $instrumen => $values) {
                if (max($values) > $maxVal) {
                    $maxVal = max($values);
                    $maxKey = $instrumen;
                }
            }
            return $maxKey;
        }

        // RETURN VIEW GRAFIK + DATA DUMMY
        return view('ui.grafik', [
            'labels'        => $labels,
            'guruData'      => $guruData,
            'kepsekData'    => $kepsekData,
            'pengawasData'  => $pengawasData,
            'daerahList'    => $daerahList,
            'selectedDaerah'=> $selectedDaerah,
            'maxGuru'       => getTopInstrumen($guruData),
            'maxKepsek'     => getTopInstrumen($kepsekData),
            'maxPengawas'   => getTopInstrumen($pengawasData),
        ]);
    }
}
