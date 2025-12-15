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
        <i class="ri-stack-line me-1"></i> Daftar Indikator
    </h4>

    <!-- PROGRESS INFO -->
    @php
    $totalIndikators = count($indikatorList);
    $passedIndikators = 0;
    $currentNumber = 0;

    foreach($indikatorList as $index => $indikatorItem) {
    if($indikatorItem['is_passed']) $passedIndikators++;
    if($indikatorItem['is_current']) $currentNumber = $index + 1;
    }
    $progressPercentage = $totalIndikators > 0 ? round(($passedIndikators / $totalIndikators) * 100) : 0;
    @endphp

    <div class="progress-info mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <small class="text-muted">
                <i class="ri-progress-3-line me-1"></i>Progress
            </small>
            <small class="text-primary fw-bold">{{ $passedIndikators }}/{{ $totalIndikators }}</small>
        </div>
        <div class="progress" style="height: 6px;">
            <div class="progress-bar bg-success" role="progressbar"
                style="width: {{ $progressPercentage }}%"
                aria-valuenow="{{ $progressPercentage }}"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>
        </div>
        @if($currentNumber > 0)
        <small class="text-muted d-block mt-2">
            <i class="ri-arrow-right-line me-1"></i>Sedang mengerjakan: <strong>#{{ $currentNumber }}</strong>
        </small>
        @endif
    </div>

    <!-- INDIKATOR LIST -->
    <div id="indikatorListContainer">
        @foreach($indikatorList as $indikatorItem)
        <div class="nav-indikator-item 
            @if($indikatorItem['is_current']) active-indikator @endif
            @if($indikatorItem['is_passed']) passed-indikator @else not-passed-indikator @endif"
            data-indikator-id="{{ $indikatorItem['indikator_id'] }}"
            data-no-urut="{{ $indikatorItem['no_urut'] }}"
            title="Urut: {{ $indikatorItem['no_urut'] }}">

            <!-- NUMBER BADGE -->
            <div class="d-flex align-items-start">
                <div class="indikator-number me-2">
                    <span class="badge 
                        @if($indikatorItem['is_current']) bg-primary
                        @elseif($indikatorItem['is_passed']) bg-success
                        @else bg-secondary @endif">
                        {{ $loop->iteration }}
                    </span>
                </div>

                <!-- INDIKATOR INFO -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="indikator-title" style="font-weight: {{ $indikatorItem['is_current'] ? '700' : '500' }}">
                                {{ $indikatorItem['title'] }}
                            </span>
                            @if($indikatorItem['is_current'])
                            <span class="badge bg-warning text-dark badge-sm ms-2">
                                <i class="ri-play-circle-line me-1"></i>Sekarang
                            </span>
                            @endif
                        </div>

                        <!-- STATUS ICON -->
                        @if($indikatorItem['is_passed'])
                        <i class="ri-checkbox-circle-fill text-success fs-5" title="Sudah selesai"></i>
                        @else
                        <i class="ri-checkbox-blank-circle-line text-secondary fs-5" title="Belum selesai"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if(count($indikatorList) === 0)
        <div class="text-center py-4">
            <i class="ri-inbox-line" style="font-size: 40px; color: #ccc;"></i>
            <p class="text-muted mt-2 mb-0">Tidak ada indikator tersedia</p>
        </div>
        @endif
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
                            <i class="ri-book-open-line me-2"></i>
                            @php
                            $kegiatan = \App\Models\Kegiatan::find(Hashids::decode($encoded_kegiatan_id)[0] ?? 0);
                            @endphp
                            {{ $kegiatan->kegiatan_name ?? 'Instrumen' }}
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">

                            <!-- ================================== -->
                            <!-- SOAL DALAM BOX -->
                            <!-- ================================== -->
                            <div class="col-12">
                                <div class="big-box no-select prevent-copy">

                                    <!-- Judul Soal -->
                                    <h5 class="box-title studi-title">
                                        <i class="ri-question-line me-1 text-primary"></i>
                                        <span style="color:#1a4d8e; font-weight:700;">
                                            Soal {{ $no_urut }}
                                            @if(isset($answered) && in_array($soal->soal_id, $answered))
                                            <span class="badge bg-success ms-2">Sudah dijawab</span>
                                            @endif
                                        </span>
                                    </h5>

                                    <!-- Teks Soal -->
                                    <p class="soal-text mb-4">
                                        {{ $soal->soal }}
                                    </p>

                                    <form action="{{ route('quiz1.submit') }}" method="POST">
                                        @csrf

                                        <!-- Hidden Inputs -->
                                        <input type="hidden" name="soal_id" value="{{ $soal->soal_id }}">
                                        <input type="hidden" name="tahap" value="{{ $tahap }}">
                                        <input type="hidden" name="indikator_id" value="{{ $indikator_id }}">
                                        <input type="hidden" name="encoded_kegiatan_id" value="{{ $encoded_kegiatan_id }}">
                                        <input type="hidden" name="encoded_indikator_id" value="{{ $encoded_indikator_id }}">
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
        text-align: justify;
        /* Tambahkan ini untuk rata kanan-kiri */
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

    /* ======================================== */
    /* DESKTOP HALF OVAL BUTTON */
    /* ======================================== */
    #floatingNav {
        position: fixed;
        top: 142px;
        left: 0;
        z-index: 2000;
        transition: .2s;
    }

    #floatingNav.hide {
        opacity: 0;
        pointer-events: none;
    }

    @media(min-width: 769px) {
        .half-oval-btn {
            position: relative;
            left: -28px;
            width: 58px;
            height: 50px;
            background: #1a4d8e;
            border: none;
            color: #fff;
            border-radius: 0 30px 30px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transition: .25s ease;
            transform: translateX(20px);
        }

        .half-oval-btn:hover {
            left: -20px;
            background: #163f74;
        }
    }

    /* ======================================== */
    /* MOBILE BUTTON (BULAT KANAN) */
    /* ======================================== */
    @media(max-width: 768px) {
        #floatingNav {
            top: 70px;
            right: 18px;
            left: auto;
        }

        .floating-toggle {
            width: 42px;
            height: 42px;
            background: #1a4d8e;
            color: white;
            border-radius: 50%;
            font-size: 22px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .2);
        }
    }

    /* ======================================== */
    /* SLIDE PANEL */
    /* ======================================== */
    .floating-panel {
        position: fixed;
        top: 0;
        left: -260px;
        width: 260px;
        height: 100vh;
        background: white;
        padding: 20px;
        box-shadow: 3px 0 15px rgba(0, 0, 0, 0.15);
        overflow-y: auto;
        transition: 0.3s ease;
        z-index: 3000;
    }

    @media(max-width: 768px) {
        .floating-panel {
            width: 100%;
            left: -100%;
        }
    }

    .floating-panel.open {
        left: 0;
    }

    .close-panel-btn {
        position: absolute;
        right: 1px;
        top: 15px !important;
        background: transparent;
        border: none;
        font-size: 20px;
        color: #1a4d8e;
        cursor: pointer;
    }

    .nav-indikator-item {
        padding: 12px;
        background: #f5f7ff;
        border: 1px solid #e0e6ff;
        border-radius: 8px;
        margin-bottom: 10px;
        font-size: 17px !important;
        margin-top: 18px;
    }

    /* ======================================== */
    /* SHIFT CONTENT (DESKTOP ONLY) */
    /* ======================================== */
    .content-wrapper {
        transition: 0.3s ease;
    }

    @media(min-width: 769px) {
        .content-wrapper.shift {
            margin-left: 260px;
        }
    }

    /* =============================== */
    /* MINI NAV TIMER – LEBIH PENDEK  */
    /* =============================== */
    #quizTimer {
        position: fixed;
        top: 200px;
        left: -6px;
        background: #1a4d8e;
        color: white;
        padding: 8px 12px;
        border-radius: 0 20px 20px 0;
        font-size: 15px;
        font-weight: 600;
        z-index: 2100;
        display: flex;
        align-items: center;
        gap: 6px;
        width: fit-content;
        max-width: 120px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
    }

    #quizTimer i {
        font-size: 18px;
    }

    /* MOBILE FIX */
    @media(max-width: 768px) {
        #quizTimer {
            top: 115px;
            right: 18px;
            left: auto;
            border-radius: 16px;
            padding: 7px 10px;
            font-size: 14px;
            max-width: 120px;
        }
    }

    /* INDIKATOR ITEM STYLES */
    .nav-indikator-item {
        padding: 15px;
        background: #f5f7ff;
        border: 1px solid #e0e6ff;
        border-radius: 10px;
        margin-bottom: 12px;
        font-size: 15px;
        transition: all 0.2s ease;
        cursor: default;
    }

    .nav-indikator-item:hover {
        background: #e8f4ff;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    /* INDIKATOR YANG SEDANG AKTIF */
    .nav-indikator-item.active-indikator {
        background: linear-gradient(135deg, #e8f4ff 0%, #d4e7ff 100%);
        border: 2px solid #1a4d8e;
        box-shadow: 0 0 0 3px rgba(26, 77, 142, 0.15);
    }

    /* INDIKATOR YANG SUDAH LEWATI */
    .nav-indikator-item.passed-indikator {
        border-left: 5px solid #28a745;
        background: #f8fff8;
    }

    .nav-indikator-item.passed-indikator:hover {
        background: #f0fff0;
    }

    /* INDIKATOR YANG BELUM LEWATI */
    .nav-indikator-item.not-passed-indikator {
        border-left: 5px solid #6c757d;
        opacity: 0.9;
    }

    .indikator-title {
        font-size: 15px;
        color: #333;
        line-height: 1.4;
    }

    .indikator-number .badge {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-sm {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .progress-info {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        margin-bottom: 15px;
    }

    .floating-title {
        color: #1a4d8e;
        font-weight: 700;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e6ff;
        font-size: 18px;
    }

    /* CSS untuk mencegah seleksi teks */
    .no-select {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .protected-text {
        cursor: default;
        position: relative;
    }

    .protected-text::after {
        display: none;
    }

    .case-content,
    .soal-content,
    .answer-text {
        position: relative;
    }

    .quiz-choice,
    .btn-jawab,
    .form-check-input,
    .radio-inside,
    button,
    input[type="radio"],
    input[type="submit"] {
        cursor: pointer !important;
        user-select: none !important;
    }

    .text-protector {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        pointer-events: none;
    }

    .protected-container {
        position: relative;
    }

    .copy-warning {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
        display: none;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
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

    // Navigasi untuk indikator yang sudah passed
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nav-indikator-item.passed-indikator').forEach(item => {
            item.style.cursor = 'pointer';

            item.addEventListener('click', function(e) {
                if (e.target.tagName === 'I' || e.target.classList.contains('badge')) {
                    return;
                }

                const indikatorId = this.dataset.indikatorId;
                const noUrut = this.dataset.noUrut;

                if (!this.classList.contains('active-indikator')) {
                    if (confirm('Pindah ke indikator ini?')) {
                        const encodedIndikatorId = btoa(indikatorId);
                        const encodedNoUrut = btoa(1);

                        window.location.href = `{{ route('quiz1.show', [
                            'tahap' => $tahap,
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_indikator_id' => 'INDIKATOR_ID_PLACEHOLDER',
                            'encoded_no_urut' => 'NO_URUT_PLACEHOLDER'
                        ]) }}`
                            .replace('INDIKATOR_ID_PLACEHOLDER', encodedIndikatorId)
                            .replace('NO_URUT_PLACEHOLDER', encodedNoUrut);
                    }
                }
            });

            item.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active-indikator')) {
                    this.style.transform = 'translateY(-3px)';
                    this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
                }
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = '';
            });
        });
    });

    // Update bobot saat radio button dipilih
    document.querySelectorAll('.pilihan').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('bobot').value = this.dataset.bobot;
        });
    });

    // Timer untuk Quiz 1 (120 menit = 5400 detik)
    const TOTAL_TIME = 120 * 60;
    let startTime = localStorage.getItem("quiz1_start_time");

    if (!startTime) {
        startTime = Date.now();
        localStorage.setItem("quiz1_start_time", startTime);
    }

    function updateTimer() {
        const now = Date.now();
        const elapsed = Math.floor((now - startTime) / 1000);
        const remaining = TOTAL_TIME - elapsed;

        if (remaining <= 0) {
            document.getElementById("timerText").textContent = "00:00:00";
            localStorage.removeItem("quiz1_start_time");
            alert("Waktu Habis!");
            return;
        }

        const hours = String(Math.floor(remaining / 3600)).padStart(2, "0");
        const minutes = String(Math.floor((remaining % 3600) / 60)).padStart(2, "0");
        const seconds = String(remaining % 60).padStart(2, "0");

        document.getElementById("timerText").textContent = `${hours}:${minutes}:${seconds}`;
    }

    setInterval(updateTimer, 1000);
    updateTimer();
</script>
@endsection