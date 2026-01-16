<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkJawabanDetail extends Model
{
    use HasFactory;

    protected $table = 'ptk_jawaban_detail';
    protected $primaryKey = 'ptk_jawaban_detail_id';
    public $timestamps = true;

    protected $fillable = [
        'kegiatan_id',
        'tahap',
        'ptk_id',
        'soal_id',
        'instrumen_id',
        'indikator_id',
        'indikator_code',
        'sub_indikator_id',
        'sub_indikator_code',
        'level',
        'bobot',
        'time_start',
        'time_end',
        'selisih',
        'sisa_duration_time',
        'created_at',
        'updated_at'
    ];







    /**
     * Ambil sisa waktu TERAKHIR dari field sisa_duration_time
     * Tapi jangan reset waktu, hanya ambil data terbaru
     */
    public static function getLatestRemainingTimeFromDatabase($kegiatan_id, $ptk_id, $tahap = 2)
    {
        // Debug log
        \Log::info("getLatestRemainingTimeFromDatabase called", [
            'kegiatan_id' => $kegiatan_id,
            'ptk_id' => $ptk_id,
            'tahap' => $tahap
        ]);

        try {
            // Cari record terakhir dengan sisa_duration_time
            $latestRecord = self::where('kegiatan_id', $kegiatan_id)
                ->where('ptk_id', $ptk_id)
                ->where('tahap', $tahap)
                ->whereNotNull('sisa_duration_time')
                ->where('sisa_duration_time', '!=', '')
                ->orderBy('created_at', 'desc')
                ->orderBy('ptk_jawaban_detail_id', 'desc')
                ->first();

            // Jika ada record dengan sisa_duration_time, gunakan itu
            if ($latestRecord && !empty($latestRecord->sisa_duration_time)) {
                $timeString = trim($latestRecord->sisa_duration_time);
                $timeParts = explode(':', $timeString);

                if (count($timeParts) === 3) {
                    $hours = (int)$timeParts[0];
                    $minutes = (int)$timeParts[1];
                    $seconds = (int)$timeParts[2];
                    $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                    \Log::info("Menggunakan sisa_duration_time terakhir dari database", [
                        'record_id' => $latestRecord->ptk_jawaban_detail_id,
                        'sisa_duration_time' => $timeString,
                        'total_seconds' => $totalSeconds,
                        'created_at' => $latestRecord->created_at
                    ]);

                    // JANGAN reset ke 7200 jika < 7200, biarkan sesuai waktu yang tersisa
                    // Ini agar waktu bisa dilanjutkan, tidak diulang dari awal
                    return max(0, $totalSeconds);
                }
            }

            // Jika tidak ada data sama sekali, baru berikan waktu penuh 2 jam
            $hasAnyRecord = self::where('kegiatan_id', $kegiatan_id)
                ->where('ptk_id', $ptk_id)
                ->where('tahap', $tahap)
                ->exists();

            if (!$hasAnyRecord) {
                \Log::info("Tidak ada record sebelumnya, memberikan waktu penuh 2 jam");
                return 7200; // 2 jam penuh
            }

            // Jika ada record tapi tidak ada sisa_duration_time, hitung dari time_start/time_end
            \Log::info("Tidak ada sisa_duration_time, menghitung dari time_start/time_end");
            $calculated = self::calculateRemainingTime($kegiatan_id, $ptk_id, $tahap);
            \Log::info("Hasil perhitungan", ['seconds' => $calculated]);

            return max(0, $calculated);
        } catch (\Exception $e) {
            \Log::error("Error in getLatestRemainingTimeFromDatabase", [
                'error' => $e->getMessage()
            ]);

            // Fallback: 120 menit (7200 detik)
            return 7200;
        }
    }


    /**
     * Hitung sisa waktu berdasarkan kegiatan (REVISED)
     */
    public static function calculateRemainingTime($kegiatan_id, $ptk_id, $tahap = 2)
    {
        // Durasi total 120 menit dalam detik
        $total_duration = 120 * 60;

        // Cek jika ada sisa_duration_time terakhir
        $latestWithRemaining = self::where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk_id)
            ->where('tahap', $tahap)
            ->whereNotNull('sisa_duration_time')
            ->where('sisa_duration_time', '!=', '00:00:00')
            ->orderBy('created_at', 'desc')
            ->first();

        // Jika ada sisa_duration_time, gunakan itu
        if ($latestWithRemaining && !empty($latestWithRemaining->sisa_duration_time)) {
            $timeString = trim($latestWithRemaining->sisa_duration_time);
            \Log::info("Menggunakan sisa_duration_time terakhir", [
                'time_string' => $timeString,
                'record_id' => $latestWithRemaining->ptk_jawaban_detail_id
            ]);

            $timeParts = explode(':', $timeString);
            if (count($timeParts) === 3) {
                $hours = (int)$timeParts[0];
                $minutes = (int)$timeParts[1];
                $seconds = (int)$timeParts[2];

                $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
                return max(0, $totalSeconds);
            }
        }

        // Jika tidak, hitung dari total waktu yang digunakan
        $records = self::where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk_id)
            ->where('tahap', $tahap)
            ->whereNotNull('time_start')
            ->whereNotNull('time_end')
            ->orderBy('created_at')
            ->get();

        if ($records->isEmpty()) {
            return $total_duration; // Masih 120 menit penuh
        }

        $total_used = 0;

        foreach ($records as $record) {
            try {
                $start = \Carbon\Carbon::parse($record->time_start);
                $end = \Carbon\Carbon::parse($record->time_end);
                $total_used += $start->diffInSeconds($end);
            } catch (\Exception $e) {
                \Log::error("Error parsing time", [
                    'record_id' => $record->ptk_jawaban_detail_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $remaining = $total_duration - $total_used;

        \Log::info("calculateRemainingTime hasil", [
            'total_duration' => $total_duration,
            'total_used' => $total_used,
            'remaining' => $remaining,
            'has_latest_remaining' => $latestWithRemaining ? 'YES' : 'NO'
        ]);

        return max(0, $remaining);
    }

    /**
     * Format sisa waktu ke format time (HH:MM:SS)
     */
    public static function formatRemainingTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }



    /**
     * Reset timer untuk user tertentu (untuk lanjutkan quiz)
     */
    public static function resetUserTimer($kegiatan_id, $ptk_id, $tahap = 2)
    {
        // Hapus session timer jika ada
        if (session()->has('quiz2_start_time')) {
            session()->forget('quiz2_start_time');
        }
        if (session()->has('quiz2_remaining')) {
            session()->forget('quiz2_remaining');
        }

        // Ambil waktu sisa terbaru dari database
        $remainingSeconds = self::getLatestRemainingTimeFromDatabase($kegiatan_id, $ptk_id, $tahap);

        return $remainingSeconds;
    }








    // Tambahkan di PtkJawabanDetail.php atau helper
    public static function calculateTimeElapsedSince($timeString)
    {
        try {
            if (empty($timeString)) return 0;

            // Parse waktu dari string HH:MM:SS
            $timeParts = explode(':', $timeString);
            if (count($timeParts) !== 3) return 0;

            $hours = (int)$timeParts[0];
            $minutes = (int)$timeParts[1];
            $seconds = (int)$timeParts[2];

            // Hitung total detik dari waktu tersebut
            $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            // Waktu total yang diizinkan (2 jam = 7200 detik)
            $totalAllowed = 7200;

            // Hitung waktu yang sudah digunakan
            $elapsed = $totalAllowed - $totalSeconds;

            return max(0, $elapsed);
        } catch (\Exception $e) {
            \Log::error("Error calculating time elapsed", ['error' => $e->getMessage()]);
            return 0;
        }
    }
}
