@extends('layouts.main-user')

@section('mycontent')
<link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

<!-- ============================================ -->
<!-- FLOATING NAVBAR + SLIDE PANEL -->
<!-- ============================================ -->

<!-- TOGGLE BUTTON HALF OVAL -->
<div id="floatingNav">
    <!-- TIMER NAV -->
    <div id="quizTimer">
        <i class="ri-timer-line me-1"></i>
        <span id="timerText">00:00:00</span>
    </div>

    <button id="toggleFloating" class="floating-toggle half-oval-btn">
        <i class="ri-arrow-right-s-line"></i>
    </button>
</div>

<!-- SLIDE PANEL -->
<div id="floatingPanel" class="floating-panel">

    <!-- CLOSE BUTTON -->
    <button class="close-panel-btn" id="closePanelBtn">
        <i class="ri-close-line"></i>
    </button>

    <h4 class="floating-title">
        <i class="ri-stack-line me-1"></i> Daftar Studi Kasus
    </h4>

    <div class="nav-case-item">
        <span>Studi Kasus 1</span>
        <i class="ri-checkbox-circle-line text-success"></i>
    </div>
    <div class="nav-case-item">
        <span>Studi Kasus 2</span>
        <i class="ri-checkbox-blank-circle-line text-secondary"></i>
    </div>
    <div class="nav-case-item">
        <span>Studi Kasus 3</span>
        <i class="ri-checkbox-blank-circle-line text-secondary"></i>
    </div>
    <div class="nav-case-item">
        <span>Studi Kasus 4</span>
        <i class="ri-checkbox-circle-line text-success"></i>
    </div>

</div>



<!-- ============================================ -->
<!-- CONTENT WRAPPER ALLOW SHIFT -->
<!-- ============================================ -->

<div class="content-wrapper">

    <div class="container-fluid">

        <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
        <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">


        <!-- ============================================ -->
        <!-- QUIZ CARD -->
        <!-- ============================================ -->
        <div class="row">
            <div class="col-xl-12">

                <div class="card border-0 shadow-sm" style="border-radius:14px; position: relative;">

                    <!-- HEADER BADUY -->
                    <div class="card-header baduy-bg" style="border-radius:14px 14px 0 0;">
                        <h5 class="mb-0 text-white d-flex align-items-center" style="font-size:20px; font-weight:700;">
                            <i class="ri-book-open-line me-2"></i> Instrumen
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <div class="row">


                            <!-- ================================== -->
                            <!-- STUDI KASUS DALAM BOX -->
                            <!-- ================================== -->
                            <div class="col-12 mb-3">
                                <div class="big-box">
                                    <h5 class="box-title studi-title">
                                        <i class="ri-article-line me-1" style="color:#1a4d8e;"></i>
                                        <span style="color:#1a4d8e; font-weight:700;">Studi Kasus</span>
                                    </h5>

                                    <p class="box-text" style="font-size:19px; line-height:1.6;">
                                        {!! nl2br(e($case->case)) !!}
                                    </p>
                                </div>
                            </div>

                            <!-- ================================== -->
                            <!-- SOAL + PILIHAN JAWABAN DALAM 1 BOX -->
                            <!-- ================================== -->
                            <div class="col-12">
                                <div class="big-box">

                                    <!-- Judul Soal -->
                                    <h5 class="box-title studi-title">
                                        <i class="ri-question-line me-1 text-primary"></i>
                                        <span style="color:#1a4d8e; font-weight:700;">Soal</span>
                                    </h5>

                                    <!-- Teks Soal -->
                                    <p class="soal-text mb-4">
                                        {{ $soal->soal }}
                                    </p>

                                    <form action="{{ route('quiz2.submit') }}" method="POST">
                                        @csrf

                                        <!-- Hidden Inputs -->
                                        <input type="hidden" name="soal_id" value="{{ $soal->soal_id }}">
                                        <input type="hidden" name="tahap" value="{{ $tahap }}">
                                        <input type="hidden" name="sub_indikator_id" value="{{ $sub_indikator_id }}"> <!-- ID ASLI -->
                                        <input type="hidden" name="encoded_kegiatan_id" value="{{ $encoded_kegiatan_id }}">
                                        <input type="hidden" name="encoded_sub_indikator_id" value="{{ $encoded_sub_indikator_id }}">
                                        <input type="hidden" name="encoded_no_urut" value="{{ $encoded_no_urut }}">
                                        <input type="hidden" name="nip" value="{{ $nip }}">
                                        <input type="text" name="bobot" id="bobot">

                                        <!-- Pilihan Jawaban -->
                                        @foreach ($choices as $c)
                                        <label class="quiz-choice">
                                            <input type="radio" name="pilihan_jawaban_id" class="form-check-input pilihan radio-inside"
                                                value="{{ $c->soal_jawaban_id }}" data-bobot="{{ $c->bobot }}"
                                                id="choice{{ $c->soal_jawaban_id }}" required>
                                            <span class="choice-text">
                                                {{ $c->pilihan_jawaban }}
                                            </span>
                                        </label>
                                        @endforeach

                                        <button type="submit" class="btn btn-primary btn-lg mt-4 btn-jawab w-100 btn-jawab">
                                            <i class="ri-checkbox-circle-line me-2"></i> Kirim Jawaban
                                        </button>
                                    </form>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>


    <!-- ============================================ -->
    <!-- FULL CSS (HALF OVAL BUTTON + RESPONSIVE) -->
    <!-- ============================================ -->

    <style>
        /* BOX BESAR UMUM */
        .big-box {
            background: #f6f8ff;
            border: 1px solid #d7e2ff;
            border-radius: 12px;
            padding: 20px 22px;
        }

        .box-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .box-text {
            font-size: 17px;
            line-height: 1.6;
            text-align: justify;
        }

        /* BOX SOAL DI DALAM BIG BOX */
        .soal-text {
            font-size: 19px;
            font-weight: 700;
            line-height: 1.6;
        }

        /* PILIHAN JAWABAN BOX */
        .quiz-choice {
            background: #f4f7ff;
            border: 1px solid #d6e4ff;
            border-radius: 10px;
            padding: 14px 18px;
            transition: 0.2s;
            margin-bottom: 12px;
            cursor: pointer;

            display: flex;
            align-items: center;
        }

        .quiz-choice:hover {
            background: #e8f0ff;
            border-color: #b7d0ff;
        }

        /* RADIO KIRI DALAM BOX */
        .radio-inside {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin: 0;
            flex-shrink: 0;
        }

        /* TEKS JAWABAN */
        .choice-text {
            margin-left: 12px;
            font-size: 17px;
            font-weight: 500;
            text-align: justify;
        }

        /* BUTTON */
        .btn-jawab {
            border-radius: 10px;
            padding-left: 40px;
            padding-right: 40px;
        }
    </style>



    @endsection



    @section('sipproja-js')
    <script>
        // OPEN PANEL
        document.getElementById("toggleFloating").addEventListener("click", function() {
            document.getElementById("floatingPanel").classList.add("open");
            document.querySelector(".content-wrapper").classList.add("shift");
            document.getElementById("floatingNav").classList.add("hide");
        });

        // CLOSE PANEL
        document.getElementById("closePanelBtn").addEventListener("click", function() {
            document.getElementById("floatingPanel").classList.remove("open");
            document.querySelector(".content-wrapper").classList.remove("shift");
            document.getElementById("floatingNav").classList.remove("hide");
        });
    </script>
    <script>
        document.querySelectorAll('.pilihan').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('bobot').value = this.dataset.bobot;
            });
        });
    </script>
    <script>
        // Durasi timer (120 menit = 7200 detik)
        const TOTAL_TIME = 120 * 60;

        // Ambil waktu mulai dari localStorage
        let startTime = localStorage.getItem("quiz_start_time");

        if (!startTime) {
            // Jika belum ada, set waktu mulai sekarang
            startTime = Date.now();
            localStorage.setItem("quiz_start_time", startTime);
        }

        function updateTimer() {
            const now = Date.now();
            const elapsed = Math.floor((now - startTime) / 1000); // detik
            const remaining = TOTAL_TIME - elapsed;

            if (remaining <= 0) {
                document.getElementById("timerText").textContent = "00:00:00";
                localStorage.removeItem("quiz_start_time");
                alert("Waktu Habis!");
                return;
            }

            // Hitung jam, menit & detik
            const hours = String(Math.floor(remaining / 3600)).padStart(2, "0");
            const minutes = String(Math.floor((remaining % 3600) / 60)).padStart(2, "0");
            const seconds = String(remaining % 60).padStart(2, "0");

            // Tampilkan dalam format 00:00:00
            document.getElementById("timerText").textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Update setiap 1 detik
        setInterval(updateTimer, 1000);
        updateTimer();
    </script>
    @endsection