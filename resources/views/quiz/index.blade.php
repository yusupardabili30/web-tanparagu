@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">

    <!-- CSS EXISTING -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <!-- PAGE TITLE -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Quiz</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0" style="font-size:15px; font-weight:400;">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-primary">
                                Quiz
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Soal
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- QUIZ CARD -->
    <div class="row">
        <div class="col-xl-12">

            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <!-- HEADER BADUY -->
                <div class="card-header baduy-bg" style="border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white d-flex align-items-center" style="font-size:20px; font-weight:700;">
                        <i class="ri-book-open-line me-2"></i> Quiz
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

                                <form action="{{ route('quiz.submit') }}" method="POST">
                                    @csrf

                                    <!-- Hidden Inputs -->
                                    <input type="hidden" name="soal_id" value="{{ $soal->soal_id }}">
                                    <input type="hidden" name="sub_indikator_id" value="{{ $sub_indikator_id }}"> <!-- ID ASLI -->
                                    <input type="text" name="encoded_kegiatan_id" value="{{ $encoded_kegiatan_id }}">
                                    <input type="text" name="encoded_sub_indikator_id" value="{{ $encoded_sub_indikator_id }}">
                                    <input type="text" name="encoded_no_urut" value="{{ $encoded_no_urut }}">
                                    <input type="text" name="nip" value="{{ $nip }}">
                                    <input type="text" name="bobot" id="bobot">

                                    <!-- Pilihan Jawaban -->
                                    @foreach ($choices as $c)
                                    <label class="quiz-choice">
                                        <input
                                            type="radio"
                                            name="pilihan_jawaban_id"
                                            class="form-check-input pilihan radio-inside"
                                            value="{{ $c->soal_jawaban_id }}"
                                            data-bobot="{{ $c->bobot }}"
                                            id="choice{{ $c->soal_jawaban_id }}"
                                            required>
                                        <span class="choice-text">
                                            {{ $c->pilihan_jawaban }}
                                        </span>
                                    </label>
                                    @endforeach

                                    <button type="submit" class="btn btn-primary btn-lg mt-4 btn-jawab">
                                        <i class="ri-checkbox-circle-line me-2"></i> Submit
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

<!-- ========================= -->
<!-- LOCAL CSS -->
<!-- ========================= -->
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
    document.querySelectorAll('.pilihan').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('bobot').value = this.dataset.bobot;
        });
    });
</script>
@endsection