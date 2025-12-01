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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Informasi Kegiatan</h5>
                        <div class="text-muted">
                            <i class="ri-calendar-line me-1"></i>
                            Periode: {{ $start_date }} - {{ $end_date }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Tabel Informasi Kegiatan -->
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
                                        <strong>{{ $kegiatan->kegiatan_name }}</strong>
                                        <br>
                                        <small class="text-muted">Token: <code>{{ $kegiatan->instrumen_token }}</code></small>
                                    </td>
                                    <td>{{ $kegiatan->entity }}</td>
                                    <td>{{ $start_date }}</td>
                                    <td>{{ $end_date }}</td>
                                    <td>
                                        @if(session('quiz_started'))
                                        <!-- Jika quiz sudah dimulai, tampilkan tombol Lanjutkan -->
                                        <a href="{{ route('ptk.continue-quiz', $kegiatan->kegiatan_id) }}" class="btn btn-sm btn-success">
                                            <i class="ri-continue-line me-1"></i> Lanjutkan Quiz
                                        </a>
                                        @else
                                        <!-- Jika belum mulai, tampilkan tombol Mulai -->
                                        <a href="{{ route('ptk.start-quiz', $kegiatan->kegiatan_id) }}" class="btn btn-sm btn-primary">
                                            <i class="ri-play-circle-line me-1"></i> Mulai Quiz
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Informasi Detail Kegiatan -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="ri-information-line me-2"></i>Informasi Kegiatan</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td width="40%"><strong>Nama Kegiatan</strong></td>
                                                    <td>: {{ $kegiatan->kegiatan_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Entity</strong></td>
                                                    <td>: {{ $kegiatan->entity }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Token Akses</strong></td>
                                                    <td>: <code>{{ $kegiatan->instrumen_token }}</code></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status</strong></td>
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
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="ri-calendar-event-line me-2"></i>Periode Kegiatan</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td width="40%"><strong>Tanggal Mulai</strong></td>
                                                    <td>: {{ $start_date }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Selesai</strong></td>
                                                    <td>: {{ $end_date }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Durasi</strong></td>
                                                    <td>:
                                                        @php
                                                        $start = \Carbon\Carbon::parse($kegiatan->start_date);
                                                        $end = \Carbon\Carbon::parse($kegiatan->end_date);
                                                        $days = $start->diffInDays($end) + 1;
                                                        @endphp
                                                        {{ $days }} hari
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Sisa Waktu</strong></td>
                                                    <td>:
                                                        @php
                                                        $now = \Carbon\Carbon::now();
                                                        $end = \Carbon\Carbon::parse($kegiatan->end_date);
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
                        </div>
                    </div>

                    <!-- Info Session & Tombol Logout -->
                    <div class="alert alert-info mt-3">
                        <div class="d-flex align-items-center">
                            <i class="ri-user-line fs-4 me-2"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Informasi Sesi</h6>
                                <p class="mb-0">
                                    Anda sedang mengakses kegiatan: <strong>{{ $kegiatan->kegiatan_name }}</strong>
                                    <br>
                                    Token akses: <code>{{ $kegiatan->instrumen_token }}</code>
                                    <br>
                                    <small class="text-muted">Sesi akan aktif hingga Anda logout atau menutup browser</small>
                                </p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('lockscreen.logout') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="ri-logout-box-line me-1"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('sipproja-js')
<script>
    $(document).ready(function() {
        // Timer untuk sisa waktu kegiatan
        function updateRemainingTime() {
            $.get('/check-session', function(response) {
                if (!response.authenticated) {
                    alert('Sesi telah berakhir. Silakan login kembali.');
                    window.location.href = '/';
                }
            });
        }

        // Update setiap 5 menit
        setInterval(updateRemainingTime, 300000);

        // Update saat pertama kali load
        updateRemainingTime();
    });
</script>
@endsection