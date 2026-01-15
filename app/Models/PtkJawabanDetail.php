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
     * Ini untuk lanjutkan quiz - ambil data terbaru dari database
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
            // Cari record terakhir dengan sisa_duration_time TIDAK NULL
            $latestRecord = self::where('kegiatan_id', $kegiatan_id)
                ->where('ptk_id', $ptk_id)
                ->where('tahap', $tahap)
                ->whereNotNull('sisa_duration_time')
                ->where('sisa_duration_time', '!=', '')
                ->orderBy('created_at', 'desc')
                ->orderBy('ptk_jawaban_detail_id', 'desc')
                ->first();

            \Log::info("Latest record found", [
                'record_exists' => $latestRecord ? 'YES' : 'NO',
                'sisa_duration_time' => $latestRecord ? $latestRecord->sisa_duration_time : 'NULL',
                'record_id' => $latestRecord ? $latestRecord->ptk_jawaban_detail_id : 'NULL'
            ]);

            // Jika ada record dengan sisa_duration_time, gunakan itu
            if ($latestRecord && !empty($latestRecord->sisa_duration_time)) {
                $timeString = trim($latestRecord->sisa_duration_time);
                \Log::info("Parsing time string", ['time_string' => $timeString]);

                $timeParts = explode(':', $timeString);
                if (count($timeParts) === 3) {
                    $hours = (int)$timeParts[0];
                    $minutes = (int)$timeParts[1];
                    $seconds = (int)$timeParts[2];

                    $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
                    \Log::info("Parsed time", [
                        'hours' => $hours,
                        'minutes' => $minutes,
                        'seconds' => $seconds,
                        'total_seconds' => $totalSeconds
                    ]);

                    // Pastikan tidak negatif
                    return max(0, $totalSeconds);
                }
            }

            // Jika tidak ada, hitung dari time_start dan time_end
            \Log::info("No sisa_duration_time found, calculating from time_start/time_end");
            $calculated = self::calculateRemainingTime($kegiatan_id, $ptk_id, $tahap);
            \Log::info("Calculated remaining time", ['seconds' => $calculated]);

            return max(0, $calculated);
        } catch (\Exception $e) {
            \Log::error("Error in getLatestRemainingTimeFromDatabase", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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

        // Ambil semua record untuk kegiatan ini
        $records = self::where('kegiatan_id', $kegiatan_id)
            ->where('ptk_id', $ptk_id)
            ->where('tahap', $tahap)
            ->whereNotNull('time_start')
            ->whereNotNull('time_end')
            ->orderBy('created_at')
            ->get();

        \Log::info("calculateRemainingTime - records count", ['count' => $records->count()]);

        if ($records->isEmpty()) {
            \Log::info("No records found, returning full duration", ['duration' => $total_duration]);
            return $total_duration; // Masih 120 menit penuh
        }

        // Hitung total waktu yang sudah digunakan
        $total_used = 0;

        foreach ($records as $record) {
            try {
                $start = \Carbon\Carbon::parse($record->time_start);
                $end = \Carbon\Carbon::parse($record->time_end);
                $diff = $start->diffInSeconds($end);
                $total_used += $diff;

                \Log::debug("Record time used", [
                    'record_id' => $record->ptk_jawaban_detail_id,
                    'time_start' => $record->time_start,
                    'time_end' => $record->time_end,
                    'diff_seconds' => $diff
                ]);
            } catch (\Exception $e) {
                \Log::error("Error parsing time in record", [
                    'record_id' => $record->ptk_jawaban_detail_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Hitung sisa waktu
        $remaining = $total_duration - $total_used;

        \Log::info("Time calculation result", [
            'total_duration' => $total_duration,
            'total_used' => $total_used,
            'remaining' => $remaining,
            'max_remaining' => max(0, $remaining)
        ]);

        return max(0, $remaining); // Minimal 0
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
}
