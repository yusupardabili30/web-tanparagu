@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <!-- PAGE TITLE -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Quiz Selesai</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0" style="font-size:15px; font-weight:400;">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-primary">
                                Quiz
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Selesai
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- FINISH CARD -->
    <div class="row justify-content-center">
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <!-- HEADER -->
                <div class="card-header baduy-bg text-center" style="border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        <i class="ri-checkbox-circle-line me-2"></i> Quiz Telah Selesai
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
                        Selamat! Anda Telah Menyelesaikan Instrumen Pemetaan Pembelajaran Guru 
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
                        Terima kasih telah mengikuti quiz. Jawaban Anda telah tersimpan dengan baik.
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

@endsection