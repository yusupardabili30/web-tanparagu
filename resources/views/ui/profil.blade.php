@extends('layouts.main-user')
@section('mycontent')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Kegiatan - <span class="text-primary">Contoh Kegiatan</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Kegiatan</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->


    <!-- PROFIL + INFO KEGIATAN -->
    <div class="row mb-4 mt-5 pt-2">

        <!-- PROFIL DI KIRI -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header"
                    style="background:#1a5bb8; color:white; border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white">Profil Pengguna</h5>
                </div>

                <div class="card-body p-4">

                    <h2 class="fw-bold mb-1">Tim IT</h2>

                    <!-- ICON BUBBLES (TANPA FOTO) -->
                    <div class="d-flex gap-3 my-3">

                        <div class="px-4 py-2 rounded-pill bg-primary-subtle text-primary">
                            <i class="ri-global-line fs-15"></i>
                        </div>

                        <div class="px-4 py-2 rounded-pill bg-danger-subtle text-danger">
                            <i class="ri-mail-line fs-15"></i>
                        </div>

                        <div class="px-4 py-2 rounded-pill bg-warning-subtle text-warning">
                            <i class="ri-question-answer-line fs-15"></i>
                        </div>

                    </div>

                    <!-- INFO TABEL -->
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr><td class="fw-semibold" width="160">Industry Type</td><td>Chemical Industries</td></tr>
                            <tr><td class="fw-semibold">Location</td><td>Damascus, Syria</td></tr>
                            <tr><td class="fw-semibold">Employee</td><td>10–50</td></tr>
                            <tr><td class="fw-semibold">Rating</td><td>4.0 <i class="ri-star-fill text-warning"></i></td></tr>
                            <tr><td class="fw-semibold">Website</td><td><a href="#" class="link-primary">www.syntycesolution.com</a></td></tr>
                            <tr><td class="fw-semibold">Email</td><td>info@syntycesolution.com</td></tr>
                            <tr><td class="fw-semibold">Since</td><td>1995</td></tr>
                        </tbody>
                    </table>

                    <!-- BUTTON RIWAYAT -->
                    <div class="mt-4 text-end">
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            Lihat Riwayat Kegiatan
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- INFO KEGIATAN DI KANAN -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header"
                    style="background:#1a5bb8; color:white; border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white">Detail Kegiatan</h5>
                </div>

                <div class="card-body p-4">

                    <table class="table table-borderless">
                        <tbody>
                            <tr><td class="fw-semibold" width="160">Nama Kegiatan</td><td>: Contoh Kegiatan</td></tr>
                            <tr><td class="fw-semibold">Entity</td><td>: Contoh Entity</td></tr>
                            <tr><td class="fw-semibold">Tanggal Mulai</td><td>: 01 Januari 2025</td></tr>
                            <tr><td class="fw-semibold">Tanggal Selesai</td><td>: 10 Januari 2025</td></tr>
                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        <a href="#" class="btn btn-primary btn-lg">Mulai Quiz</a>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- INFO SESSION -->
    <div class="alert alert-info mt-3 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="ri-user-line fs-4 me-2"></i>
            <div>
                <h6 class="mb-1">Informasi Sesi</h6>
                <small>
                    Anda sedang mengakses kegiatan: <b>Contoh Kegiatan</b><br>
                    Token: <code>ABC123XYZ</code>
                </small>
            </div>

            <div class="ms-auto">
                <a class="btn btn-outline-danger btn-sm" href="#">
                    Logout
                </a>
            </div>
        </div>
    </div>

</div>
