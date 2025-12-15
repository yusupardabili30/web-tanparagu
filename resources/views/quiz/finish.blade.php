@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">
    <!-- Reset timer JavaScript -->
    <script>
        // Reset timer saat halaman finish diakses
        localStorage.removeItem("quiz_start_time");
        localStorage.removeItem("quiz2_start_time"); // tambahan untuk quiz2 jika ada
    </script>
    <br>
    <br>
    <br>
    <br>


    <!-- FINISH CARD -->
    <div class="row justify-content-center">
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <!-- HEADER -->
                <div class="card-header baduy-bg text-center" style="border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        <i class="ri-checkbox-circle-line me-2"></i> Instrumen Telah Selesai
                    </h5>
                </div>

                <div class="card-body p-5 text-center">

                    <!-- ICON SUCCESS -->
                    <div class="mb-4">
                        <div class="icon-success mx-auto">
                            <i class="ri-checkbox-circle-fill" style="font-size: 80px; color: #28a745;"></i>
                        </div>
                    </div>

                    <!-- TITLE -->
                    <h3 class="mb-3" style="color: #1a4d8e; font-weight: 700;">
                        Terima Kasih! Anda Telah Menyelesaikan @php
                        $kegiatan = \App\Models\Kegiatan::find(Hashids::decode($encoded_kegiatan_id)[0] ?? 0);
                        @endphp
                        {{ $kegiatan->kegiatan_name ?? 'Instrumen' }}
                    </h3>

                    <!-- DETAILS -->
                    <div class="mb-4">
                        <p class="mb-2" style="font-size: 18px;">
                            <strong>Nama:</strong> {{ $ptk->nama ?? 'Tidak ditemukan' }}
                        </p>
                        <p class="mb-2" style="font-size: 18px;">
                            <strong>NIP:</strong> {{ $ptk->nip ?? 'Tidak ditemukan' }}
                        </p>
                    </div>

                    <!-- MESSAGE -->
                    <div class="alert alert-success" role="alert" style="font-size: 16px; border-radius: 10px;">
                        <i class="ri-information-line me-2"></i>
                        Terima kasih telah mengikuti instrumen. Jawaban Anda telah tersimpan dengan baik.
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="card-footer text-center" style="background: #f8f9fa; border-top: 1px solid #dee2e6;">
                    <p class="mb-0 text-muted" style="font-size: 14px;">
                        <i class="ri-time-line me-1"></i>
                        Selesai pada: {{ date('d F Y H:i:s') }}
                    </p>
                </div>

            </div>

        </div>
    </div>

</div>

<style>
    .icon-success {
        width: 120px;
        height: 120px;
        background: #f8fff8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #d4edda;
    }

    .card {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
</style>

@section('sipproja-js')
<!-- <script>
    // Clear semua data timer dari localStorage
    localStorage.removeItem("quiz_start_time");
    localStorage.removeItem("quiz2_start_time");
    localStorage.removeItem("timestart_quiz2");
    localStorage.removeItem("timesoal_quiz2");

    // Clear session timer jika ada
    if (typeof sessionStorage !== 'undefined') {
        sessionStorage.removeItem("quiz_start_time");
        sessionStorage.removeItem("quiz2_start_time");
    }

    // Juga clear cookie timer jika menggunakan cookie
    document.cookie = "quiz_start_time=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "quiz2_start_time=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    console.log("Timer telah direset untuk sesi berikutnya.");
</script> -->

<script>
    // Reset semua timer saat halaman finish diakses
    document.addEventListener('DOMContentLoaded', function() {
        // Clear semua data timer dari localStorage
        const timerKeys = [
            "quiz_start_time",
            "quiz1_start_time", // Timer untuk quiz1
            "quiz2_start_time", // Timer untuk quiz2
            "timestart_quiz2",
            "timesoal_quiz2",
            "quiz_timer_start", // Backup key jika ada
            "quiz1_timer_start", // Backup untuk quiz1
            "quiz2_timer_start" // Backup untuk quiz2
        ];

        timerKeys.forEach(key => {
            localStorage.removeItem(key);
        });

        // Clear session timer jika ada
        if (typeof sessionStorage !== 'undefined') {
            timerKeys.forEach(key => {
                sessionStorage.removeItem(key);
            });
        }

        // Clear cookies timer jika ada
        timerKeys.forEach(key => {
            document.cookie = key + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        });

        console.log("✅ Semua timer telah direset:");
        console.log("- Timer Quiz 1 (Indikator) dihapus");
        console.log("- Timer Quiz 2 (Studi Kasus) dihapus");
        console.log("- Backup timer juga dihapus");

        // Tampilkan pesan konfirmasi di console
        console.log("Timer siap untuk sesi berikutnya. User dapat memulai quiz dari awal dengan timer 2 jam lagi.");
    });

    // Juga panggil saat halaman dimuat (backup)
    window.onload = function() {
        // Hapus kembali untuk memastikan
        localStorage.removeItem("quiz1_start_time");
        localStorage.removeItem("quiz2_start_time");

        console.log("🔄 Timer di-reset ulang saat onload");
    };
</script>
@endsection