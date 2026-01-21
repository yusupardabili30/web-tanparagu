@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">
    
    <br>
    <br>
    <br>
    <br>

    <!-- SUCCESS CARD -->
    <div class="row justify-content-center">
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <!-- HEADER -->
                <div class="card-header baduy-bg text-center" style="border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        <i class="ri-check-double-line me-2"></i> Data Pelatihan Tersimpan
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
                        Terima Kasih! Data Pelatihan Anda Telah Disimpan
                    </h3>

                    <!-- DETAILS -->
                    <div class="mb-4">
                        <p class="mb-2" style="font-size: 18px;">
                            <strong>Nama:</strong> {{ $ptk->nama ?? 'Tidak ditemukan' }}
                        </p>
                        <p class="mb-2" style="font-size: 18px;">
                            <strong>NIP:</strong> {{ $ptk->nip ?? 'Tidak ditemukan' }}
                        </p>
                        <p class="mb-2" style="font-size: 18px;">
                            <strong>Kegiatan:</strong> {{ $kegiatan->kegiatan_name ?? 'Tidak ditemukan' }}
                        </p>
                    </div>

                    <!-- MESSAGE -->
                    <div class="alert alert-success" role="alert" style="font-size: 16px; border-radius: 10px;">
                        <i class="ri-information-line me-2"></i>
                        Data preferensi pelatihan Anda telah berhasil disimpan. 
                        Data ini akan digunakan untuk perencanaan pelatihan ke depan.
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="card-footer text-center" style="background: #f8f9fa; border-top: 1px solid #dee2e6;">
                    <p class="mb-0 text-muted" style="font-size: 14px;">
                        <i class="ri-time-line me-1"></i>
                        Disimpan pada: {{ date('d F Y H:i:s') }}
                    </p>

                    <!-- BUTTONS -->
                    <div class="mt-3">
                        <a href="{{ route('ptk.show', ['encode_kegiatan_id' => $encoded_kegiatan_id, 'nip' => $nip]) }}"
                           class="btn btn-primary btn-lg btn-back-blue w-100 d-block"
                           style="border-radius:12px; padding: 14px 22px; font-weight: 700;">
                            <i class="ri-home-line me-2"></i> Kembali ke Dashboard
                        </a>
                    </div>
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

    .btn-back-blue{
        background: #1a4d8e !important;
        border-color: #1a4d8e !important;
        color: #fff !important;
        box-shadow: 0 8px 18px rgba(26, 91, 184, .18);
    }
    .btn-back-blue:hover,
    .btn-back-blue:focus{
        background: #164f9e !important;
        border-color: #164f9e !important;
        color: #fff !important;
    }
</style>

@endsection