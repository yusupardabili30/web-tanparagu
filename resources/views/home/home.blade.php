@extends('layouts.main')
@section('mycontent')

<style>
/* =========================
   MEGA MENDUNG THEME (HOME)
   ========================= */
:root{
    --mm-blue: #1a5bb8;
    --mm-blue-dark: #133d78;
    --mm-soft: #f6f9ff;
}

/* Card utama */
.mm-card{
    border: 1.3px solid #d0d7e5 !important;
    border-radius: 14px !important;
    overflow: hidden;
}

/* =========================
   HEADER (BADUY BACKGROUND)
   ========================= */
.mm-header{
    background-color: var(--mm-blue) !important;
    color: #fff !important;
    position: relative;
    overflow: hidden;
    padding: 22px 24px !important;
}

/* BACKGROUND BADUY (PAKAI asset() BIAR AMAN PATH NYA) */
.mm-header::before{
    content:"";
    position:absolute;
    inset:0;
    background-image: url("{{ asset('build/images/baduy.jpg') }}"); /* ✅ ubah path kalau beda */
    background-size: 220px;
    background-repeat: repeat;
    opacity: .42;
    z-index: 0;
}

/* overlay biru tipis biar motif rapih */
.mm-header::after{
    content:"";
    position:absolute;
    inset:0;
    background: rgba(26,91,184,.45);
    z-index: 1;
}

/* pastiin teks di atas background + overlay */
.mm-header > *{
    position: relative;
    z-index: 2;
}

.mm-title{
    font-weight: 800;
    font-size: 22px;
    margin: 0;
}
.mm-sub{
    margin: 4px 0 0 0;
    opacity: .92;
    font-size: 13px;
}

/* Body */
.mm-body{
    background: var(--mm-soft) !important;
    padding: 26px !important;
}

.mm-inner{
    background: #fff;
    border-radius: 14px;
    padding: 26px;
}

/* Text */
.mm-text-blue{ color: #1a3f6b !important; }
.mm-paragraph{
    color:#333;
    line-height: 1.75;
    font-size: 15px;
}

/* Badge role */
.mm-badge{
    background: rgba(255,255,255,.18) !important;
    border: 1px solid rgba(255,255,255,.25) !important;
    color:#fff !important;
    font-weight: 700 !important;
    padding: 10px 14px !important;
    border-radius: 999px !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Button */
.mm-btn{
    background: var(--mm-blue-dark) !important;
    border-color: var(--mm-blue-dark) !important;
    color:#fff !important;
    font-weight: 700 !important;
    border-radius: 12px !important;
    padding: 10px 16px !important;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.mm-btn:hover{ opacity: .95; }

.mm-btn-outline{
    border: 2px solid var(--mm-blue-dark) !important;
    color: var(--mm-blue-dark) !important;
    background: transparent !important;
    font-weight: 700 !important;
    border-radius: 12px !important;
    padding: 10px 16px !important;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.mm-btn-outline:hover{
    background: var(--mm-blue-dark) !important;
    color:#fff !important;
}

/* Gambar */
.mm-hero img{
    max-height: 340px;
    width: 100%;
    object-fit: contain;
}

/* responsive */
@media(max-width: 768px){
    .mm-body{ padding: 16px !important; }
    .mm-inner{ padding: 18px; }
    .mm-title{ font-size: 18px; }
}
</style>

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Starter</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Starter</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card mm-card shadow-sm">
                <div class="card-body p-0">

                    <!-- HEADER BADUY -->
                    <div class="mm-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h2 class="mm-title" style="color:#fff !important;">Selamat Datang!</h2>
                            <p class="mm-sub">
                                <i class="ri-user-line"></i> {{ auth()->user()->nama }}
                            </p>
                        </div>

                        <span class="mm-badge">
                            Role:
                            @switch(auth()->user()->role_id)
                                @case(1) Administrator @break
                                @case(2) Kepala Sub-Bagian @break
                                @case(3) Katim @break
                                @case(4) Tim @break
                                @default Role Tidak Terdaftar
                            @endswitch
                        </span>
                    </div>

                    <!-- BODY -->
                    <div class="mm-body">
                        <div class="mm-inner">
                            <div class="row align-items-center g-4">

                                <!-- KIRI -->
                                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
                                     data-aos="fade-up">
                                    <h1 class="mm-text-blue fw-bold mb-3">
                                        TANPARAGU<br>
                                        <span class="text-muted" style="font-weight:600; font-size:18px;">
                                            (Tautan Pemetaan Kebutuhan Belajar Guru)
                                        </span>
                                    </h1>

                                    <p class="mm-paragraph">
                                        Tautan Pemetaan Kebutuhan Belajar Guru adalah aplikasi berbasis web yang dikembangkan oleh Balai Guru dan Tenaga Kependidikan Provinsi Banten untuk membantu satuan pendidikan dalam mengidentifikasi kebutuhan belajar guru secara lebih cepat, akurat, dan terstruktur. Aplikasi ini hadir sebagai bagian dari upaya BGTK Provinsi Banten dalam menyediakan layanan data yang transparan, mudah diakses, serta mendukung peningkatan kualitas pembelajaran di satuan pendidikan.
                                    </p>
                                </div>

                                <!-- KANAN -->
                                <div class="col-lg-6 order-1 order-lg-2 mm-hero"
                                    data-aos="zoom-out" data-aos-delay="100">
                                    <img src="{{ asset('build/images/file.png') }}" class="img-fluid" alt="Hero">
                                </div>

                            </div>
                        </div>
                    </div>

                </div><!-- end card-body -->
            </div>
        </div><!-- end col -->
    </div>

</div><!-- container-fluid -->
@endsection

@section('sipproja-js')
<script>
  $(document).ready(function(){
    // Swal.fire("SweetAlert2 is working!");
  });
</script>
@endsection
