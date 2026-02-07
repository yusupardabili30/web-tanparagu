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

        <!-- PROGRESS INFO -->
        @php
            $totalCases = count($caseList);
            $passedCases = 0;
            $currentNumber = 0;

            foreach ($caseList as $index => $caseItem) {
                if ($caseItem['is_passed']) {
                    $passedCases++;
                }
                if ($caseItem['is_current']) {
                    $currentNumber = $index + 1;
                }
            }
            $progressPercentage = $totalCases > 0 ? round(($passedCases / $totalCases) * 100) : 0;
        @endphp

        <div class="progress-info mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">
                    <i class="ri-progress-3-line me-1"></i>Progress
                </small>
                <small class="text-primary fw-bold">{{ $passedCases }}/{{ $totalCases }}</small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercentage }}%"
                    aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            @if ($currentNumber > 0)
                <small class="text-muted d-block mt-2">
                    <i class="ri-arrow-right-line me-1"></i>Sedang mengerjakan: <strong>#{{ $currentNumber }}</strong>
                </small>
            @endif
        </div>

        <!-- CASE LIST -->
        <div id="caseListContainer">
            @foreach ($caseList as $caseItem)
                <div class="nav-case-item
            @if ($caseItem['is_current']) active-case @endif
            @if ($caseItem['is_passed']) passed-case @else not-passed-case @endif"
                    data-case-id="{{ $caseItem['soal_case_id'] }}" data-sub-id="{{ $caseItem['sub_indikator_id'] }}"
                    title="Sub Indikator: {{ $caseItem['sub_indikator_id'] }} | Urut: {{ $caseItem['no_urut'] }}">

                    <!-- NUMBER BADGE -->
                    <div class="d-flex align-items-start">
                        <div class="case-number me-2">
                            <span
                                class="badge
                        @if ($caseItem['is_current']) bg-primary
                        @elseif($caseItem['is_passed']) bg-success
                        @else bg-secondary @endif">
                                {{ $loop->iteration }}
                            </span>
                        </div>

                        <!-- CASE INFO -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="case-title"
                                        style="font-weight: {{ $caseItem['is_current'] ? '700' : '500' }}">
                                        {{ $caseItem['title'] }}
                                    </span>
                                    @if ($caseItem['is_current'])
                                        <span class="badge bg-warning text-dark badge-sm ms-2">
                                            <i class="ri-play-circle-line me-1"></i>Sekarang
                                        </span>
                                    @endif
                                </div>

                                <!-- STATUS ICON -->
                                @if ($caseItem['is_passed'])
                                    <i class="ri-checkbox-circle-fill text-success fs-5" title="Sudah melewati"></i>
                                @else
                                    <i class="ri-checkbox-blank-circle-line text-secondary fs-5" title="Belum melewati"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if (count($caseList) === 0)
                <div class="text-center py-4">
                    <i class="ri-inbox-line" style="font-size: 40px; color: #ccc;"></i>
                    <p class="text-muted mt-2 mb-0">Tidak ada studi kasus tersedia</p>
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
                                    $kegiatan = \App\Models\Kegiatan::find(
                                        Hashids::decode($encoded_kegiatan_id)[0] ?? 0,
                                    );
                                @endphp
                                {{ $kegiatan->kegiatan_name ?? 'Instrumen' }}
                            </h5>
                        </div>

                        <div class="card-body p-4">

                            <div class="row">

                                <!-- ================================== -->
                                <!-- STUDI KASUS DALAM BOX -->
                                <!-- ================================== -->
                                <div class="col-12 mb-3">
                                    <div class="big-box no-select prevent-copy">
                                        <h5 class="box-title studi-title">
                                            <i class="ri-article-line me-1" style="color:#1a4d8e;"></i>
                                            <span style="color:#1a4d8e; font-weight:700;">
                                                @php
                                                    $judul = 'Studi Kasus ' . ($case->no_urut ?? '1');
                                                @endphp
                                                {{ $judul }}
                                            </span>
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
                                    <div class="big-box no-select prevent-copy">

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
                                            <input type="hidden" name="sub_indikator_id" value="{{ $sub_indikator_id }}">
                                            <input type="hidden" name="encoded_kegiatan_id"
                                                value="{{ $encoded_kegiatan_id }}">
                                            <input type="hidden" name="encoded_sub_indikator_id"
                                                value="{{ $encoded_sub_indikator_id }}">
                                            <input type="hidden" name="encoded_no_urut" value="{{ $encoded_no_urut }}">
                                            <input type="hidden" name="nip" value="{{ $nip }}">

                                            <input type="text" name="bobot" id="bobot">

                                            <!-- TIMER HIDDEN (FIX) -->
                                            <input type="hidden" name="remaining_seconds" id="remainingSecondsInput"
                                                value="{{ $remaining_seconds ?? 7200 }}">
                                            <input type="hidden" name="frontend_time_string" id="frontendTimeString">

                                            <!-- Pilihan Jawaban -->
                                            @foreach ($choices as $c)
                                                <label class="quiz-choice">
                                                    <input type="radio" name="pilihan_jawaban_id"
                                                        class="form-check-input pilihan radio-inside"
                                                        value="{{ $c->soal_jawaban_id }}"
                                                        data-bobot="{{ $c->bobot }}"
                                                        id="choice{{ $c->soal_jawaban_id }}" required>
                                                    <span class="choice-text">
                                                        {{ $c->pilihan_jawaban }}
                                                    </span>
                                                </label>
                                            @endforeach

                                            <button type="submit"
                                                class="btn btn-primary btn-lg mt-4 btn-jawab w-100 btn-jawab">
                                                <i class="ri-checkbox-circle-line me-2"></i> Kirim Jawaban
                                            </button>
                                        </form>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button type="button" id="pauseBtn"
                                                class="btn btn-primary btn-lg pause-btn">
                                                <i class="ri-pause-circle-line me-2"></i> Jeda
                                            </button>
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
        <!-- FULL CSS -->
        <!-- ============================================ -->
        <style>
            /* =========================
               THEME VARIABLES (ADDED)
               ========================= */
            :root {
                --mm-primary: #1a4d8e;
                /* <<< ganti ini doang kalau mau beda */
                --mm-primary-hover: #163f74;
                --mm-primary-focus: rgba(26, 75, 184, .25);

                --mm-font: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            }

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
            /* MINI NAV TIMER */
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

            /* CASE ITEM STYLES */
            .nav-case-item {
                padding: 15px;
                background: #f5f7ff;
                border: 1px solid #e0e6ff;
                border-radius: 10px;
                margin-bottom: 12px;
                font-size: 15px;
                transition: all 0.2s ease;
                cursor: default;
            }

            .nav-case-item:hover {
                background: #e8f4ff;
                transform: translateY(-2px);
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            }

            /* CASE YANG SEDANG AKTIF */
            .nav-case-item.active-case {
                background: linear-gradient(135deg, #e8f4ff 0%, #d4e7ff 100%);
                border: 2px solid #1a4d8e;
                box-shadow: 0 0 0 3px rgba(26, 77, 142, 0.15);
            }

            /* CASE YANG SUDAH LEWATI */
            .nav-case-item.passed-case {
                border-left: 5px solid #28a745;
                background: #f8fff8;
            }

            .nav-case-item.passed-case:hover {
                background: #f0fff0;
            }

            /* CASE YANG BELUM LEWATI */
            .nav-case-item.not-passed-case {
                border-left: 5px solid #6c757d;
                opacity: 0.9;
            }

            .case-title {
                font-size: 15px;
                color: #333;
                line-height: 1.4;
            }

            .case-number .badge {
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

            /* ICON SIZE */
            .fs-5 {
                font-size: 1.25rem !important;
            }

            /* CSS untuk mencegah seleksi teks TANPA mengganggu interaksi */
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

            /* =========================
               BACK WARNING MODAL (REDESIGN) (FIXED)
               ========================= */
            #backWarningModal .modal-content {
                font-family: var(--mm-font);
                border-radius: 16px;
                overflow: hidden;
                border: 1px solid rgba(0, 0, 0, .06);
                box-shadow: 0 18px 50px rgba(0, 0, 0, .18);
                animation: slideInDown .28s ease-out;
            }

            #backWarningModal .modal-header {
                background: var(--mm-primary);
                border-bottom: 0;
                padding: 16px 20px;
            }

            #backWarningModal .modal-title {
                font-size: 17px;
                font-weight: 700;
                letter-spacing: .2px;
                color: #fff;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            #backWarningModal .modal-body {
                padding: 22px 22px 10px;
                background: #fff;
            }

            #backWarningModal .modal-footer {
                border-top: 0;
                padding: 14px 22px 22px;
                background: #fff;
            }

            #backWarningModal .mm-hero-icon {
                width: 56px;
                height: 56px;
                border-radius: 14px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(26, 77, 142, .08);
                color: var(--mm-primary);
                margin-bottom: 12px;
            }

            #backWarningModal h5 {
                font-weight: 800;
                margin-bottom: 8px;
            }

            #backWarningModal .alert {
                border-radius: 12px;
                border: 1px solid rgba(26, 77, 142, .14);
                background: rgba(26, 77, 142, .06);
                color: #20324a;
            }

            #backWarningModal .btn-primary {
                background: var(--mm-primary);
                border: none;
                padding: 11px 18px;
                border-radius: 12px;
                font-weight: 700;
                min-width: 190px;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            }

            #backWarningModal .btn-primary:hover {
                background: var(--mm-primary-hover);
                transform: translateY(-1px);
                box-shadow: 0 10px 22px rgba(26, 77, 142, .22);
            }

            #backWarningModal .btn-primary:focus,
            #backWarningModal .btn-primary:focus-visible {
                outline: none;
                box-shadow: 0 0 0 .25rem var(--mm-primary-focus);
            }

            #backWarningModal .btn-primary:disabled {
                opacity: .75;
                transform: none;
                box-shadow: none;
            }

            #backWarningModal.modal {
                backdrop-filter: blur(3px);
            }

            @keyframes slideInDown {
                from {
                    transform: translateY(-18px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                10%,
                30%,
                50%,
                70%,
                90% {
                    transform: translateX(-5px);
                }

                20%,
                40%,
                60%,
                80% {
                    transform: translateX(5px);
                }
            }

            .shake-modal {
                animation: shake .5s ease-in-out;
            }

            .pause-btn {
                border-radius: 10px;
                font-weight: 700;
                padding-left: 22px;
                padding-right: 22px;
                background: #163f74 !important;
                border-color: #163f74 !important;
                color: #fff !important;
                box-shadow: none !important;
                transform: none !important;
                filter: none !important;
            }

            /* matiin hover/focus/active supaya ga berubah */
            .pause-btn:hover,
            .pause-btn:active,
            .pause-btn:focus,
            .pause-btn:focus-visible {
                background: #163f74 !important;
                border-color: #163f74 !important;
                color: #fff !important;
                box-shadow: none !important;
                outline: none !important;
                transform: none !important;
                filter: none !important;
            }

            .swal2-confirm.btn-primary {
                background: var(--mm-primary) !important;
                border: none;
            }

            .swal2-confirm.btn-primary:hover {
                background: var(--mm-primary-hover) !important;
            }

            /* kasih jarak antar tombol SweetAlert2 */
            .swal2-actions {
                gap: 12px;
                /* ubah sesuai mau: 8px / 12px / 16px */
            }

            /* optional: biar tombolnya seimbang */
            .swal2-actions .btn {
                min-width: 110px;
            }

            /* Jarak antar tombol */
            .swal2-actions {
                gap: 12px;
            }

            /* Style tombol swal ikut tema (tanpa hover) */
            .swal2-actions .btn {
                border-radius: 12px;
                font-weight: 700;
                min-width: 110px;
            }

            /* Tombol confirm (Ya, Jeda) */
            .swal2-actions .btn.btn-primary,
            .swal2-actions .btn.btn-primary:hover,
            .swal2-actions .btn.btn-primary:active,
            .swal2-actions .btn.btn-primary:focus,
            .swal2-actions .btn.btn-primary:focus-visible {
                background: #163f74 !important;
                border-color: #163f74 !important;
                box-shadow: none !important;
                outline: none !important;
                transform: none !important;
                filter: none !important;
            }

            /* Tombol cancel (Batal) */
            .swal2-actions .btn.btn-outline-secondary {
                border-color: #163f74 !important;
                color: #163f74 !important;
                background: transparent !important;
            }

            .swal2-actions .btn.btn-outline-secondary:hover,
            .swal2-actions .btn.btn-outline-secondary:active,
            .swal2-actions .btn.btn-outline-secondary:focus,
            .swal2-actions .btn.btn-outline-secondary:focus-visible {
                border-color: #163f74 !important;
                color: #163f74 !important;
                background: transparent !important;
                box-shadow: none !important;
                outline: none !important;
                transform: none !important;
                filter: none !important;
            }
        </style>
    @endsection

    @section('sipproja-js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const pauseBtn = document.getElementById('pauseBtn');
                if (!pauseBtn) return;

                pauseBtn.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Jeda Pengerjaan?',
                        text: 'Waktu akan disimpan dan Anda akan kembali ke halaman Profil PTK.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Jeda',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true,
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-outline-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {

                            if (typeof saveTimeToLocalStorage === 'function') {
                                saveTimeToLocalStorage();
                            }

                            Swal.fire({
                                title: 'Menyimpan progres...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => Swal.showLoading()
                            });

                            setTimeout(() => {
                                window.location.href =
                                    `{{ route('ptk.show', [
                                        'encode_kegiatan_id' => $encoded_kegiatan_id,
                                        'nip' => $nip,
                                    ]) }}`;
                            }, 800);
                        }
                    });
                });
            });
        </script>

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

            // NAV CASE (FIX: item undefined -> loop all nav items)
            document.querySelectorAll('.nav-case-item').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    if (e.target.tagName === 'I' || e.target.classList.contains('badge')) {
                        return;
                    }

                    const subId = this.dataset.subId;
                    const caseId = this.dataset.caseId; // keep if needed

                    if (!this.classList.contains('active-case')) {
                        if (confirm('Pindah ke studi kasus ini?')) {
                            const encodedSubId = btoa(subId);
                            const encodedNoUrut = btoa(1);

                            window.location.href =
                                `{{ route('quiz2.show', [
                                    'tahap' => $tahap,
                                    'encoded_kegiatan_id' => $encoded_kegiatan_id,
                                    'nip' => $nip,
                                    'encoded_sub_indikator_id' => 'SUB_ID_PLACEHOLDER',
                                    'encoded_no_urut' => 'NO_URUT_PLACEHOLDER',
                                ]) }}`
                                .replace('SUB_ID_PLACEHOLDER', encodedSubId)
                                .replace('NO_URUT_PLACEHOLDER', encodedNoUrut);
                        }
                    }
                });

                item.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active-case')) {
                        this.style.transform = 'translateY(-3px)';
                        this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
                    }
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
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
            // Timer yang TEPAT sinkron dengan database
            let remainingSeconds = {{ $remaining_seconds ?? 7200 }};
            let lastUpdateTime = Date.now();
            let isInitialized = false;

            // Format waktu untuk ditampilkan
            function formatTime(seconds) {
                const hours = String(Math.floor(seconds / 3600)).padStart(2, "0");
                const minutes = String(Math.floor((seconds % 3600) / 60)).padStart(2, "0");
                const secs = String(seconds % 60).padStart(2, "0");
                return `${hours}:${minutes}:${secs}`;
            }

            // Update timer display DAN input hidden
            function updateTimerDisplay() {
                const timeString = formatTime(remainingSeconds);
                document.getElementById("timerText").textContent = timeString;

                // UPDATE INPUT HIDDEN UNTUK DIKIRIM KE BACKEND
                document.getElementById("remainingSecondsInput").value = remainingSeconds;
                document.getElementById("frontendTimeString").value = timeString;

                // Debug log
                console.log("🕐 Timer updated:", {
                    seconds: remainingSeconds,
                    display: timeString,
                    timestamp: new Date().toLocaleTimeString()
                });
            }

            // Simpan waktu ke localStorage
            function saveTimeToLocalStorage() {
                localStorage.setItem("quiz2_remaining_seconds", remainingSeconds);
                localStorage.setItem("quiz2_last_update", Date.now());
                localStorage.setItem("quiz2_display_time", formatTime(remainingSeconds));
            }

            // Load waktu dari localStorage dengan memperhitungkan waktu yang telah berlalu
            function loadAndContinueTime() {
                @if (!($reset_localstorage ?? false))
                    const savedRemaining = localStorage.getItem("quiz2_remaining_seconds");
                    const lastUpdate = localStorage.getItem("quiz2_last_update");
                    const savedDisplay = localStorage.getItem("quiz2_display_time");

                    if (savedRemaining && lastUpdate) {
                        const now = Date.now();
                        const elapsedSeconds = Math.floor((now - parseInt(lastUpdate)) / 1000);
                        const savedSeconds = parseInt(savedRemaining);

                        const continuedSeconds = Math.max(0, savedSeconds - elapsedSeconds);

                        // Gunakan yang TERKECIL antara waktu lanjutan dan waktu dari database
                        remainingSeconds = Math.min(continuedSeconds, remainingSeconds);

                        lastUpdateTime = now;

                        console.log("✅ Timer dilanjutkan dari localStorage:", {
                            dariLocalStorage: continuedSeconds + " detik",
                            dariDatabase: {{ $remaining_seconds ?? 7200 }} + " detik",
                            dipakai: remainingSeconds + " detik (" + formatTime(remainingSeconds) + ")"
                        });
                    } else {
                        console.log("📊 Menggunakan waktu dari database:", formatTime(remainingSeconds));
                    }
                @else
                    console.log("🆕 Timer dimulai baru:", formatTime(remainingSeconds));
                @endif
            }

            // Main timer function
            function updateTimer() {
                if (remainingSeconds <= 0) {
                    updateTimerDisplay();

                    // Redirect ke finish jika waktu habis
                    setTimeout(() => {
                        window.location.href =
                            "{{ route('quiz.finish', [
                                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                                'nip' => $nip,
                            ]) }}";
                    }, 1000);

                    return;
                }

                remainingSeconds--;
                updateTimerDisplay();

                if (Date.now() - lastUpdateTime > 5000) {
                    saveTimeToLocalStorage();
                    lastUpdateTime = Date.now();
                }
            }

            function initializeTimer() {
                if (isInitialized) return;

                loadAndContinueTime();

                @if ($reset_localstorage ?? false)
                    localStorage.removeItem("quiz2_remaining_seconds");
                    localStorage.removeItem("quiz2_last_update");
                    localStorage.removeItem("quiz2_display_time");
                    console.log("🔄 Timer localStorage direset untuk lanjutkan quiz");
                @endif

                updateTimerDisplay();

                if (!localStorage.getItem("quiz2_remaining_seconds")) {
                    saveTimeToLocalStorage();
                }

                setInterval(updateTimer, 1000);

                isInitialized = true;

                console.log("🎬 Timer diinisialisasi:", {
                    seconds: remainingSeconds,
                    display: formatTime(remainingSeconds),
                    databaseTime: "{{ $remaining_time_formatted ?? '02:00:00' }}"
                });
            }

            window.addEventListener('load', function() {
                initializeTimer();
            });

            window.addEventListener('beforeunload', function() {
                saveTimeToLocalStorage();
                console.log("💾 Timer disimpan sebelum unload:", formatTime(remainingSeconds));
            });

            document.querySelector('form').addEventListener('submit', function(e) {
                updateTimerDisplay();

                console.log("📤 Form akan disubmit dengan waktu:", {
                    seconds: remainingSeconds,
                    display: formatTime(remainingSeconds),
                    inputValue: document.getElementById("remainingSecondsInput").value
                });
            });
        </script>

        <script>
            // ============================================
            // FUNGSI UTAMA: MENCEGAH COPY-PASTE TANPA MENGANGGU INTERAKSI
            // ============================================

            document.addEventListener('DOMContentLoaded', function() {

                // 1. Tampilkan pesan peringatan
                function showWarning(message, type = 'warning') {
                    const oldWarning = document.querySelector('.copy-warning');
                    if (oldWarning) oldWarning.remove();

                    const warning = document.createElement('div');
                    warning.className = 'copy-warning';
                    warning.innerHTML = `
                <i class="ri-error-warning-line me-2"></i>
                <span>${message}</span>
            `;
                    warning.style.backgroundColor = type === 'warning' ? '#dc3545' : '#163f74';

                    document.body.appendChild(warning);

                    warning.style.display = 'block';

                    setTimeout(() => {
                        warning.style.opacity = '0';
                        warning.style.transition = 'opacity 0.3s';
                        setTimeout(() => {
                            if (warning.parentNode) {
                                warning.parentNode.removeChild(warning);
                            }
                        }, 300);
                    }, 3000);
                }

                // 2. Proteksi teks studi kasus
                function protectCaseText() {
                    const caseBox = document.querySelector('.big-box:first-child');
                    if (!caseBox) return;

                    const caseText = caseBox.querySelector('.box-text');
                    if (!caseText) return;

                    caseText.classList.add('protected-text', 'no-select');

                    const originalHtml = caseText.innerHTML;
                    caseText.innerHTML = `
                <div class="protected-container">
                    <div class="text-protector"></div>
                    <div class="case-content">${originalHtml}</div>
                </div>
            `;

                    caseText.addEventListener('copy', function(e) {
                        e.preventDefault();
                        showWarning('Teks studi kasus tidak dapat disalin');
                        return false;
                    });

                    caseText.addEventListener('cut', function(e) {
                        e.preventDefault();
                        showWarning('Teks studi kasus tidak dapat dipotong');
                        return false;
                    });

                    caseText.addEventListener('contextmenu', function(e) {
                        e.preventDefault();
                        showWarning('Menu konteks tidak tersedia untuk teks ini');
                        return false;
                    });

                    caseText.addEventListener('selectstart', function(e) {
                        e.preventDefault();
                        return false;
                    });
                }

                // 3. Proteksi teks soal
                function protectQuestionText() {
                    const questionBox = document.querySelector('.big-box:nth-child(2)');
                    if (!questionBox) return;

                    const questionText = questionBox.querySelector('.soal-text');
                    if (!questionText) return;

                    questionText.classList.add('protected-text', 'no-select');

                    const originalHtml = questionText.innerHTML;
                    questionText.innerHTML = `
                <div class="protected-container">
                    <div class="text-protector"></div>
                    <div class="soal-content">${originalHtml}</div>
                </div>
            `;

                    questionText.addEventListener('copy', function(e) {
                        e.preventDefault();
                        showWarning('Teks soal tidak dapat disalin');
                        return false;
                    });

                    questionText.addEventListener('cut', function(e) {
                        e.preventDefault();
                        showWarning('Teks soal tidak dapat dipotong');
                        return false;
                    });

                    questionText.addEventListener('contextmenu', function(e) {
                        e.preventDefault();
                        showWarning('Menu konteks tidak tersedia untuk teks ini');
                        return false;
                    });

                    questionText.addEventListener('selectstart', function(e) {
                        e.preventDefault();
                        return false;
                    });
                }

                // 4. Proteksi teks pilihan jawaban (TANPA mengganggu radio button)
                function protectAnswerText() {
                    const answerChoices = document.querySelectorAll('.quiz-choice .choice-text');

                    answerChoices.forEach((choiceText, index) => {
                        choiceText.classList.add('protected-text', 'no-select', 'answer-text');

                        const originalHtml = choiceText.innerHTML;
                        choiceText.innerHTML = `
                    <div class="protected-container">
                        <div class="text-protector"></div>
                        <div class="answer-content">${originalHtml}</div>
                    </div>
                `;

                        choiceText.addEventListener('copy', function(e) {
                            e.preventDefault();
                            showWarning('Teks jawaban tidak dapat disalin');
                            return false;
                        });

                        choiceText.addEventListener('cut', function(e) {
                            e.preventDefault();
                            showWarning('Teks jawaban tidak dapat dipotong');
                            return false;
                        });

                        choiceText.addEventListener('contextmenu', function(e) {
                            e.preventDefault();
                            showWarning('Menu konteks tidak tersedia untuk teks ini');
                            return false;
                        });

                        choiceText.addEventListener('selectstart', function(e) {
                            e.preventDefault();
                            return false;
                        });

                        const radioButton = choiceText.closest('.quiz-choice').querySelector(
                            'input[type="radio"]');
                        if (radioButton) {
                            choiceText.addEventListener('click', function(e) {
                                if (e.target.closest('.answer-content') || e.target.closest(
                                        '.choice-text')) {
                                    radioButton.click();
                                    radioButton.focus();
                                }
                            });
                        }
                    });
                }

                // 5. Fungsi untuk mengizinkan interaksi pada elemen penting
                function allowInteractiveElements() {
                    const interactiveElements = [
                        '.btn-jawab',
                        '.form-check-input',
                        '.radio-inside',
                        'input[type="radio"]',
                        'input[type="submit"]',
                        'button'
                    ];

                    interactiveElements.forEach(selector => {
                        document.querySelectorAll(selector).forEach(el => {
                            el.style.pointerEvents = 'auto';
                            el.style.cursor = 'pointer';

                            el.addEventListener('contextmenu', function(e) {
                                return true;
                            });
                        });
                    });
                }

                // 6. Blokir keyboard shortcuts hanya untuk elemen yang dilindungi
                function setupKeyboardProtection() {
                    document.addEventListener('keydown', function(e) {
                        const target = e.target;

                        const isProtectedText = target.closest('.protected-text') ||
                            target.closest('.case-content') ||
                            target.closest('.soal-content') ||
                            target.closest('.answer-text');

                        if (isProtectedText) {
                            if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                                e.preventDefault();
                                showWarning('Salin teks tidak diizinkan');
                                return false;
                            }

                            if ((e.ctrlKey || e.metaKey) && e.key === 'x') {
                                e.preventDefault();
                                showWarning('Potong teks tidak diizinkan');
                                return false;
                            }

                            if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                                e.preventDefault();
                                showWarning('Seleksi semua tidak diizinkan');
                                return false;
                            }
                        }

                        if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
                            const isProtectedTextForPaste = target.closest('.protected-text');
                            if (isProtectedTextForPaste) {
                                e.preventDefault();
                                showWarning('Tempel teks tidak diizinkan di sini');
                                return false;
                            }
                        }

                        if (e.key === 'F12') {
                            e.preventDefault();
                            showWarning('Developer tools tidak dapat diakses');
                            return false;
                        }

                        if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key)) {
                            e.preventDefault();
                            showWarning('Developer tools tidak dapat diakses');
                            return false;
                        }
                    });
                }

                // 7. Blokir drag teks dari elemen yang dilindungi
                function setupDragProtection() {
                    document.addEventListener('dragstart', function(e) {
                        const target = e.target;
                        const isProtectedText = target.closest('.protected-text') ||
                            target.closest('.case-content') ||
                            target.closest('.soal-content') ||
                            target.closest('.answer-text');

                        if (isProtectedText) {
                            e.preventDefault();
                            return false;
                        }
                    });
                }

                // 8. Mencegah seleksi teks dengan mouse pada elemen yang dilindungi
                function setupSelectionProtection() {
                    document.addEventListener('selectstart', function(e) {
                        const target = e.target;
                        const isProtectedText = target.closest('.protected-text') ||
                            target.closest('.case-content') ||
                            target.closest('.soal-content') ||
                            target.closest('.answer-text');

                        if (isProtectedText) {
                            e.preventDefault();
                            return false;
                        }
                    });
                }

                // 9. Tambahkan CSS inline untuk mencegah seleksi
                function addInlineProtectionStyles() {
                    const style = document.createElement('style');
                    style.textContent = `
                .protected-text * {
                    -webkit-user-select: none !important;
                    -moz-user-select: none !important;
                    -ms-user-select: none !important;
                    user-select: none !important;
                }

                input, button, .quiz-choice {
                    user-select: none !important;
                }

                .protected-text::selection {
                    background: transparent !important;
                    color: inherit !important;
                }

                .protected-text::-moz-selection {
                    background: transparent !important;
                    color: inherit !important;
                }
            `;
                    document.head.appendChild(style);
                }

                // 10. Inisialisasi semua proteksi
                function initializeProtection() {
                    console.log('Mengaktifkan proteksi copy-paste...');

                    addInlineProtectionStyles();

                    protectCaseText();
                    protectQuestionText();
                    protectAnswerText();

                    allowInteractiveElements();

                    setupKeyboardProtection();
                    setupDragProtection();
                    setupSelectionProtection();

                    console.log('Proteksi copy-paste aktif. Radio button dan tombol tetap bisa diklik.');
                }

                setTimeout(initializeProtection, 500);

                document.querySelector('form')?.addEventListener('submit', function(e) {
                    console.log('Form akan disubmit...');
                });

                document.querySelectorAll('.quiz-choice').forEach(choice => {
                    choice.addEventListener('click', function(e) {
                        const radio = this.querySelector('input[type="radio"]');
                        if (radio && !radio.checked) {
                            radio.checked = true;
                            radio.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        }
                    });

                    const choiceText = choice.querySelector('.choice-text');
                    if (choiceText) {
                        choiceText.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    }
                });
            });

            window.addEventListener('load', function() {
                setInterval(function() {
                    const caseText = document.querySelector('.case-content');
                    if (caseText && window.getSelection().toString().includes(caseText.textContent)) {
                        window.getSelection().removeAllRanges();

                        const warning = document.createElement('div');
                        warning.style.position = 'fixed';
                        warning.style.top = '10px';
                        warning.style.right = '10px';
                        warning.style.background = '#ff4444';
                        warning.style.color = 'white';
                        warning.style.padding = '10px';
                        warning.style.borderRadius = '5px';
                        warning.style.zIndex = '10000';
                        warning.textContent = 'Teks tidak dapat disalin';
                        document.body.appendChild(warning);

                        setTimeout(() => warning.remove(), 2000);
                    }
                }, 1000);
            });

            document.addEventListener('change', function(e) {
                if (e.target.type === 'radio') {
                    console.log('Radio button dipilih:', e.target.value);

                    const bobot = e.target.getAttribute('data-bobot');
                    const bobotInput = document.getElementById('bobot');
                    if (bobotInput && bobot) {
                        bobotInput.value = bobot;
                    }
                }
            });
        </script>

        {{-- Script untuk mencegah back button --}}
        <script>
            // ============================================
            // MENCEGAH NAVIGASI BACK BROWSER DENGAN LOGIKA LANJUTKAN YANG TEPAT
            // ============================================

            // 1. Simpan state bahwa soal ini sudah dikunjungi
            if (!sessionStorage.getItem('quiz2_visited_' + {{ $soal->soal_id ?? 0 }})) {
                sessionStorage.setItem('quiz2_visited_' + {{ $soal->soal_id ?? 0 }}, 'true');
            }

            // 2. Flag untuk menandai apakah halaman ini sedang di-refresh
            let isPageRefreshing = false;

            // 3. Deteksi refresh halaman
            window.addEventListener('beforeunload', function() {
                isPageRefreshing = true;
                sessionStorage.setItem('quiz2_refresh_timestamp', Date.now());
            });

            // 4. Redirect jika mencoba back ke soal yang sudah dikunjungi
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    const soalId = {{ $soal->soal_id ?? 0 }};
                    const visitedKey = 'quiz2_visited_' + soalId;

                    if (sessionStorage.getItem(visitedKey) === 'true') {
                        showBackButtonWarning();
                    }
                }
            });

            // 5. Tampilkan warning modal (REDESIGN + BUTTON COLOR FROM :root)
            function showBackButtonWarning() {
                const oldModal = document.getElementById('backWarningModal');
                if (oldModal) oldModal.remove();

                const modalHtml = `
            <div class="modal fade" id="backWarningModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="backWarningModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header">
                            <h5 class="modal-title" id="backWarningModalLabel">
                                <i class="ri-alert-line me-2"></i> Soal Sudah Dikerjakan
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <div class="mm-hero-icon">
                                    <i class="ri-error-warning-line" style="font-size:28px;"></i>
                                </div>
                            </div>
                            <h5 class="text-center mb-3">Anda Sudah Menjawab Soal Ini!</h5>
                            <p class="text-muted text-center mb-4">
                                Soal ini sudah pernah Anda kerjakan dan tidak dapat diulang.<br>
                                Anda akan dialihkan ke posisi lanjutan soal Anda.
                            </p>
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="ri-information-line me-2"></i>
                                    <div>
                                        <small><strong>Info:</strong> Sistem akan menempatkan Anda pada soal yang belum dikerjakan atau ke posisi terakhir soal Anda.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-primary btn-lg" onclick="continueToLastPosition()">
                                <i class="ri-arrow-right-line me-2"></i> Lanjutkan Soal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

                document.body.insertAdjacentHTML('beforeend', modalHtml);

                const modalElement = document.getElementById('backWarningModal');
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });

                modal.show();

                modalElement.addEventListener('click', function(e) {
                    if (e.target === modalElement) {
                        e.preventDefault();
                        e.stopPropagation();
                        modalElement.querySelector('.modal-content').classList.add('shake-modal');
                        setTimeout(() => {
                            modalElement.querySelector('.modal-content').classList.remove('shake-modal');
                        }, 500);
                    }
                });
            }

            // 6. Fungsi untuk lanjutkan ke posisi terakhir
            function continueToLastPosition() {
                const modalElement = document.getElementById('backWarningModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    modalElement.remove();
                }

                Swal.fire({
                    title: 'Mengarahkan ke posisi lanjutan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    backdrop: true,
                    willOpen: () => Swal.showLoading()
                });

                window.location.href =
                    `{{ route('ptk.continue-quiz', [
                        'encode_kegiatan_id' => $encoded_kegiatan_id,
                        'nip' => $nip,
                    ]) }}`;
            }

            // 7. Blokir escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && document.getElementById('backWarningModal')) {
                    e.preventDefault();
                    e.stopPropagation();

                    const modalContent = document.querySelector('#backWarningModal .modal-content');
                    if (modalContent) {
                        modalContent.classList.add('shake-modal');
                        setTimeout(() => modalContent.classList.remove('shake-modal'), 500);
                    }
                }
            });

            // 8. Replace state history untuk mencegah back
            history.replaceState(null, null, window.location.href);

            // 9. Tangkap event popstate (back button)
            window.addEventListener('popstate', function(event) {
                history.replaceState(null, null, window.location.href);
                isPageRefreshing = false;

                const soalId = {{ $soal->soal_id ?? 0 }};
                const visitedKey = 'quiz2_visited_' + soalId;

                if (sessionStorage.getItem(visitedKey) === 'true') {
                    setTimeout(() => {
                        if (!isPageRefreshing) {
                            showBackButtonWarning();
                        }
                    }, 100);
                }
            });

            // 10. Deteksi navigasi back lainnya
            window.addEventListener('beforeunload', function(e) {
                if (!isPageRefreshing) {
                    const soalId = {{ $soal->soal_id ?? 0 }};
                    const visitedKey = 'quiz2_visited_' + soalId;

                    if (sessionStorage.getItem(visitedKey) === 'true') {
                        if (!document.getElementById('backWarningModal')) {
                            e.preventDefault();
                            e.returnValue = 'Anda sudah mengerjakan soal ini. Ingin melanjutkan ke soal berikutnya?';

                            setTimeout(() => {
                                showBackButtonWarning();
                            }, 100);
                        }
                    }
                }
            });

            // 11. Cegah form submit ganda
            let isSubmitting = false;
            document.querySelector('form')?.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i> Mengirim...';
                }

                isSubmitting = true;

                setTimeout(() => {
                    isSubmitting = false;
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="ri-checkbox-circle-line me-2"></i> Kirim Jawaban';
                    }
                }, 5000);
            });

            // 12. Simpan pilihan jawaban sementara
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                if (form) {
                    const radioButtons = form.querySelectorAll('input[type="radio"]');

                    radioButtons.forEach(radio => {
                        radio.addEventListener('change', function() {
                            sessionStorage.setItem('selected_answer_' + {{ $soal->soal_id ?? 0 }}, this
                                .value);
                            sessionStorage.setItem('selected_bobot_' + {{ $soal->soal_id ?? 0 }}, this
                                .dataset.bobot);
                        });
                    });

                    const savedAnswer = sessionStorage.getItem('selected_answer_' + {{ $soal->soal_id ?? 0 }});
                    const savedBobot = sessionStorage.getItem('selected_bobot_' + {{ $soal->soal_id ?? 0 }});

                    if (savedAnswer) {
                        const radioToCheck = form.querySelector(`input[value="${savedAnswer}"]`);
                        if (radioToCheck) {
                            radioToCheck.checked = true;
                            document.getElementById('bobot').value = savedBobot || radioToCheck.dataset.bobot;
                        }
                    }
                }

                const refreshTimestamp = sessionStorage.getItem('quiz2_refresh_timestamp');
                if (refreshTimestamp) {
                    const timeDiff = Date.now() - parseInt(refreshTimestamp);
                    if (timeDiff < 2000) {
                        console.log("🔄 Refresh terdeteksi, tidak menampilkan modal back warning");
                        sessionStorage.removeItem('quiz2_refresh_timestamp');
                        return;
                    }
                }

                const performanceEntries = performance.getEntriesByType("navigation");
                isPageRefreshing = false;

                if (performanceEntries.length > 0 && performanceEntries[0].type === "back_forward") {
                    const soalId = {{ $soal->soal_id ?? 0 }};
                    const visitedKey = 'quiz2_visited_' + soalId;

                    if (sessionStorage.getItem(visitedKey) === 'true' && !isPageRefreshing) {
                        setTimeout(() => {
                            showBackButtonWarning();
                        }, 500);
                    }
                }
            });

            // 14. Reset flag refresh saat halaman selesai dimuat
            window.addEventListener('load', function() {
                setTimeout(() => {
                    isPageRefreshing = false;
                }, 1000);
            });
        </script>

        {{-- SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <script>
            // Fungsi untuk menampilkan SweetAlert
            function showAlert(type, title, message) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: type,
                    title: title,
                    text: message
                });
            }

            @if (session('warning'))
                showAlert('warning', 'Peringatan', '{{ session('warning') }}');
            @endif

            @if (session('info'))
                showAlert('info', 'Informasi', '{{ session('info') }}');
            @endif

            @if (session('success'))
                showAlert('success', 'Berhasil', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', 'Error', '{{ session('error') }}');
            @endif
        </script>
    @endsection
