@extends('layouts.main-user')
@section('mycontent')

<div class="container-fluid">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <div class="container-fluid">
        <!-- start page title -->
        <div class="row mb-1 pt-0" style="margin-top:-50px;">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('ptk.riwayat', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $nip]) }}"
                                    class="text-primary fw-bold">
                                    <i class="ri-arrow-left-line me-1"></i> Kembali ke Riwayat
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Detail Riwayat</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- =============================== -->
        <!-- INFO KEGIATAN DAN PTK          -->
        <!-- =============================== -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-information-line me-2 text-primary"></i>
                            Informasi Kegiatan
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama Kegiatan:</strong><br>{{ $kegiatan->nama_kegiatan }}</p>
                                <p><strong>Token:</strong><br><code>{{ $kegiatan->instrumen_token ?? '-' }}</code></p>
                                <p><strong>Entity:</strong><br>{{ $kegiatan->entity ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Periode:</strong><br>{{ $start_date }} s/d {{ $end_date }}</p>
                                <p><strong>Status:</strong><br>
                                    @if($kegiatan->status == 'Active')
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary">Selesai</span>
                                    @endif
                                </p>
                                <p><strong>Terakhir Update:</strong><br>{{ $tanggalTerakhir }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-user-line me-2 text-primary"></i>
                            Informasi PTK
                        </h5>
                        <p><strong>Nama:</strong> {{ $ptk->nama }}</p>
                        <p><strong>NIP:</strong> {{ $ptk->nip }}</p>
                        <p><strong>Instansi:</strong> {{ $ptk->instansi ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================== -->
        <!-- STATISTIK HASIL                 -->
        <!-- =============================== -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 bg-primary text-white shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $totalSubIndikator }}</h2>
                        <small>Total Sub Indikator</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-success text-white shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $levelTertinggi }}</h2>
                        <small>Level Tertinggi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-warning text-white shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $levelTerendah }}</h2>
                        <small>Level Terendah</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $rataRataLevel }}</h2>
                        <small>Rata-rata Level</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================== -->
        <!-- DETAIL SUB INDIKATOR           -->
        <!-- =============================== -->
        <div class="card border-0 shadow-sm">
            <div class="card-header baduy-bg">
                <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                    <i class="ri-list-check me-2"></i> Detail Jawaban per Sub Indikator
                </h5>
            </div>
            <div class="card-body">
                @if($detailJawaban->count() == 0)
                <div class="text-center py-5">
                    <i class="ri-inbox-line fs-1 text-muted"></i>
                    <p class="text-muted mt-3">Belum ada data jawaban untuk kegiatan ini.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Kode Sub Indikator</th>
                                <th>Nama Sub Indikator</th>
                                <th width="120">Level</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Jawab</th>
                                <th>Terakhir Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailJawaban as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $item->sub_indikator_code ?? '-' }}</span>
                                </td>
                                <td>{{ $item->sub_indikator_name ?? 'Tidak diketahui' }}</td>
                                <td class="text-center">
                                    @php
                                    $level = $item->level ?? 0;
                                    $badgeClass = '';
                                    if($level >= 4) $badgeClass = 'bg-success';
                                    elseif($level >= 3) $badgeClass = 'bg-info';
                                    elseif($level >= 2) $badgeClass = 'bg-warning';
                                    else $badgeClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} fs-6">{{ $level }}</span>
                                </td>
                                <td>{{ $item->sub_indikator_dec ?? '-' }}</td>
                                <td>
                                    @if($item->date_create)
                                    {{ date('d M Y H:i', strtotime($item->date_create)) }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if($item->date_update)
                                    {{ date('d M Y H:i', strtotime($item->date_update)) }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- =============================== -->
        <!-- GRAFIK LEVEL (Optional)        -->
        <!-- =============================== -->
        @if($detailJawaban->count() > 0)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header baduy-bg">
                <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                    <i class="ri-bar-chart-line me-2"></i> Distribusi Level
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                    $levelCounts = $detailJawaban->groupBy('level')->map->count();
                    @endphp

                    @foreach($levelCounts as $level => $count)
                    @php
                    $percentage = ($count / $totalSubIndikator) * 100;
                    @endphp
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Level {{ $level }}</h6>
                                        <small class="text-muted">{{ $count }} sub indikator</small>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="mb-0">{{ round($percentage, 1) }}%</h4>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar 
                                            @if($level >= 4) bg-success
                                            @elseif($level >= 3) bg-info
                                            @elseif($level >= 2) bg-warning
                                            @else bg-danger
                                            @endif"
                                        role="progressbar"
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- =============================== -->
        <!-- ACTION BUTTONS                 -->
        <!-- =============================== -->
        <div class="row mt-4">
            <div class="col-md-6">
                <a href="{{ route('ptk.riwayat', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $nip]) }}"
                    class="btn btn-outline-primary btn-lg w-100">
                    <i class="ri-arrow-left-line me-2"></i> Kembali ke Riwayat
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('ptk.show', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $nip]) }}"
                    class="btn btn-primary btn-lg w-100">
                    <i class="ri-home-line me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>

    </div>
</div>

<style>
    .table th,
    .table td {
        text-align: center !important;
        vertical-align: middle !important;
    }

    .table td:not(:nth-child(3)):not(:nth-child(5)) {
        text-align: center !important;
    }

    .table td:nth-child(3),
    .table td:nth-child(5) {
        text-align: left !important;
    }

    .card {
        border-radius: 14px;
    }
</style>

@endsection