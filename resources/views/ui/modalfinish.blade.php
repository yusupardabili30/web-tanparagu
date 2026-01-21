@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <!-- Reset timer JavaScript -->
    <script>
        localStorage.removeItem("quiz_start_time");
        localStorage.removeItem("quiz2_start_time");
    </script>

    <br><br><br><br>

    <!-- FINISH CARD -->
    <div class="row justify-content-center">
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm finish-card">

                <!-- HEADER -->
                <div class="card-header baduy-bg text-center finish-header">
                    <h5 class="mb-0 text-white finish-title">
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
                    <h3 class="mb-3 finish-h3">
                        Terima Kasih! Anda Telah Menyelesaikan Instrumen
                    </h3>

                    <!-- DETAILS (dummy / placeholder) -->
                    <div class="mb-4">
                        <p class="mb-2 finish-p">
                            <strong>Nama:</strong> -
                        </p>
                        <p class="mb-2 finish-p">
                            <strong>NIP:</strong> -
                        </p>
                    </div>

                    <!-- MESSAGE -->
                    <div class="alert alert-success finish-alert" role="alert">
                        <i class="ri-information-line me-2"></i>
                        Terima kasih telah mengikuti instrumen. Jawaban Anda telah tersimpan dengan baik.
                    </div>

                    <!-- ✅ SATU CARD (TIME + WARNING + BUTTON) -->
                    <div class="finish-action mt-4 text-start">
                        <div class="finish-time text-center mb-3">
                            <i class="ri-time-line me-1"></i>
                            Selesai pada: {{ date('d F Y H:i:s') }}
                        </div>

                        <div class="finish-note mb-3">
                            <i class="ri-alert-line me-2"></i>
                            <b>Form pelatihan yang diinginkan WAJIB DIISI</b>
                        </div>

                        <a href="{{ url('/pelatihanform') }}"
                           class="btn btn-primary btn-lg btn-back-blue w-100 d-block">
                            <i class="ri-file-list-3-line me-2"></i> Isi Form Pelatihan yang Diinginkan
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<style>
    .finish-card{
        border-radius:14px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        background:#fff;
    }
    .finish-header{
        border-radius:14px 14px 0 0;
        border:0;
    }
    .finish-title{
        font-size:20px;
        font-weight:700;
    }
    .finish-h3{
        color:#1a4d8e;
        font-weight:700;
    }
    .finish-p{
        font-size:18px;
    }

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

    .finish-alert{
        font-size:16px;
        border-radius:10px;
        margin-bottom: 0;
    }

    /* ✅ 1 BOX (CARD) untuk time + note + button */
    .finish-action{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:16px;
        box-shadow: 0 10px 22px rgba(2,6,23,.06);
    }
    .finish-time{
        font-size:14px;
        color:#6b7280;
    }
    .finish-note{
        background:#fff7e6;
        border:1px solid #ffe0b2;
        color:#b45309;
        border-radius:12px;
        padding:12px 14px;
        font-size:14px;
    }

    /* ✅ warna biru button */
    .btn-back-blue{
        background: #1a4d8e !important;
        border-color: #1a4d8e !important;
        color: #fff !important;
        border-radius:12px !important;
        padding: 14px 22px !important;
        font-weight: 700 !important;
        box-shadow: 0 8px 18px rgba(26, 91, 184, .18);
    }
    .btn-back-blue:hover,
    .btn-back-blue:focus{
        background: #164f9e !important;
        border-color: #164f9e !important;
        color: #fff !important;
    }
</style>

@section('sipproja-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerKeys = [
            "quiz_start_time",
            "quiz1_start_time",
            "quiz_timer_start",
            "quiz1_timer_start",
        ];

        timerKeys.forEach(key => localStorage.removeItem(key));

        if (typeof sessionStorage !== 'undefined') {
            timerKeys.forEach(key => sessionStorage.removeItem(key));
        }

        timerKeys.forEach(key => {
            document.cookie = key + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        });
    });

    window.onload = function() {
        localStorage.removeItem("quiz1_start_time");
    };
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerKeys = [
            "quiz2_remaining_seconds",
            "quiz2_last_update",
            "quiz_start_time",
            "quiz2_start_time"
        ];

        timerKeys.forEach(key => {
            localStorage.removeItem(key);
            sessionStorage.removeItem(key);
        });

        timerKeys.forEach(key => {
            document.cookie = key + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        });
    });
</script>
@endsection
