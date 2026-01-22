@extends('layouts.main')
@section('mycontent')
@php
    $tittle = 'Analisis Hasil Instrumen';
    
    $levelNames = [
        2 => 'Penerapan',
        3 => 'Analisis', 
        4 => 'Evaluasi',
        5 => 'Pembimbingan'
    ];
    
    $levelColors = [
        2 => '#17a2b8',
        3 => '#007bff',
        4 => '#ffc107',
        5 => '#28a745'
    ];
    
    $jenjangColors = [
        'Pertama' => '#ff6b6b',
        'Muda' => '#4ecdc4', 
        'Madya' => '#45b7d1',
        'Utama' => '#96ceb4'
    ];
@endphp

<style>
    .analisis-wrap { background: #f3f7ff; border-radius: 18px; padding: 18px; }
    .analisis-head { background: #1a5bb8; color: white; padding: 22px 24px; border-radius: 22px; margin-bottom: 14px; }
    .analisis-filter { background: white; border-radius: 16px; padding: 14px; margin-bottom: 14px; box-shadow: 0 6px 18px rgba(0,0,0,.06); }
    .stat-card { background: white; border-radius: 16px; padding: 18px; margin-bottom: 14px; box-shadow: 0 6px 18px rgba(0,0,0,.06); }
    .chart-container { background: white; border-radius: 16px; padding: 16px; margin-bottom: 14px; box-shadow: 0 6px 18px rgba(0,0,0,.06); min-height: 320px; }
    .analisis-table th { background: #f6f9ff; color: #1f2937; font-weight: 900; }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #1a5bb8;
        line-height: 1;
    }
    .stat-label {
        font-size: 14px;
        color: #6c757d;
        margin-top: 4px;
    }
    
    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a5bb8;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .modus-table {
        font-size: 13px;
    }
    .modus-table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    .badge-level {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 60px 20px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,.06);
    }
    .empty-state i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 16px;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }
    
    .chart-container-large {
        height: 400px;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .filter-col {
        flex: 1;
        min-width: 200px;
    }


    .table-responsive {
    overflow-x: auto;   /* sudah ada, tinggal pastikan */
    -webkit-overflow-scrolling: touch;
}
.table-card {
    background: white;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,.06);
}

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ $tittle }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Analisis</a></li>
                        <li class="breadcrumb-item active">{{ $tittle }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="analisis-wrap">
        <!-- Header -->
        <div class="analisis-head">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="ri-pie-chart-2-line"></i> {{ $tittle }}
            </h5>
            <div class="meta mt-2">Analisis komprehensif hasil instrumen berdasarkan berbagai kriteria</div>
        </div>

        <!-- Filter -->
        <div class="analisis-filter">
            <form action="{{ route('analisis.index') }}" method="GET" id="analisisForm">
                <div class="filter-row">
                    <div class="filter-col">
                        <label class="form-label">Kegiatan</label>
                        <select class="form-select" name="kegiatan_id" id="kegiatanSelect">
                            <option value="">Semua Kegiatan</option>
                            @foreach($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->kegiatan_id }}"
                                    {{ request('kegiatan_id') == $kegiatan->kegiatan_id ? 'selected' : '' }}>
                                    {{ $kegiatan->kegiatan_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label class="form-label">Jenjang Jabatan</label>
                        <select class="form-select" name="pangkat_jabatan_id" id="pangkatSelect">
                            <option value="">Semua Jenjang</option>
                            @foreach($pangkatJabatans as $pangkat)
                                <option value="{{ $pangkat->pangkat_jabatan_id }}"
                                    {{ request('pangkat_jabatan_id') == $pangkat->pangkat_jabatan_id ? 'selected' : '' }}>
                                    {{ $pangkat->jenjang_jabatan ?? $pangkat->pangkat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label class="form-label">Jenis PTK</label>
                        <select class="form-select" name="jenis_ptk_id" id="jenisPtkSelect">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisPtkList as $jenis)
                                <option value="{{ $jenis->jenis_ptk_id }}"
                                    {{ request('jenis_ptk_id') == $jenis->jenis_ptk_id ? 'selected' : '' }}>
                                    {{ $jenis->jenis_ptk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label class="form-label">Kota</label>
                        <select class="form-select" name="kota_id" id="kotaSelect">
                            <option value="">Semua Kota</option>
                            @foreach($kotas as $kota)
                                <option value="{{ $kota->kota_id }}"
                                    {{ request('kota_id') == $kota->kota_id ? 'selected' : '' }}>
                                    {{ $kota->nama_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label class="form-label">Jenjang Pendidikan</label>
                        <select class="form-select" name="bentuk_pendidikan" id="bentukPendidikanSelect">
                            <option value="">Semua Jenjang</option>
                            @foreach($bentukPendidikanList as $bentuk)
                                <option value="{{ $bentuk->bentuk_pendidikan }}"
                                    {{ request('bentuk_pendidikan') == $bentuk->bentuk_pendidikan ? 'selected' : '' }}>
                                    {{ $bentuk->bentuk_pendidikan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin" id="jenisKelaminSelect">
                            <option value="">Semua</option>
                            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="filter-col d-flex gap-2 align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-grow-1" id="btnFilter">
                                <i class="ri-filter-line align-bottom me-1"></i> Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btnReset">
                                <i class="ri-refresh-line align-bottom"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data analisis...</p>
        </div>

        <!-- Error Alert -->
        <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

        <!-- Analisis Content -->
        <div id="analisisContent">
            @if(isset($analisisData) && !isset($analisisData['error']))
                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(26,91,184,.12); color: #1a5bb8;">
                                <i class="ri-user-3-line fs-4"></i>
                            </div>
                            <div class="stat-number">{{ $analisisData['statistik']['total_ptk'] ?? 0 }}</div>
                            <div class="stat-label">Total PTK (Semua Filter)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(40,167,69,.12); color: #28a745;">
                                <i class="ri-checkbox-circle-line fs-4"></i>
                            </div>
                            <div class="stat-number">{{ $analisisData['statistik']['ptk_menjawab'] ?? 0 }}</div>
                            <div class="stat-label">PTK Menjawab (Kegiatan)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(255,193,7,.12); color: #ffc107;">
                                <i class="ri-bar-chart-line fs-4"></i>
                            </div>
                            <div class="stat-number">{{ number_format($analisisData['statistik']['rata_level'] ?? 0, 2) }}</div>
                            <div class="stat-label">Rata-rata Level</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(220,53,69,.12); color: #dc3545;">
                                <i class="ri-progress-4-line fs-4"></i>
                            </div>
                            <div class="stat-number">{{ $analisisData['statistik']['persentase_isi'] ?? 0 }}%</div>
                            <div class="stat-label">Progress Pengisian</div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 1: Distribusi Berdasarkan Jawaban -->
                <div class="row">
                    <!-- Chart 1: Distribusi Level Kompetensi -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <div class="chart-title">
                                <i class="ri-bar-chart-2-line"></i> Distribusi Level Kompetensi
                                <small class="text-muted ms-2">(Berdasarkan jumlah jawaban)</small>
                            </div>
                            <canvas id="levelDistributionChart" height="300"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Distribusi Jenjang Jabatan -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <div class="chart-title">
                                <i class="ri-pie-chart-line"></i> Distribusi Jenjang Jabatan
                                <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                            </div>
                            <canvas id="jenjangDistributionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2: Distribusi Berdasarkan PTK -->
                <div class="row">
                    <!-- Chart 3: Distribusi Jenjang Pendidikan -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <div class="chart-title">
                                <i class="ri-school-line"></i> Distribusi Jenjang Pendidikan
                                <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                            </div>
                            <canvas id="bentukPendidikanChart" height="300"></canvas>
                        </div>
                    </div>

                    <!-- Chart 4: Distribusi Jenis Kelamin -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <div class="chart-title">
                                <i class="ri-user-line"></i> Distribusi Jenis Kelamin
                                <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                            </div>
                            <canvas id="jenisKelaminChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart 5: Semua Sub Indikator -->
                @if(!empty($analisisData['all_sub_indikators_chart']['labels']))
                <div class="row">
                    <div class="col-12">
                        <div class="chart-container chart-container-large">
                            <div class="chart-title">
                                <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator
                                <span class="badge bg-info ms-2">Jumlah PTK (Bukan Jawaban)</span>
                            </div>
                            <canvas id="allSubIndikatorsChart" height="400"></canvas>
                        </div>
                    </div>
                </div>
                @endif




               <!-- Ganti bagian Charts 6: Sub Indikator per Jenjang Jabatan dengan ini -->
@if(!empty($analisisData['sub_indikator_per_jenjang']))
<div class="row mt-4">
    <div class="col-12">
        <div class="chart-container">
            <div class="chart-title">
                <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator per Jenjang Jabatan
                <span class="badge bg-info ms-2">Jumlah PTK per Jenjang</span>
            </div>
            
            <div class="row" id="jenjangChartsContainer">
                @foreach($analisisData['sub_indikator_per_jenjang'] as $jenjangChart)
                <div class="col-md-6 mb-4">
                    <div class="chart-card" style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,.05); height: 400px;">
                        <h6 class="mb-3 text-center" style="color: #1a5bb8; font-weight: 600; font-size: 16px;">
                            <i class="ri-user-star-line me-2"></i>{{ $jenjangChart['jenjang_jabatan'] }}
                        </h6>
                        <div class="chart-wrapper" style="height: 320px;">
                            <canvas id="jenjangChart_{{ $loop->index }}" height="320"></canvas>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
                <!-- Tabel Modus per Kota -->
                @if(!empty($analisisData['modus_per_kota']))
                <div class="row">
                    <div class="col-12">
                       <div class="table-card">
                            <div class="chart-title">
                                <i class="ri-map-pin-line"></i> Modus Level per Kota
                                <small class="text-muted ms-2">(Berdasarkan jumlah PTK)</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered modus-table">
                                    <thead>
                                        <tr>
                                            <th>Kota</th>
                                            <th>Total Jawab Sub Indikator</th>
                                            <th>Sub Indikator</th>
                                            <th>Modus Level</th>
                                            <th>Jumlah PTK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($analisisData['modus_per_kota'] as $kota)
                                            @if(!empty($kota['sub_indikator_modus']))
                                                @foreach($kota['sub_indikator_modus'] as $index => $sub)
                                                    <tr>
                                                        @if($index === 0)
                                                            <td rowspan="{{ count($kota['sub_indikator_modus']) }}" style="vertical-align: middle; font-weight: 600;">
                                                                {{ $kota['nama_kota'] }}
                                                            </td>
                                                            <td rowspan="{{ count($kota['sub_indikator_modus']) }}" style="vertical-align: middle; text-align: center;">
                                                                {{ $kota['total_jawaban'] }}
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <small class="text-muted">{{ $sub['sub_indikator_code'] }}</small><br>
                                                            <span class="fw-medium">{{ Str::limit($sub['sub_indikator_name'], 40) }}</span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $levelColor = $levelColors[$sub['modus_level'] ?? 2] ?? '#17a2b8';
                                                                $levelName = $levelNames[$sub['modus_level'] ?? 2] ?? 'Penerapan';
                                                            @endphp
                                                            <span class="badge-level" style="background-color: {{ $levelColor }}; color: white;">
                                                                Level {{ $sub['modus_level'] }} ({{ $levelName }})
                                                            </span>
                                                        </td>
                                                        <td style="text-align: center;">{{ $sub['jumlah_jawaban'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tabel Progress Kota -->
                @if(!empty($analisisData['progress_kota']))
                <div class="row">
                    <div class="col-12">
                   <div class="table-card">

                            <div class="chart-title">
                                <i class="ri-progress-3-line"></i> Progress Pengisian per Kota
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered modus-table">
                                    <thead>
                                        <tr>
                                            <th>Kota</th>
                                            <th>Total PTK</th>
                                            <th>Sudah Isi</th>
                                            <th>Persentase</th>
                                            <th>Progress Bar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($analisisData['progress_kota'] as $kota)
                                            <tr>
                                                <td>{{ $kota->nama_kota }}</td>
                                                <td style="text-align: center;">{{ $kota->total_ptk }}</td>
                                                <td style="text-align: center;">{{ $kota->sudah_isi }}</td>
                                                <td style="text-align: center; font-weight: 600;">{{ $kota->persentase }}%</td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar 
                                                            @if($kota->persentase >= 80) bg-success
                                                            @elseif($kota->persentase >= 50) bg-warning
                                                            @else bg-danger
                                                            @endif" 
                                                            role="progressbar" 
                                                            style="width: {{ $kota->persentase }}%;">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="empty-state text-center py-5">
                    <i class="ri-bar-chart-box-line display-4 text-muted mb-3"></i>
                    <p class="text-muted">Silakan pilih filter untuk melihat analisis data</p>
                    <button class="btn btn-primary mt-3" onclick="document.getElementById('analisisForm').submit()">
                        <i class="ri-filter-line align-bottom me-1"></i> Filter Data
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('sipproja-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* =====================================================
   GLOBAL CHART INSTANCE
===================================================== */
let levelDistributionChart = null;
let jenjangDistributionChart = null;
let bentukPendidikanChart = null;
let jenisKelaminChart = null;
let allSubIndikatorsChart = null;

/* =====================================================
   RESET FORM
===================================================== */
document.getElementById('btnReset')?.addEventListener('click', function () {
    document.getElementById('kegiatanSelect').value = '';
    document.getElementById('pangkatSelect').value = '';
    document.getElementById('jenisPtkSelect').value = '';
    document.getElementById('kotaSelect').value = '';
    document.getElementById('bentukPendidikanSelect').value = '';
    document.getElementById('jenisKelaminSelect').value = '';
    document.getElementById('analisisForm').submit();
});

/* =====================================================
   FORM SUBMIT AJAX
===================================================== */
document.getElementById('analisisForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    loadAnalisis();
});

function loadAnalisis() {
    const form = document.getElementById('analisisForm');
    const params = new URLSearchParams(new FormData(form));

    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('analisisContent').style.display = 'none';
    document.getElementById('errorAlert').style.display = 'none';

    fetch(`{{ route('analisis.index') }}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal memuat data');
        return res.json();
    })
    .then(data => {
        document.getElementById('loadingSpinner').style.display = 'none';

        if (data.error) {
            document.getElementById('errorAlert').innerText = data.error;
            document.getElementById('errorAlert').style.display = 'block';
            return;
        }

        updateAnalisisContent(data);
    })
    .catch(err => {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('errorAlert').innerText = err.message;
        document.getElementById('errorAlert').style.display = 'block';
    });
}

/* =====================================================
   UPDATE HTML CONTENT
===================================================== */
function updateAnalisisContent(data) {
    let html = `
    <div class="row">
        ${statCard('ri-user-3-line','Total PTK<br><small>Semua Filter</small>',data.statistik?.total_ptk ?? 0,'#1a5bb8')}
        ${statCard('ri-checkbox-circle-line','PTK Menjawab<br><small>Kegiatan</small>',data.statistik?.ptk_menjawab ?? 0,'#28a745')}
        ${statCard('ri-bar-chart-line','Rata-rata Level',Number(data.statistik?.rata_level ?? 0).toFixed(2),'#ffc107')}
        ${statCard('ri-progress-4-line','Progress<br>Pengisian',(data.statistik?.persentase_isi ?? 0)+'%','#dc3545')}
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-bar-chart-2-line"></i> Distribusi Level Kompetensi <small class="text-muted">(Jumlah jawaban)</small></div>
                <canvas id="levelDistributionChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-pie-chart-line"></i> Distribusi Jenjang Jabatan <small class="text-muted">(PTK yang menjawab)</small></div>
                <canvas id="jenjangDistributionChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-school-line"></i> Distribusi Jenjang Pendidikan <small class="text-muted">(PTK yang menjawab)</small></div>
                <canvas id="bentukPendidikanChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-user-line"></i> Distribusi Jenis Kelamin <small class="text-muted">(PTK yang menjawab)</small></div>
                <canvas id="jenisKelaminChart" height="300"></canvas>
            </div>
        </div>
    </div>`;

    // Tambahkan chart sub indikator jika ada data
    if (data.all_sub_indikators_chart?.labels?.length > 0) {
        html += `
        <div class="row">
            <div class="col-12">
                <div class="chart-container chart-container-large">
                    <div class="chart-title">
                        <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator
                        <span class="badge bg-info ms-2">Jumlah PTK</span>
                    </div>
                    <canvas id="allSubIndikatorsChart" height="400"></canvas>
                </div>
            </div>
        </div>`;
    }

    // Tambahkan charts per jenjang jabatan jika ada
    if (data.sub_indikator_per_jenjang?.length > 0) {
        html += `
        <div class="row mt-4">
            <div class="col-12">
                <div class="chart-container chart-container-large">
                    <div class="chart-title">
                        <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator per Jenjang Jabatan
                        <span class="badge bg-info ms-2">Jumlah PTK per Jenjang</span>
                    </div>
                    
                    <div class="row" id="jenjangChartsContainer">`;
        
        data.sub_indikator_per_jenjang.forEach((jenjangData, index) => {
            html += `
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="chart-card" style="background: white; border-radius: 12px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,.05); height: 320px;">
                                <h6 class="mb-3 text-center" style="color: #1a5bb8; font-weight: 600;">
                                    <i class="ri-user-star-line me-2"></i>${jenjangData.jenjang_jabatan}
                                </h6>
                                <canvas 
                                    id="jenjangChart_${index}" 
                                    height="250">
                                </canvas>
                            </div>
                        </div>`;
        });
        
        html += `
                    </div>
                </div>
            </div>
        </div>`;
    }

    // Tambahkan tabel modus per kota jika ada
    if (data.modus_per_kota?.length > 0) {
        let modusRows = '';
        data.modus_per_kota.forEach((kota, kotaIndex) => {
            if (kota.sub_indikator_modus && kota.sub_indikator_modus.length > 0) {
                kota.sub_indikator_modus.forEach((sub, subIndex) => {
                    modusRows += `
                    <tr>
                        ${subIndex === 0 ? `
                            <td rowspan="${kota.sub_indikator_modus.length}" style="vertical-align: middle; font-weight: 600;">
                                ${kota.nama_kota}
                            </td>
                            <td rowspan="${kota.sub_indikator_modus.length}" style="vertical-align: middle; text-align: center;">
                                ${kota.total_jawaban}
                            </td>
                        ` : ''}
                        <td>
                            <small class="text-muted">${sub.sub_indikator_code}</small><br>
                            <span class="fw-medium">${sub.sub_indikator_name ? sub.sub_indikator_name.substring(0, 40) + (sub.sub_indikator_name.length > 40 ? '...' : '') : '-'}</span>
                        </td>
                        <td>
                            <span class="badge-level" style="background-color: ${getLevelColor(sub.modus_level)}; color: white;">
                                Level ${sub.modus_level} (${getLevelName(sub.modus_level)})
                            </span>
                        </td>
                        <td style="text-align: center;">${sub.jumlah_jawaban}</td>
                    </tr>`;
                });
            }
        });
        
        html += `
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="chart-title">
                        <i class="ri-map-pin-line"></i> Modus Level per Kota <small class="text-muted">(Jumlah PTK)</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered modus-table">
                            <thead>
                                <tr>
                                    <th>Kota</th>
                                    <th>Total Jawaban Sub Indikator</th>
                                    <th>Sub Indikator</th>
                                    <th>Modus Level</th>
                                    <th>Jumlah PTK</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${modusRows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>`;
    }

    // Tambahkan tabel progress kota jika ada
    if (data.progress_kota?.length > 0) {
        let progressRows = '';
        data.progress_kota.forEach(kota => {
            const progressClass = kota.persentase >= 80 ? 'bg-success' : 
                                kota.persentase >= 50 ? 'bg-warning' : 'bg-danger';
            
            progressRows += `
            <tr>
                <td>${kota.nama_kota}</td>
                <td style="text-align: center;">${kota.total_ptk}</td>
                <td style="text-align: center;">${kota.sudah_isi}</td>
                <td style="text-align: center; font-weight: 600;">${kota.persentase}%</td>
                <td>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar ${progressClass}" 
                             role="progressbar" 
                             style="width: ${kota.persentase}%;">
                        </div>
                    </div>
                </td>
            </tr>`;
        });
        
        html += `
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="chart-title">
                        <i class="ri-progress-3-line"></i> Progress Pengisian per Kota
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered modus-table">
                            <thead>
                                <tr>
                                    <th>Kota</th>
                                    <th>Total PTK</th>
                                    <th>Sudah Isi</th>
                                    <th>Persentase</th>
                                    <th>Progress Bar</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${progressRows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>`;
    }

    document.getElementById('analisisContent').innerHTML = html;
    document.getElementById('analisisContent').style.display = 'block';

    setTimeout(() => renderCharts(data), 100);
}

/* =====================================================
   STAT CARD HELPER
===================================================== */
function statCard(icon,label,value,color){
    return `
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:${color}22;color:${color}">
                <i class="${icon} fs-4"></i>
            </div>
            <div class="stat-number">${value}</div>
            <div class="stat-label">${label}</div>
        </div>
    </div>`;
}

/* =====================================================
   HELPER FUNCTIONS
===================================================== */
function getLevelColor(level) {
    const colors = {
        2: '#17a2b8',
        3: '#007bff',
        4: '#ffc107',
        5: '#28a745'
    };
    return colors[level] || '#17a2b8';
}

function getLevelName(level) {
    const names = {
        2: 'Penerapan',
        3: 'Analisis',
        4: 'Evaluasi',
        5: 'Pembimbingan'
    };
    return names[level] || 'Penerapan';
}

/* =====================================================
   RENDER ALL CHARTS
===================================================== */
function renderCharts(data) {
    console.log('Rendering charts with data:', data);

    // Destroy existing charts
    [levelDistributionChart, jenjangDistributionChart, bentukPendidikanChart, jenisKelaminChart, allSubIndikatorsChart].forEach(chart => {
        if (chart) {
            try {
                chart.destroy();
            } catch(e) {
                console.log('Error destroying chart:', e);
            }
        }
    });

    /* ================= LEVEL DISTRIBUTION (BERDASARKAN JAWABAN) ================= */
    const levelCtx = document.getElementById('levelDistributionChart')?.getContext('2d');
    if (levelCtx) {
        const src = Array.isArray(data.level_distribution) ? data.level_distribution : [];
        const get = l => src.find(x => x.level === l)?.count ?? 0;

        levelDistributionChart = new Chart(levelCtx, {
            type: 'bar',
            data: {
                labels: ['Level 2','Level 3','Level 4','Level 5'],
                datasets: [{
                    label: 'Jumlah Jawaban',
                    data: [get(2),get(3),get(4),get(5)],
                    backgroundColor: ['#17a2b8','#007bff','#ffc107','#28a745'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    /* ================= JENJANG DISTRIBUTION (BERDASARKAN PTK) ================= */
    const jenjangCtx = document.getElementById('jenjangDistributionChart')?.getContext('2d');
    if (jenjangCtx) {
        const src = data.jenjang_distribution?.length
            ? data.jenjang_distribution
            : [{ jenjang_jabatan:'Tidak Ada Data', count:0 }];

        jenjangDistributionChart = new Chart(jenjangCtx, {
            type: 'doughnut',
            data: {
                labels: src.map(x => x.jenjang_jabatan),
                datasets: [{
                    data: src.map(x => x.count),
                    backgroundColor: ['#ff6b6b','#4ecdc4','#45b7d1','#96ceb4','#feca57','#5f27cd']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });
    }

    /* ================= BENTUK PENDIDIKAN DISTRIBUTION (BERDASARKAN PTK) ================= */
    const bentukPendidikanCtx = document.getElementById('bentukPendidikanChart')?.getContext('2d');
    if (bentukPendidikanCtx) {
        const src = data.bentuk_pendidikan_distribution?.length
            ? data.bentuk_pendidikan_distribution
            : [{ bentuk_pendidikan:'Tidak Ada Data', count:0 }];

        bentukPendidikanChart = new Chart(bentukPendidikanCtx, {
            type: 'pie',
            data: {
                labels: src.map(x => x.bentuk_pendidikan),
                datasets: [{
                    data: src.map(x => x.count),
                    backgroundColor: ['#36a2eb','#ff6384','#4bc0c0','#ff9f40','#9966ff','#ffcd56']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });
    }

    /* ================= JENIS KELAMIN DISTRIBUTION (BERDASARKAN PTK) ================= */
    const jenisKelaminCtx = document.getElementById('jenisKelaminChart')?.getContext('2d');
    if (jenisKelaminCtx) {
        const src = data.jenis_kelamin_distribution?.length
            ? data.jenis_kelamin_distribution
            : [{ jenis_kelamin:'Tidak Ada Data', count:0 }];

        jenisKelaminChart = new Chart(jenisKelaminCtx, {
            type: 'doughnut',
            data: {
                labels: src.map(x => x.jenis_kelamin),
                datasets: [{
                    data: src.map(x => x.count),
                    backgroundColor: ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });
    }

    /* ================= ALL SUB INDIKATOR (BERDASARKAN PTK) ================= */
    const allSubCtx = document.getElementById('allSubIndikatorsChart')?.getContext('2d');
    if (allSubCtx && data.all_sub_indikators_chart) {
        const chartData = data.all_sub_indikators_chart;
        console.log('All sub indicators chart data:', chartData);

        if (chartData.labels?.length > 0 && chartData.datasets?.length > 0) {
            allSubIndikatorsChart = new Chart(allSubCtx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.raw;
                                    return `${label}: ${value} PTK`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Sub Indikator'
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah PTK'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        } else {
            console.log('Chart data kosong, tidak membuat chart');
            const container = allSubCtx.canvas.parentElement;
            if (container) {
                container.innerHTML += `
                    <div class="alert alert-info mt-3">
                        <i class="ri-information-line"></i>
                        Tidak ada data untuk grafik sub indikator
                    </div>
                `;
            }
        }
    }

    /* ================= RENDER JENJANG CHARTS ================= */
    renderJenjangCharts(data);
}

/* =====================================================
   RENDER JENJANG CHARTS
===================================================== */
function renderJenjangCharts(data) {
    console.log('Rendering jenjang charts:', data?.sub_indikator_per_jenjang);
    
    // Hapus container sebelumnya
    const container = document.getElementById('jenjangChartsContainer');
    if (!container || !data?.sub_indikator_per_jenjang) return;
    
    // Hancurkan chart sebelumnya
    document.querySelectorAll('[id^="jenjangChart_"]').forEach(canvas => {
        const chartId = canvas.id;
        if (window[chartId]) {
            try {
                window[chartId].destroy();
            } catch(e) {
                console.log('Error destroying chart:', e);
            }
        }
    });
    
    // Render chart untuk setiap jenjang
    data.sub_indikator_per_jenjang.forEach((jenjangData, index) => {
        const canvasId = `jenjangChart_${index}`;
        const canvas = document.getElementById(canvasId);
        
        if (!canvas) {
            console.log(`Canvas ${canvasId} tidak ditemukan`);
            return;
        }
        
        // Dapatkan context canvas
        const ctx = canvas.getContext('2d');
        if (!ctx) {
            console.log(`Context tidak ditemukan untuk ${canvasId}`);
            return;
        }
        
        console.log(`Membuat chart untuk ${jenjangData.jenjang_jabatan}`, jenjangData);
        
        // Pastikan datasets ada dan valid
        if (!jenjangData.datasets || !Array.isArray(jenjangData.datasets)) {
            console.log(`Datasets tidak valid untuk ${jenjangData.jenjang_jabatan}`);
            return;
        }
        
        // Buat chart
        window[canvasId] = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: jenjangData.labels || [],
                datasets: jenjangData.datasets.map(dataset => ({
                    label: dataset.label || 'Unknown',
                    data: dataset.data || [],
                    backgroundColor: dataset.backgroundColor || '#17a2b8',
                    borderColor: dataset.borderColor || '#17a2b8',
                    borderWidth: dataset.borderWidth || 1
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 10,
                            usePointStyle: true,
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw} PTK`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 10
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah PTK',
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
        
        console.log(`Chart ${canvasId} berhasil dibuat`);
    });
}

/* =====================================================
   INITIAL LOAD (SSR DATA)
===================================================== */
@if(isset($analisisData) && !isset($analisisData['error']))
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initial load dengan data:', @json($analisisData));
    renderCharts(@json($analisisData));
});
@endif
</script>
@endsection