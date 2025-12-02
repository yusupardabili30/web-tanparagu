@extends('layouts.main-user')
@section('mycontent')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Kegiatan - {{ $kegiatan->kegiatan_name }}</h4>
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


    <!-- =============================== -->
    <!-- PROFIL + INFO KEGIATAN SIDE-BY-SIDE (TURUN, ADA SPASI DARI HEADER) -->
    <!-- =============================== -->

    <div class="row mb-4 mt-5 pt-2"> 
        <!-- PROFIL DI KIRI -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header" 
                    style="background:#1a5bb8; color:white; border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white">Profil Pengguna</h5>
                </div>

                <div class="card-body p-4">

                    <!-- DETAIL PROFIL TANPA FOTO -->
                    <h4 class="fw-bold mb-1">Syntyce Solution</h4>
                    <p class="text-muted mb-3">Michael Morris</p>

<<<<<<< HEAD
                    <!-- ICON BUBBLE -->
                    <div class="d-flex gap-3 my-3">

                        <div class="px-4 py-2 rounded-pill bg-primary-subtle text-primary">
                            <i class="ri-global-line fs-20"></i>
=======
                        <!-- FOTO USER -->
                        <div class="me-4">
                            <img src="{{ asset('assets/images/brands/mail_chimp.png') }}"
                                class="rounded-circle"
                                style="width:95px; height:95px; object-fit:cover;">
>>>>>>> f8379aa0312d4d5d96e9b1cfc0a50c99c8c4f739
                        </div>

                        <div class="px-4 py-2 rounded-pill bg-danger-subtle text-danger">
                            <i class="ri-mail-line fs-20"></i>
                        </div>

                        <div class="px-4 py-2 rounded-pill bg-warning-subtle text-warning">
                            <i class="ri-question-answer-line fs-20"></i>
                        </div>

                    </div>

                    <!-- INFO TABEL -->
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" width="160">Industry Type</td>
                                <td>Chemical Industries</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Location</td>
                                <td>Damascus, Syria</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Employee</td>
                                <td>10–50</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Rating</td>
                                <td>4.0 <i class="ri-star-fill text-warning"></i></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Website</td>
                                <td><a href="#" class="link-primary">www.syntycesolution.com</a></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email</td>
                                <td>info@syntycesolution.com</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Since</td>
                                <td>1995</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- BUTTON KE HALAMAN RIWAYAT -->
                    <div class="mt-4 text-end">
                        <a href="{{ url('kegiatan/riwayat') }}" class="btn btn-outline-primary btn-lg">
                            Lihat Riwayat Kegiatan
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- CARD INFO KEGIATAN DI KANAN -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header" 
                    style="background:#1a5bb8; color:white; border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white">Detail Kegiatan</h5>
                </div>

                <div class="card-body p-4">

                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" width="160">Nama Kegiatan</td>
                                <td>: {{ $kegiatan->kegiatan_name }}</td>
                            </tr>

                            <tr>
                                <td class="fw-semibold">Entity</td>
                                <td>: {{ $kegiatan->entity }}</td>
                            </tr>

                            <tr>
                                <td class="fw-semibold">Tanggal Mulai</td>
                                <td>: {{ $start_date }}</td>
                            </tr>

                            <tr>
                                <td class="fw-semibold">Tanggal Selesai</td>
                                <td>: {{ $end_date }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        @if(session('quiz_started'))
                            <a href="{{ route('ptk.continue-quiz', $kegiatan->kegiatan_id) }}" 
                                class="btn btn-success btn-lg">
                                Lanjutkan Quiz
                            </a>
                        @else
                            <a href="{{ route('ptk.start-quiz', $kegiatan->kegiatan_id) }}" 
                                class="btn btn-primary btn-lg">
                                Mulai Quiz
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>


<<<<<<< HEAD
    <!-- INFO SESSION -->
    <div class="alert alert-info mt-3 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="ri-user-line fs-4 me-2"></i>
            <div>
                <h6 class="mb-1">Informasi Sesi</h6>
                <small>
                    Anda sedang mengakses kegiatan:
                    <b>{{ $kegiatan->kegiatan_name }}</b><br>
                    Token: <code>{{ $kegiatan->instrumen_token }}</code>
                </small>
=======
    <!-- TOGGLE SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const collapse = document.getElementById('profilCollapse');
            const icon = document.getElementById('iconToggle');

            collapse.addEventListener('shown.bs.collapse', () => {
                icon.classList.remove('ri-arrow-right-s-line');
                icon.classList.add('ri-arrow-down-s-line');
            });

            collapse.addEventListener('hidden.bs.collapse', () => {
                icon.classList.remove('ri-arrow-down-s-line');
                icon.classList.add('ri-arrow-right-s-line');
            });
        });
    </script>


    <!-- =============================== -->
    <!-- CARD KEGIATAN (BLUE STYLE)     -->
    <!-- =============================== -->

    <div class="row">
        <div class="col-12">

            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-header"
                    style="background:#1a5bb8; color:white; border-radius:14px 14px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">Informasi Kegiatan</h5>
                        <div class="text-white">
                            <i class="ri-calendar-line me-1"></i>
                            Periode: {{ $start_date }} - {{ $end_date }}
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Entity</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <strong>{{ $kegiatan->kegiatan_name }}</strong><br>
                                        <small>Token: <code>{{ $kegiatan->instrumen_token }}</code></small>
                                    </td>
                                    <td>{{ $kegiatan->entity }}</td>
                                    <td>{{ $start_date }}</td>
                                    <td>{{ $end_date }}</td>
                                    <td>
                                        @if(session('quiz_started'))
                                        <a class="btn btn-success btn-sm" href="{{ route('ptk.continue-quiz', $kegiatan->kegiatan_id) }}">
                                            Lanjutkan
                                        </a>
                                        @else
                                        <a class="btn btn-primary btn-sm" href="{{ route('ptk.start-quiz', $kegiatan->kegiatan_id) }}">
                                            Mulai Quiz
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>


                    <!-- DETAIL CARD -->
                    <div class="row mt-4">

                        <div class="col-md-6">
                            <div class="card bg-light border-0 shadow-sm" style="border-radius:14px;">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-2">
                                        <i class="ri-information-line me-2"></i> Informasi Kegiatan
                                    </h6>

                                    <table class="table table-borderless mb-0">
                                        <tbody>

                                            <tr class="row-blue">
                                                <td class="fw-semibold">Nama Kegiatan</td>
                                                <td>: {{ $kegiatan->kegiatan_name }}</td>
                                            </tr>

                                            <tr class="row-blue">
                                                <td class="fw-semibold">Entity</td>
                                                <td>: {{ $kegiatan->entity }}</td>
                                            </tr>

                                            <tr class="row-blue">
                                                <td class="fw-semibold">Token</td>
                                                <td>: <code>{{ $kegiatan->instrumen_token }}</code></td>
                                            </tr>

                                            <tr class="row-blue">
                                                <td class="fw-semibold">Status</td>
                                                <td>:
                                                    @if($kegiatan->status == 'Active')
                                                    <span class="badge bg-success">Aktif</span>
                                                    @else
                                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card bg-light border-0 shadow-sm" style="border-radius:14px;">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-2">
                                        <i class="ri-calendar-event-line me-2"></i> Periode Kegiatan
                                    </h6>

                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td><b>Tanggal Mulai</b></td>
                                                <td>: {{ $start_date }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Tanggal Selesai</b></td>
                                                <td>: {{ $end_date }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Durasi</b></td>
                                                <td>:
                                                    @php
                                                    $start = \Carbon\Carbon::parse($kegiatan->start_date);
                                                    $end = \Carbon\Carbon::parse($kegiatan->end_date);
                                                    @endphp
                                                    {{ $start->diffInDays($end) + 1 }} hari
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Sisa Waktu</b></td>
                                                <td>:
                                                    @php
                                                    $now = \Carbon\Carbon::now();
                                                    $remaining = $now->diffInDays($end, false);
                                                    @endphp

                                                    @if($remaining > 0)
                                                    <span class="text-success">{{ $remaining }} hari lagi</span>
                                                    @elseif($remaining == 0)
                                                    <span class="text-warning">Berakhir hari ini</span>
                                                    @else
                                                    <span class="text-danger">Sudah berakhir</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div><!-- /row -->


                    <!-- INFO SESSION -->
                    <div class="alert alert-info mt-3 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="ri-user-line fs-4 me-2"></i>
                            <div>
                                <h6 class="mb-1">Informasi Sesi</h6>
                                <small>
                                    Anda sedang mengakses kegiatan:
                                    <b>{{ $kegiatan->kegiatan_name }}</b><br>
                                    Token: <code>{{ $kegiatan->instrumen_token }}</code>
                                </small>
                            </div>

                            <div class="ms-auto">
                                <a class="btn btn-outline-danger btn-sm"
                                    href="{{ route('lockscreen.logout') }}">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>

                </div><!-- /card-body -->

>>>>>>> f8379aa0312d4d5d96e9b1cfc0a50c99c8c4f739
            </div>

            <div class="ms-auto">
                <a class="btn btn-outline-danger btn-sm"
                    href="{{ route('lockscreen.logout') }}">
                    Logout
                </a>
            </div>
        </div>
    </div>

<<<<<<< HEAD
</div>
=======
</div>
>>>>>>> f8379aa0312d4d5d96e9b1cfc0a50c99c8c4f739
