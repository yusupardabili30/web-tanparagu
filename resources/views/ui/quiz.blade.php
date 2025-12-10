    @extends('layouts.main-user')

    @section('mycontent')

    <!-- ============================================ -->
    <!-- FLOATING NAVBAR + SLIDE PANEL -->
    <!-- ============================================ -->

    <!-- TOGGLE BUTTON HALF OVAL -->
    <div id="floatingNav">
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


        <!-- PAGE TITLE -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Quiz</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0" style="font-size:15px; font-weight:400;">
                            <li class="breadcrumb-item"><a href="#" class="text-primary">Quiz</a></li>
                            <li class="breadcrumb-item active">Soal</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <!-- ============================================ -->
        <!-- QUIZ CARD -->
        <!-- ============================================ -->
        <div class="row">
            <div class="col-xl-12">

                <div class="card border-0 shadow-sm" style="border-radius:14px; position: relative;">

                    <!-- HEADER BADUY -->
                    <div class="card-header baduy-bg" style="border-radius:14px 14px 0 0;">
                        <h5 class="mb-0 text-white d-flex align-items-center" style="font-size:20px; font-weight:700;">
                            <i class="ri-book-open-line me-2"></i> Quiz
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <div class="row">

                            <!-- STUDI KASUS -->
                            <div class="col-12 mb-3">
                                <div class="big-box">
                                    <h5 class="box-title">
                                        <i class="ri-article-line me-1" style="color:#1a4d8e;"></i>
                                        <span style="color:#1a4d8e; font-weight:700;">Studi Kasus</span>
                                    </h5>

                                    <p class="box-text" style="font-size:19px; line-height:1.6;">
                                        Di kelas Anda terdapat murid bernama Titis yang dikenal cerdas dan mampu menyampaikan pendapat secara kritis. Namun, dalam beberapa minggu terakhir ia sering menyela penjelasan guru dengan komentar yang tidak relevan...
                                    </p>
                                </div>
                            </div>


                            <!-- SOAL -->
                            <div class="col-12">
                                <div class="big-box">

                                    <h5 class="box-title">
                                        <i class="ri-question-line me-1 text-primary"></i>
                                        <span style="color:#1a4d8e; font-weight:700;">Soal</span>
                                    </h5>

                                    <p class="soal-text mb-4" style="font-size:19px; font-weight:700; line-height:1.6;">
                                        Jika Anda guru Titis, apa langkah paling tepat?
                                    </p>

                                    <form action="#" method="POST">
                                        @csrf

                                        <label class="quiz-choice">
                                            <input type="radio" name="pilihan_jawaban_id" class="radio-inside">
                                            <span class="choice-text">Menegur langsung</span>
                                        </label>

                                        <label class="quiz-choice">
                                            <input type="radio" name="pilihan_jawaban_id" class="radio-inside">
                                            <span class="choice-text">Mengajak refleksi setelah kelas</span>
                                        </label>

                                        <label class="quiz-choice">
                                            <input type="radio" name="pilihan_jawaban_id" class="radio-inside">
                                            <span class="choice-text">Memberi tugas tambahan</span>
                                        </label>

                                        <label class="quiz-choice">
                                            <input type="radio" name="pilihan_jawaban_id" class="radio-inside">
                                            <span class="choice-text">Memindahkan tempat duduk</span>
                                        </label>

                                        <button type="submit" class="btn btn-primary btn-lg mt-4 btn-jawab">
                                            <i class="ri-checkbox-circle-line me-2"></i> Submit (Dummy)
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
    </div> <!-- END WRAPPER -->



    <!-- ============================================ -->
    <!-- FULL CSS (HALF OVAL BUTTON + RESPONSIVE) -->
    <!-- ============================================ -->

    <style>

    .big-box {
        background: #f6f8ff;
        border: 1px solid #d7e2ff;
        border-radius: 12px;
        padding: 20px 22px;
    }

    .quiz-choice {
        background: #f4f7ff;
        border: 1px solid #d6e4ff;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .radio-inside {
        width: 20px;
        height: 20px;
    }

    .choice-text {
        margin-left: 12px;
        font-size: 17px;
        font-weight: 500;
    }

    /* ======================================== */
    /* DESKTOP HALF OVAL BUTTON */
    /* ======================================== */
    #floatingNav {
        position: fixed;
        top: 142px;          /* sejajar dengan teks QUIZ */
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
            left: -28px;           /* muncul dari sisi kiri */
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
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
            box-shadow: 0 4px 10px rgba(0,0,0,.2);
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
        box-shadow: 3px 0 15px rgba(0,0,0,0.15);
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
        top: 15px !important;  /* turun 10px dari posisi sebelumnya */
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

    </style>



    @endsection



    @section('sipproja-js')
    <script>

    // OPEN PANEL
    document.getElementById("toggleFloating").addEventListener("click", function () {
        document.getElementById("floatingPanel").classList.add("open");
        document.querySelector(".content-wrapper").classList.add("shift");
        document.getElementById("floatingNav").classList.add("hide");
    });

    // CLOSE PANEL
    document.getElementById("closePanelBtn").addEventListener("click", function () {
        document.getElementById("floatingPanel").classList.remove("open");
        document.querySelector(".content-wrapper").classList.remove("shift");
        document.getElementById("floatingNav").classList.remove("hide");
    });

    </script>
    @endsection
