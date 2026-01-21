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

    foreach($caseList as $index => $caseItem) {
    if($caseItem['is_passed']) $passedCases++;
    if($caseItem['is_current']) $currentNumber = $index + 1;
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

    <!-- CASE LIST -->
    <div id="caseListContainer">
        @foreach($caseList as $caseItem)
        <div class="nav-case-item 
            @if($caseItem['is_current']) active-case @endif
            @if($caseItem['is_passed']) passed-case @else not-passed-case @endif"
            data-case-id="{{ $caseItem['soal_case_id'] }}"
            data-sub-id="{{ $caseItem['sub_indikator_id'] }}"
            title="Sub Indikator: {{ $caseItem['sub_indikator_id'] }} | Urut: {{ $caseItem['no_urut'] }}">

            <!-- NUMBER BADGE -->
            <div class="d-flex align-items-start">
                <div class="case-number me-2">
                    <span class="badge 
                        @if($caseItem['is_current']) bg-primary
                        @elseif($caseItem['is_passed']) bg-success
                        @else bg-secondary @endif">
                        {{ $loop->iteration }}
                    </span>
                </div>

                <!-- CASE INFO -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="case-title" style="font-weight: {{ $caseItem['is_current'] ? '700' : '500' }}">
                                {{ $caseItem['title'] }}
                            </span>
                            @if($caseItem['is_current'])
                            <span class="badge bg-warning text-dark badge-sm ms-2">
                                <i class="ri-play-circle-line me-1"></i>Sekarang
                            </span>
                            @endif
                        </div>

                        <!-- STATUS ICON -->
                        @if($caseItem['is_passed'])
                        <i class="ri-checkbox-circle-fill text-success fs-5" title="Sudah melewati"></i>
                        @else
                        <i class="ri-checkbox-blank-circle-line text-secondary fs-5" title="Belum melewati"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach


        @if(count($caseList) === 0)
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
                            $kegiatan = \App\Models\Kegiatan::find(Hashids::decode($encoded_kegiatan_id)[0] ?? 0);
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
                                            // Format seperti di floating panel
                                            $judul = "Studi Kasus " . ($case->no_urut ?? '1');


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
                                        <input type="hidden" name="sub_indikator_id" value="{{ $sub_indikator_id }}"> <!-- ID ASLI -->
                                        <input type="hidden" name="encoded_kegiatan_id" value="{{ $encoded_kegiatan_id }}">
                                        <input type="hidden" name="encoded_sub_indikator_id" value="{{ $encoded_sub_indikator_id }}">
                                        <input type="hidden" name="encoded_no_urut" value="{{ $encoded_no_urut }}">
                                        <input type="hidden" name="nip" value="{{ $nip }}">
                                        <input type="text" name="bobot" id="bobot">
                                       <!-- Menjadi ini -->
<input type="hidden" name="remaining_seconds" id="remainingSecondsInput" value="{{ $remaining_seconds ?? 7200 }}">
<input type="hidden" name="frontend_time_string" id="frontendTimeString">


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

        /* ======================================== */
        /* DESKTOP HALF OVAL BUTTON */
        /* ======================================== */
        #floatingNav {
            position: fixed;
            top: 142px;
            /* sejajar dengan teks QUIZ */
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
                /* muncul dari sisi kiri */
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
            /* turun 10px dari posisi sebelumnya */
            background: transparent;
            border: none;
            font-size: 20px;
            color: #1a4d8e;
            cursor: pointer;
        }

        .nav-case-item {
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
            /* DITARIK KE KIRI BIAR GA NEMPEL CARD */
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

        /* ... existing styles ... */

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
            /* Safari */
            -moz-user-select: none;
            /* Firefox */
            -ms-user-select: none;
            /* IE10+/Edge */
            user-select: none;
            /* Standard */
        }

        /* Style hanya untuk teks yang dilindungi, bukan elemen interaktif */
        .protected-text {
            cursor: default;
            position: relative;
        }

        /* JANGAN gunakan overlay untuk elemen interaktif */
        .protected-text::after {
            display: none;
            /* Nonaktifkan overlay */
        }

        /* Style untuk konten yang dilindungi */
        .case-content,
        .soal-content,
        .answer-text {
            position: relative;
        }

        /* Pastikan elemen interaktif tetap bisa diklik */
        .quiz-choice,
        .btn-jawab,
        .form-check-input,
        .radio-inside,
        button,
        input[type="radio"],
        input[type="submit"] {
            cursor: pointer !important;
            user-select: none !important;
            /* Boleh user-select none tapi tetap bisa diklik */
        }

        /* Overlay hanya untuk teks, bukan untuk container penuh */
        .text-protector {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            pointer-events: none;
            /* Tidak mengganggu klik elemen di bawahnya */
        }

        /* Container teks yang dilindungi */
        .protected-container {
            position: relative;
        }

        /* Pesan peringatan */
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

        // Navigasi untuk studi kasus yang sudah passed


        item.addEventListener('click', function(e) {
            // Jangan trigger jika klik icon
            if (e.target.tagName === 'I' || e.target.classList.contains('badge')) {
                return;
            }

            const subId = this.dataset.subId;
            const caseId = this.dataset.caseId;

            // Hanya navigasi jika bukan case yang sedang aktif
            if (!this.classList.contains('active-case')) {
                if (confirm('Pindah ke studi kasus ini?')) {
                    // Encode sub_id dan redirect ke soal pertama dari sub_indikator ini
                    const encodedSubId = btoa(subId);
                    const encodedNoUrut = btoa(1);

                    window.location.href = `{{ route('quiz2.show', [
                            'tahap' => $tahap,
                            'encoded_kegiatan_id' => $encoded_kegiatan_id,
                            'nip' => $nip,
                            'encoded_sub_indikator_id' => 'SUB_ID_PLACEHOLDER',
                            'encoded_no_urut' => 'NO_URUT_PLACEHOLDER'
                        ]) }}`
                        .replace('SUB_ID_PLACEHOLDER', encodedSubId)
                        .replace('NO_URUT_PLACEHOLDER', encodedNoUrut);
                }
            }
        });

        // Efek hover
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
    
    // Hitung waktu yang telah berlalu sejak penyimpanan terakhir
    function calculateElapsedTimeSinceLastSave() {
        const lastSave = localStorage.getItem("quiz2_last_update");
        if (!lastSave) return 0;
        
        const now = Date.now();
        const elapsed = Math.floor((now - parseInt(lastSave)) / 1000);
        return Math.max(0, elapsed);
    }
    
    // Load waktu dari localStorage dengan memperhitungkan waktu yang telah berlalu
    function loadAndContinueTime() {
        @if(!($reset_localstorage ?? false))
            const savedRemaining = localStorage.getItem("quiz2_remaining_seconds");
            const lastUpdate = localStorage.getItem("quiz2_last_update");
            const savedDisplay = localStorage.getItem("quiz2_display_time");
            
            if (savedRemaining && lastUpdate) {
                const now = Date.now();
                const elapsedSeconds = Math.floor((now - parseInt(lastUpdate)) / 1000);
                const savedSeconds = parseInt(savedRemaining);
                
                // Kurangi waktu yang telah berlalu sejak penyimpanan terakhir
                const continuedSeconds = Math.max(0, savedSeconds - elapsedSeconds);
                
                // Gunakan yang TERKECIL antara waktu lanjutan dan waktu dari database
                remainingSeconds = Math.min(continuedSeconds, remainingSeconds);
                
                // Update waktu terakhir
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
                window.location.href = "{{ route('quiz.finish', [
                    'encoded_kegiatan_id' => $encoded_kegiatan_id,
                    'nip' => $nip
                ]) }}";
            }, 1000);
            
            return;
        }
        
        // Kurangi 1 detik
        remainingSeconds--;
        
        // Update tampilan dan input hidden
        updateTimerDisplay();
        
        // Simpan ke localStorage setiap 5 detik
        if (Date.now() - lastUpdateTime > 5000) {
            saveTimeToLocalStorage();
            lastUpdateTime = Date.now();
        }
    }
    
    // Initialize timer dengan benar
    function initializeTimer() {
        if (isInitialized) return;
        
        // 1. Load dan lanjutkan waktu dari localStorage
        loadAndContinueTime();
        
        // 2. Reset localStorage jika ada flag (untuk lanjutkan quiz)
        @if($reset_localstorage ?? false)
            localStorage.removeItem("quiz2_remaining_seconds");
            localStorage.removeItem("quiz2_last_update");
            localStorage.removeItem("quiz2_display_time");
            console.log("🔄 Timer localStorage direset untuk lanjutkan quiz");
        @endif
        
        // 3. Tampilkan waktu awal dan update input
        updateTimerDisplay();
        
        // 4. Simpan waktu awal ke localStorage jika belum ada
        if (!localStorage.getItem("quiz2_remaining_seconds")) {
            saveTimeToLocalStorage();
        }
        
        // 5. Start timer interval
        setInterval(updateTimer, 1000);
        
        isInitialized = true;
        
        console.log("🎬 Timer diinisialisasi:", {
            seconds: remainingSeconds,
            display: formatTime(remainingSeconds),
            databaseTime: "{{ $remaining_time_formatted ?? '02:00:00' }}"
        });
    }
    
    // Event listeners
    window.addEventListener('load', function() {
        initializeTimer();
    });
    
    // Simpan waktu sebelum unload/tutup halaman
    window.addEventListener('beforeunload', function() {
        saveTimeToLocalStorage();
        console.log("💾 Timer disimpan sebelum unload:", formatTime(remainingSeconds));
    });
    
    // Pastikan input hidden selalu ter-update saat form di-submit
    document.querySelector('form').addEventListener('submit', function(e) {
        // Update input hidden terakhir kali sebelum submit
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
                // Hapus pesan lama
                const oldWarning = document.querySelector('.copy-warning');
                if (oldWarning) oldWarning.remove();

                // Buat pesan baru
                const warning = document.createElement('div');
                warning.className = 'copy-warning';
                warning.innerHTML = `
            <i class="ri-error-warning-line me-2"></i>
            <span>${message}</span>
        `;
                warning.style.backgroundColor = type === 'warning' ? '#dc3545' : '#1a4d8e';

                document.body.appendChild(warning);

                // Tampilkan
                warning.style.display = 'block';

                // Sembunyikan setelah 3 detik
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

                // Tambahkan proteksi hanya pada teks
                caseText.classList.add('protected-text', 'no-select');

                // Tambahkan container untuk teks
                const originalHtml = caseText.innerHTML;
                caseText.innerHTML = `
            <div class="protected-container">
                <div class="text-protector"></div>
                <div class="case-content">${originalHtml}</div>
            </div>
        `;

                // Blokir copy pada teks ini
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

                // Blokir seleksi teks
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

                // Tambahkan proteksi hanya pada teks
                questionText.classList.add('protected-text', 'no-select');

                // Tambahkan container untuk teks
                const originalHtml = questionText.innerHTML;
                questionText.innerHTML = `
            <div class="protected-container">
                <div class="text-protector"></div>
                <div class="soal-content">${originalHtml}</div>
            </div>
        `;

                // Blokir copy pada teks ini
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

                // Blokir seleksi teks
                questionText.addEventListener('selectstart', function(e) {
                    e.preventDefault();
                    return false;
                });
            }

            // 4. Proteksi teks pilihan jawaban (TANPA mengganggu radio button)
            function protectAnswerText() {
                const answerChoices = document.querySelectorAll('.quiz-choice .choice-text');

                answerChoices.forEach((choiceText, index) => {
                    // Tambahkan proteksi hanya pada teks
                    choiceText.classList.add('protected-text', 'no-select', 'answer-text');

                    // Tambahkan container untuk teks
                    const originalHtml = choiceText.innerHTML;
                    choiceText.innerHTML = `
                <div class="protected-container">
                    <div class="text-protector"></div>
                    <div class="answer-content">${originalHtml}</div>
                </div>
            `;

                    // Blokir copy pada teks ini
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

                    // Blokir seleksi teks
                    choiceText.addEventListener('selectstart', function(e) {
                        e.preventDefault();
                        return false;
                    });

                    // Pastikan radio button masih bisa diklik
                    const radioButton = choiceText.closest('.quiz-choice').querySelector('input[type="radio"]');
                    if (radioButton) {
                        // Tambahkan event listener untuk memastikan radio bisa diklik
                        choiceText.addEventListener('click', function(e) {
                            // Jika yang diklik adalah teks, trigger klik pada radio button
                            if (e.target.closest('.answer-content') || e.target.closest('.choice-text')) {
                                radioButton.click();
                                radioButton.focus();
                            }
                        });
                    }
                });
            }

            // 5. Fungsi untuk mengizinkan interaksi pada elemen penting
            function allowInteractiveElements() {
                // Pastikan semua elemen interaktif bisa diklik
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
                        // Pastikan pointer events diizinkan
                        el.style.pointerEvents = 'auto';
                        el.style.cursor = 'pointer';

                        // Izinkan klik kanan pada elemen interaktif (kecuali untuk copy)
                        el.addEventListener('contextmenu', function(e) {
                            // Izinkan menu konteks default kecuali jika mencoba copy teks
                            return true;
                        });
                    });
                });
            }

            // 6. Blokir keyboard shortcuts hanya untuk elemen yang dilindungi
            function setupKeyboardProtection() {
                document.addEventListener('keydown', function(e) {
                    const target = e.target;

                    // Cek jika target berada dalam elemen yang dilindungi
                    const isProtectedText = target.closest('.protected-text') ||
                        target.closest('.case-content') ||
                        target.closest('.soal-content') ||
                        target.closest('.answer-text');

                    // Hanya blokir jika berada dalam teks yang dilindungi
                    if (isProtectedText) {
                        // Ctrl+C atau Cmd+C
                        if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                            e.preventDefault();
                            showWarning('Salin teks tidak diizinkan');
                            return false;
                        }

                        // Ctrl+X atau Cmd+X
                        if ((e.ctrlKey || e.metaKey) && e.key === 'x') {
                            e.preventDefault();
                            showWarning('Potong teks tidak diizinkan');
                            return false;
                        }

                        // Ctrl+A (Select All) dalam teks yang dilindungi
                        if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                            e.preventDefault();
                            showWarning('Seleksi semua tidak diizinkan');
                            return false;
                        }
                    }

                    // Ctrl+V (paste) - izinkan di mana saja kecuali di teks yang dilindungi
                    if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
                        const isProtectedTextForPaste = target.closest('.protected-text');
                        if (isProtectedTextForPaste) {
                            e.preventDefault();
                            showWarning('Tempel teks tidak diizinkan di sini');
                            return false;
                        }
                    }

                    // F12 - blokir di mana saja
                    if (e.key === 'F12') {
                        e.preventDefault();
                        showWarning('Developer tools tidak dapat diakses');
                        return false;
                    }

                    // Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
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
            /* Mencegah seleksi pada teks yang dilindungi */
            .protected-text * {
                -webkit-user-select: none !important;
                -moz-user-select: none !important;
                -ms-user-select: none !important;
                user-select: none !important;
            }
            
            /* Izinkan seleksi pada elemen interaktif jika diperlukan */
            input, button, .quiz-choice {
                user-select: none !important;
            }
            
            /* Efek visual saat mencoba seleksi */
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

                // Tambahkan style inline
                addInlineProtectionStyles();

                // Proteksi konten teks
                protectCaseText();
                protectQuestionText();
                protectAnswerText();

                // Izinkan elemen interaktif
                allowInteractiveElements();

                // Setup proteksi lainnya
                setupKeyboardProtection();
                setupDragProtection();
                setupSelectionProtection();

                console.log('Proteksi copy-paste aktif. Radio button dan tombol tetap bisa diklik.');
            }

            // 11. Jalankan inisialisasi dengan delay kecil
            setTimeout(initializeProtection, 500);

            // 12. Pastikan event submit form tetap bekerja
            document.querySelector('form')?.addEventListener('submit', function(e) {
                // Tidak ada blokir di sini, biarkan form submit normal
                console.log('Form akan disubmit...');
            });

            // 13. Handle klik pada pilihan jawaban - versi lebih sederhana
            document.querySelectorAll('.quiz-choice').forEach(choice => {
                choice.addEventListener('click', function(e) {
                    // Cari radio button di dalam choice ini
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio && !radio.checked) {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                });

                // Pastikan klik pada teks tidak menghalangi radio button
                const choiceText = choice.querySelector('.choice-text');
                if (choiceText) {
                    choiceText.addEventListener('click', function(e) {
                        e.stopPropagation(); // Jangan biarkan event bubble ke choice
                    });
                }
            });
        });

        // 14. Backup protection: Cek jika user mencoba bypass
        window.addEventListener('load', function() {
            // Deteksi jika user mencoba inspect element
            setInterval(function() {
                // Cek jika elemen proteksi dihapus
                const caseText = document.querySelector('.case-content');
                if (caseText && window.getSelection().toString().includes(caseText.textContent)) {
                    // Clear selection
                    window.getSelection().removeAllRanges();

                    // Tampilkan warning
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

        // 15. Pastikan radio button tetap berfungsi dengan baik
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio') {
                console.log('Radio button dipilih:', e.target.value);

                // Update bobot hidden input
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
        // Simpan status refresh ke sessionStorage dengan timestamp
        sessionStorage.setItem('quiz2_refresh_timestamp', Date.now());
    });

    // 4. Redirect jika mencoba back ke soal yang sudah dikunjungi
    window.addEventListener('pageshow', function(event) {
        // Cek jika halaman dimuat dari cache (back/forward)
        if (event.persisted) {
            // Cek apakah soal ini sudah dijawab
            const soalId = {{ $soal->soal_id ?? 0 }};
            const visitedKey = 'quiz2_visited_' + soalId;
            
            if (sessionStorage.getItem(visitedKey) === 'true') {
                // Tampilkan modal peringatan yang tidak bisa ditutup
                showBackButtonWarning();
            }
        }
    });

    // 5. Tampilkan warning modal dengan opsi lanjutkan (TIDAK BISA DITUTUP)
    function showBackButtonWarning() {
        // Hapus modal lama jika ada
        const oldModal = document.getElementById('backWarningModal');
        if (oldModal) {
            oldModal.remove();
        }

        // Buat modal peringatan yang TIDAK BISA DITUTUP
        const modalHtml = `
            <div class="modal fade" id="backWarningModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="backWarningModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title" id="backWarningModalLabel">
                                <i class="ri-alert-line me-2"></i> Soal Sudah Dikerjakan
                            </h5>
                            <!-- HAPUS TOMBOL CLOSE -->
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <i class="ri-error-warning-line" style="font-size: 48px; color: #ffc107;"></i>
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
                            <!-- Hanya tombol Lanjutkan Soal yang tersedia -->
                            <button type="button" class="btn btn-primary btn-lg" onclick="continueToLastPosition()">
                                <i class="ri-arrow-right-line me-2"></i> Lanjutkan Soal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Tambahkan modal ke body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Tampilkan modal dan nonaktifkan interaksi dengan latar belakang
        const modalElement = document.getElementById('backWarningModal');
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static', // Tidak bisa klik di luar modal
            keyboard: false      // Tidak bisa tutup dengan ESC
        });
        
        // Tampilkan modal
        modal.show();
        
        // Nonaktifkan semua klik di luar modal
        modalElement.addEventListener('click', function(e) {
            // Jika yang diklik adalah backdrop (background gelap)
            if (e.target === modalElement) {
                e.preventDefault();
                e.stopPropagation();
                // Berikan efek getar sebagai feedback
                modalElement.querySelector('.modal-content').classList.add('shake-modal');
                setTimeout(() => {
                    modalElement.querySelector('.modal-content').classList.remove('shake-modal');
                }, 500);
            }
        });
    }

    // 6. Fungsi untuk lanjutkan ke posisi terakhir (MENGGUNAKAN ROUTE continue-quiz)
    function continueToLastPosition() {
        // Hapus modal
        const modalElement = document.getElementById('backWarningModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            modalElement.remove();
        }

        const encodedKegiatanId = '{{ $encoded_kegiatan_id }}';
        const nip = '{{ $nip }}';
        
        // Tampilkan loading
        Swal.fire({
            title: 'Mengarahkan ke posisi lanjutan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            backdrop: true,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Langsung redirect ke route continue-quiz yang sudah ada di PtkController
        window.location.href = `{{ route('ptk.continue-quiz', [
            'encode_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip
        ]) }}`;
    }

    // 7. Blokir escape key untuk mencegah penutupan modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('backWarningModal')) {
            e.preventDefault();
            e.stopPropagation();
            
            // Berikan efek getar
            const modalContent = document.querySelector('#backWarningModal .modal-content');
            if (modalContent) {
                modalContent.classList.add('shake-modal');
                setTimeout(() => {
                    modalContent.classList.remove('shake-modal');
                }, 500);
            }
        }
    });

    // 8. Replace state history untuk mencegah back
    history.replaceState(null, null, window.location.href);
    
    // 9. Tangkap event popstate (back button)
    window.addEventListener('popstate', function(event) {
        history.replaceState(null, null, window.location.href);
        
        // Reset flag refresh saat back button ditekan
        isPageRefreshing = false;
        
        // Cek apakah soal ini sudah dikunjungi
        const soalId = {{ $soal->soal_id ?? 0 }};
        const visitedKey = 'quiz2_visited_' + soalId;
        
        if (sessionStorage.getItem(visitedKey) === 'true') {
            // Tunggu sebentar untuk memastikan bukan refresh
            setTimeout(() => {
                // Cek apakah benar-benar back button (bukan refresh)
                if (!isPageRefreshing) {
                    showBackButtonWarning();
                }
            }, 100);
        }
    });

    // 10. Deteksi navigasi back lainnya
    window.addEventListener('beforeunload', function(e) {
        // Jika user mencoba meninggalkan halaman dengan cara selain refresh
        if (!isPageRefreshing) {
            const soalId = {{ $soal->soal_id ?? 0 }};
            const visitedKey = 'quiz2_visited_' + soalId;
            
            // Jika user mencoba meninggalkan halaman setelah mengunjungi soal ini
            if (sessionStorage.getItem(visitedKey) === 'true') {
                // Hanya tampilkan peringatan jika modal belum ditampilkan
                if (!document.getElementById('backWarningModal')) {
                    e.preventDefault();
                    e.returnValue = 'Anda sudah mengerjakan soal ini. Ingin melanjutkan ke soal berikutnya?';
                    
                    // Tampilkan modal warning
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
        
        // Nonaktifkan tombol submit
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i> Mengirim...';
        }
        
        isSubmitting = true;
        
        // Set timeout untuk mengaktifkan kembali jika ada error
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
                    // Simpan pilihan ke sessionStorage
                    sessionStorage.setItem('selected_answer_' + {{ $soal->soal_id ?? 0 }}, this.value);
                    sessionStorage.setItem('selected_bobot_' + {{ $soal->soal_id ?? 0 }}, this.dataset.bobot);
                });
            });
            
            // Restore pilihan jika ada
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
        
        // 13. Cek jika user refresh halaman - TIDAK TAMPILKAN MODAL
        // (diperbaiki: refresh tidak menampilkan modal)
        const performanceEntries = performance.getEntriesByType("navigation");
        
        // Reset flag refresh saat halaman dimuat
        isPageRefreshing = false;
        
        // Cek timestamp dari sessionStorage untuk deteksi refresh
        const refreshTimestamp = sessionStorage.getItem('quiz2_refresh_timestamp');
        if (refreshTimestamp) {
            const timeDiff = Date.now() - parseInt(refreshTimestamp);
            // Jika refresh terjadi dalam 2 detik terakhir
            if (timeDiff < 2000) {
                // Ini adalah refresh, tidak tampilkan modal
                console.log("🔄 Refresh terdeteksi, tidak menampilkan modal back warning");
                // Hapus timestamp
                sessionStorage.removeItem('quiz2_refresh_timestamp');
                return;
            }
        }
        
        // Jika bukan refresh dan ada navigation type "back_forward"
        if (performanceEntries.length > 0 && performanceEntries[0].type === "back_forward") {
            const soalId = {{ $soal->soal_id ?? 0 }};
            const visitedKey = 'quiz2_visited_' + soalId;
            
            if (sessionStorage.getItem(visitedKey) === 'true' && !isPageRefreshing) {
                // Tunggu sebentar sebelum menampilkan modal
                setTimeout(() => {
                    showBackButtonWarning();
                }, 500);
            }
        }
    });

    // 14. Reset flag refresh saat halaman selesai dimuat
    window.addEventListener('load', function() {
        // Reset flag setelah halaman dimuat sepenuhnya
        setTimeout(() => {
            isPageRefreshing = false;
        }, 1000);
    });
</script>

{{-- Style untuk modal --}}
<style>
    #backWarningModal .modal-content {
        border-radius: 12px;
        overflow: hidden;
        animation: slideInDown 0.3s ease-out;
    }
    
    #backWarningModal .modal-header {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        border-bottom: none;
        padding: 15px 20px;
    }
    
    #backWarningModal .modal-title {
        font-size: 18px;
        font-weight: 600;
    }
    
    #backWarningModal .modal-body {
        padding: 25px;
    }
    
    #backWarningModal .modal-footer {
        border-top: none;
        padding: 15px 20px 25px;
    }
    
    /* Animasi untuk modal */
    @keyframes slideInDown {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .shake-modal {
        animation: shake 0.5s ease-in-out;
    }
    
    /* Style untuk tombol dalam modal */
    #backWarningModal .btn-primary {
        background: linear-gradient(135deg, #1a4d8e 0%, #2c7be5 100%);
        border: none;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        min-width: 180px;
    }
    
    #backWarningModal .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 77, 142, 0.3);
    }
    
    /* Style khusus untuk modal yang tidak bisa ditutup */
    #backWarningModal.modal {
        backdrop-filter: blur(3px);
    }
    
    #backWarningModal.modal.show .modal-backdrop {
        opacity: 0.8 !important;
    }
    
    /* Nonaktifkan pointer events untuk backdrop */
    #backWarningModal .modal-backdrop {
        pointer-events: auto !important;
    }
</style>
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

    // Cek jika ada session messages dari Laravel
    @if(session('warning'))
        showAlert('warning', 'Peringatan', '{{ session('warning') }}');
    @endif
    
    @if(session('info'))
        showAlert('info', 'Informasi', '{{ session('info') }}');
    @endif
    
    @if(session('success'))
        showAlert('success', 'Berhasil', '{{ session('success') }}');
    @endif
    
    @if(session('error'))
        showAlert('error', 'Error', '{{ session('error') }}');
    @endif
</script>
    @endsection