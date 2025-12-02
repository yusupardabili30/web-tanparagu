@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Quiz</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Quiz</a></li>
                        <li class="breadcrumb-item active">Soal</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body p-4">

                    <div class="row">

                        <!-- Judul Studi Kasus -->
                        <div class="col-12 fs-4 fw-bold mb-3">
                            Studi Kasus
                        </div>

                        <!-- Isi Studi Kasus -->
                        <div class="col-12 fs-5 mb-4" style="text-align: justify;">
                            {!! nl2br(e($case->case)) !!}
                        </div>

                        <!-- Judul Soal -->
                        <div class="col-12 fs-4 fw-semibold mb-2">
                            Soal
                        </div>

                        <!-- Isi Soal -->
                        <div class="col-12 fs-5 mb-3">

                            <p class="fw-bold">
                                {{ $soal->soal }}
                            </p>

                            <form action="{{ route('quiz.submit') }}" method="POST">
                                @csrf

                                <input type="hidden" name="soal_id" value="{{ $soal->soal_id }}">
                                <input type="hidden" name="sub_indikator_id" value="{{ $soal->sub_indikator_id }}">
                                <input type="text" name="bobot" id="bobot">

                                @foreach ($choices as $c)
                                    <div class="form-check mb-2">
                                        <input 
                                            class="form-check-input pilihan" 
                                            type="radio"
                                            name="pilihan_jawaban_id"
                                            id="choice{{ $c->soal_jawaban_id }}"
                                            value="{{ $c->soal_jawaban_id }}"
                                            data-bobot="{{ $c->bobot }}"
                                            required
                                        >
                                        <label class="form-check-label" for="choice{{ $c->soal_jawaban_id }}" style="text-align: justify;">
                                            {{ $c->pilihan_jawaban }}
                                        </label>
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary mt-3">Jawab</button>
                            </form>

                        </div>

                    </div>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>

</div>
@endsection

@section('sipproja-js')
<script>
$(document).ready(function(){    
    // Tambahkan logika JS di sini jika dibutuhkan
    document.querySelectorAll('.pilihan').forEach(radio => {
    radio.addEventListener('change', function() {
         document.getElementById('bobot').value = this.dataset.bobot;
    });
});
});
</script>
@endsection
