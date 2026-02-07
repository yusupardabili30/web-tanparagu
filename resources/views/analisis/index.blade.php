@extends('layouts.main')
@section('mycontent')
    @php
        $tittle = 'Analisis Hasil Instrumen';

        $levelNames = [
            1 => 'Dasar',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan',
        ];

        $levelColors = [
            1 => 'rgba(220, 53, 69, 0.8)',
            2 => '#17a2b8',
            3 => '#007bff',
            4 => '#ffc107',
            5 => '#28a745',
        ];

        $jenjangColors = [
            'Pertama' => '#ff6b6b',
            'Muda' => '#4ecdc4',
            'Madya' => '#45b7d1',
            'Utama' => '#96ceb4',
        ];

        $getLevelColor = function ($level) use ($levelColors) {
            return $levelColors[$level] ?? '#17a2b8';
        };

        $getLevelName = function ($level) use ($levelNames) {
            return $levelNames[$level] ?? 'Penerapan';
        };
    @endphp

    <link rel="stylesheet" href="{{ asset('build/css/analisis.min.css') }}">

    <!-- FLOATING NAVBAR + SLIDE PANEL (KIRI, SETENGAH OVAL) -->
    <div id="floatingNavAnalisis">
        <button id="toggleFloatingAnalisis" class="floating-toggle-analisis half-oval-btn-analisis" type="button"
            aria-label="Buka daftar grafik/tabel">
            <i class="ri-arrow-right-s-line"></i>
        </button>
    </div>

    <div id="floatingBackdropAnalisis" class="floating-backdrop-analisis" hidden></div>

    <div id="floatingPanelAnalisis" class="floating-panel-analisis" aria-hidden="true">
        <button class="close-panel-btn-analisis" id="closePanelBtnAnalisis" type="button" aria-label="Tutup">
            <i class="ri-close-line"></i>
        </button>

        <h4 class="floating-title-analisis">
            <i class="ri-stack-line me-1"></i> Daftar Grafik & Tabel
        </h4>

        <div id="navListAnalisis"></div>
    </div>

    <div class="container-fluid">
        {{-- PAGE TITLE --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-justify-content-between">
                    <h4 class="mb-sm-0"></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Analisis<a></li>
                            <li class="breadcrumb-item active">{{ $tittle }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="analisis-wrap">
            {{-- HEADER --}}
            <div class="analisis-head">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="ri-pie-chart-2-line"></i> {{ $tittle }}
                </h5>
                <div class="meta mt-2">Analisis komprehensif hasil instrumen berdasarkan berbagai kriteria</div>
            </div>

            {{-- FILTER --}}
            <div class="analisis-filter">
                <form action="{{ route('analisis.index') }}" method="GET" id="analisisForm">
                    <div class="filter-row">
                        <div class="filter-col">
                            <label class="form-label">Kegiatan</label>
                            <select class="form-select" name="kegiatan_id" id="kegiatanSelect">
                                <option value="">Semua Kegiatan</option>
                                @foreach ($kegiatans as $kegiatan)
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
                                @foreach ($pangkatJabatans as $pangkat)
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
                                <option value="">Semua Jenis PTK</option>
                                @foreach ($jenisPtkList as $jenis)
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
                                @foreach ($kotas as $kota)
                                    <option value="{{ $kota->kota_id }}"
                                        {{ request('kota_id') == $kota->kota_id ? 'selected' : '' }}>
                                        {{ $kota->nama_kota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-col">
                            <label class="form-label">Jenjang Pendidikan</label>
                            <select class="form-select" name="jenjang_pendidikan_id" id="jenjangPendidikanSelect">
                                <option value="">Semua Jenjang</option>
                                @foreach ($jenjangPendidikanList as $jenjang)
                                    <option value="{{ $jenjang->jenjang_pendidikan_id }}"
                                        {{ request('jenjang_pendidikan_id') == $jenjang->jenjang_pendidikan_id ? 'selected' : '' }}>
                                        {{ $jenjang->jenjang_pendidikan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-col">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" id="jenisKelaminSelect">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
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

                                <button type="button" class="btn btn-success" id="btnExportLengkap">
                                    <i class="ri-file-excel-2-line align-bottom me-1"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-success" id="btnExportRekomendasi">
                                    <i class="ri-file-excel-2-line align-bottom me-1"></i> Export Rekomendasi KB
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- {{-- LOADING --}} -->
            <div id="loadingSpinner" class="text-center py-5" style="display:none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data analisis...</p>
            </div>

            <!-- {{-- ERROR --}} -->
            <div id="errorAlert" class="alert alert-danger" style="display:none;"></div>

            <!-- {{-- CONTENT --}} -->
            <div id="analisisContent">
                @if (isset($analisisData) && !isset($analisisData['error']))
                    {{-- ROW 1: STAT CARDS (WAJIB jadi row pertama biar CSS nth-of-type aman) --}}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(26,91,184,.12); color:#1a5bb8;">
                                    <i class="ri-user-3-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['statistik']['total_ptk'] ?? 0 }}</div>
                                <div class="stat-label">Total PTK<br><small>Semua Filter</small></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(40,167,69,.12); color:#28a745;">
                                    <i class="ri-checkbox-circle-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['statistik']['ptk_menjawab'] ?? 0 }}</div>
                                <div class="stat-label">PTK Menjawab<br><small>Kegiatan</small></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(220,53,69,.12); color:#dc3545;">
                                    <i class="ri-user-forbid-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['statistik']['ptk_belum_menjawab'] ?? 0 }}</div>
                                <div class="stat-label">PTK Belum<br>Menjawab</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(220,53,69,.12); color:#dc3545;">
                                    <i class="ri-progress-4-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['statistik']['persentase_isi'] ?? 0 }}%</div>
                                <div class="stat-label">Progress<br>Pengisian</div>
                            </div>
                        </div>
                    </div>

                    <!-- {{-- ROW 2: PROVINSI (WAJIB row ke-2 biar CSS float 2 kolom jalan) --}} -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container" id="sec-level-ptk">
                                <div class="chart-title">
                                    <i class="ri-arrow-down-line"></i> Distribusi Level PTK Per Provinsi
                                    <small class="text-muted ms-2">(Level terendah yang dicapai PTK di semua sub
                                        indikator)</small>
                                </div>
                                <canvas id="levelTerendahChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- {{-- ROW 3: KAB/KOTA (WAJIB row ke-3 biar CSS float 2 kolom jalan) --}} -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-title">
                                    <i class="ri-arrow-down-line"></i> Distribusi Level PTK Per Kab/Kota
                                    <small class="text-muted ms-2">(Level terendah yang dicapai PTK di semua sub
                                        indikator)</small>
                                </div>
                                <canvas id="levelTerendahkabkotaChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- {{-- LEVEL PER KOTA --}} -->
                    @if (!empty($analisisData['distribusi_level_per_kota']['labels']))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="chart-container chart-container-large" id="sec-level-kota">
                                    <div class="chart-title">
                                        <i class="ri-map-pin-2-line"></i> Distribusi Level per Kota (Layered Bar)
                                        <span class="badge bg-info ms-2">Jumlah PTK per Level</span>
                                        <small class="text-muted ms-2">
                                            @if (request('kota_id'))
                                                (Filter kota diterapkan, tapi menampilkan semua kota untuk perbandingan)
                                            @else
                                                (Menampilkan seluruh kota)
                                            @endif
                                        </small>
                                    </div>
                                    <canvas id="levelPerKotaChart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- {{-- DOUGHNUT: JABATAN + PENDIDIKAN --}} -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container" id="sec-jenjang">
                                <div class="chart-title">
                                    <i class="ri-pie-chart-line"></i> Distribusi Jenjang Jabatan
                                    <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                                </div>
                                <div class="chart-canvas-wrap is-doughnut">
                                    <canvas id="jenjangDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-title">
                                    <i class="ri-school-line"></i> Distribusi Jenjang Pendidikan
                                    <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                                </div>
                                <div class="chart-canvas-wrap is-doughnut">
                                    <canvas id="jenjangPendidikanChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- {{-- DOUGHNUT: KELAMIN --}} -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-title">
                                    <i class="ri-user-line"></i> Distribusi Jenis Kelamin
                                    <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                                </div>
                                <div class="chart-canvas-wrap is-doughnut">
                                    <canvas id="jenisKelaminChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- {{-- ALL SUB INDIKATOR --}} -->
                    @if (!empty($analisisData['all_sub_indikators_chart']['labels']))
                        <div class="row">
                            <div class="col-12">
                                <div class="chart-container chart-container-large" id="sec-sub-indikator">
                                    <div class="chart-title">
                                        <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator
                                        <span class="badge bg-info ms-2">Jumlah PTK (Bukan Jawaban)</span>
                                    </div>
                                    <canvas id="allSubIndikatorsChart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- {{-- MODUS --}} -->
                    @if (!empty($analisisData['modus_per_kota']))
                        <div class="row">
                            <div class="col-12">
                                <div class="table-card" id="sec-modus">
                                    <div class="chart-title">
                                        <i class="ri-map-pin-line"></i>
                                        @if (request('kota_id'))
                                            Modus Level per Kota
                                        @else
                                            Modus Level Provinsi Banten
                                        @endif
                                        <small class="text-muted ms-2">(Berdasarkan jumlah PTK)</small>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered modus-table">
                                            <thead>
                                                <tr>
                                                    @if (request('kota_id'))
                                                        <th>Kota</th>
                                                        <th>Total Jawab Sub Indikator</th>
                                                    @else
                                                        <th>Provinsi</th>
                                                        <th>Total Jawab Sub Indikator</th>
                                                    @endif
                                                    <th>Sub Indikator</th>
                                                    <th>Modus Level</th>
                                                    <th>Jumlah PTK</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($analisisData['modus_per_kota'] as $kota)
                                                    @if (!empty($kota['sub_indikator_modus']))
                                                        @foreach ($kota['sub_indikator_modus'] as $index => $sub)
                                                            @php
                                                                $lc =
                                                                    $levelColors[$sub['modus_level'] ?? 2] ?? '#17a2b8';
                                                                $ln =
                                                                    $levelNames[$sub['modus_level'] ?? 2] ??
                                                                    'Penerapan';
                                                            @endphp
                                                            <tr>
                                                                @if ($index === 0)
                                                                    @if (request('kota_id'))
                                                                        <td rowspan="{{ count($kota['sub_indikator_modus']) }}"
                                                                            style="vertical-align: middle; font-weight:600;">
                                                                            {{ $kota['nama_kota'] }}
                                                                        </td>
                                                                    @else
                                                                        <td rowspan="{{ count($kota['sub_indikator_modus']) }}"
                                                                            style="vertical-align: middle; font-weight:600;">
                                                                            Banten
                                                                        </td>
                                                                    @endif

                                                                    <td rowspan="{{ count($kota['sub_indikator_modus']) }}"
                                                                        style="vertical-align: middle; text-align:center;">
                                                                        {{ $kota['total_jawaban'] }}
                                                                    </td>
                                                                @endif

                                                                <td>
                                                                    <small
                                                                        class="text-muted">{{ $sub['sub_indikator_code'] }}</small><br>
                                                                    <span
                                                                        class="fw-medium">{{ $sub['sub_indikator_name'] }}</span>
                                                                </td>

                                                                <td>
                                                                    <span class="badge-level"
                                                                        style="background-color:{{ $lc }}; color:#fff;">
                                                                        Level {{ $sub['modus_level'] }}
                                                                        ({{ $ln }})
                                                                    </span>
                                                                </td>

                                                                <td class="text-center">{{ $sub['jumlah_jawaban'] }}</td>
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

                    <!-- {{-- CHARTS PER JENJANG --}} -->
                    @if (!empty($analisisData['sub_indikator_per_jenjang']))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="chart-container" id="sec-sub-jenjang">
                                    <div class="chart-title">
                                        <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator per
                                        Jenjang Jabatan
                                        <span class="badge bg-info ms-2">Jumlah PTK per Jenjang</span>
                                    </div>

                                    <div class="jenjang-charts-scroll-container">
                                        <div class="row" id="jenjangChartsContainer">
                                            @foreach ($analisisData['sub_indikator_per_jenjang'] as $jenjangChart)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="chart-card">
                                                        <h6 class="mb-3 text-center"
                                                            style="color:#1a5bb8;font-weight:600;font-size:16px;">
                                                            <i
                                                                class="ri-user-star-line me-2"></i>{{ $jenjangChart['jenjang_jabatan'] }}
                                                        </h6>
                                                        <div class="chart-wrapper">
                                                            <canvas id="jenjangChart_{{ $loop->index }}"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="ri-information-line"></i>
                                            Menampilkan {{ count($analisisData['sub_indikator_per_jenjang']) }} jenjang
                                            jabatan
                                            @if (count($analisisData['sub_indikator_per_jenjang']) > 6)
                                                (Gunakan scroll untuk melihat lebih banyak)
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- {{-- TAB GAP --}} -->
                    @if (!empty($analisisData['rekomendasi_gap_per_jenjang']))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="table-card" id="sec-gap">
                                    <div class="chart-title">
                                        <i class="ri-list-check-2"></i> Rekomendasi Kebutuhan Belajar per Jenjang Jabatan
                                        <small class="text-muted ms-2">(Hanya menampilkan sub indikator yang belum mencapai
                                            level kompetensi)</small>
                                    </div>

                                    <div class="mb-4">
                                        <ul class="nav nav-tabs" id="jenjangTab" role="tablist">
                                            @foreach ($analisisData['rekomendasi_gap_per_jenjang'] as $idx => $jenjang)
                                                @php $slug = \Illuminate\Support\Str::slug($jenjang['jenjang_jabatan'] ?? 'jenjang'); @endphp
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ $idx == 0 ? 'active' : '' }}"
                                                        id="tab-{{ $slug }}" data-bs-toggle="tab"
                                                        data-bs-target="#content-{{ $slug }}" type="button"
                                                        role="tab">
                                                        {{ $jenjang['jenjang_jabatan'] }}
                                                        <span
                                                            class="badge bg-danger ms-1">{{ count($jenjang['rekomendasi'] ?? []) }}</span>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="tab-content" id="jenjangTabContent">
                                        @foreach ($analisisData['rekomendasi_gap_per_jenjang'] as $idx => $jenjang)
                                            @php $slug = \Illuminate\Support\Str::slug($jenjang['jenjang_jabatan'] ?? 'jenjang'); @endphp
                                            <div class="tab-pane fade {{ $idx == 0 ? 'show active' : '' }}"
                                                id="content-{{ $slug }}" role="tabpanel">
                                                @if (!empty($jenjang['rekomendasi']))
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered modus-table">
                                                            <thead>
                                                                <tr>
                                                                    <th width="3%">#</th>
                                                                    <th>Sub Indikator</th>
                                                                    <th width="8%" class="text-center">Level Dicapai
                                                                    </th>
                                                                    <th width="8%" class="text-center">Level kebutuhan
                                                                        Belajar</th>
                                                                    <th width="6%" class="text-center">Gap</th>
                                                                    <th>Rekomendasi Kebutuhan Belajar</th>
                                                                    <th width="10%" class="text-center">Jumlah PTK</th>
                                                                    <th width="10%" class="text-center">Detail PTK</th>
                                                                    <th width="12%" class="text-center">% dari Total
                                                                    </th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @php
                                                                    $counter = 1;
                                                                    $jenjangTotalPtk = $jenjang['total_ptk'] ?? 0;
                                                                @endphp

                                                                @foreach ($jenjang['rekomendasi'] as $rek)
                                                                    @if (!empty($rek['detail_gap']))
                                                                        @php
                                                                            $jumlahPtKSubIndikator =
                                                                                $rek['total_ptk_sub_indikator'] ?? 0;
                                                                            $percentage =
                                                                                $jenjangTotalPtk > 0
                                                                                    ? round(
                                                                                        ($jumlahPtKSubIndikator /
                                                                                            $jenjangTotalPtk) *
                                                                                            100,
                                                                                        1,
                                                                                    )
                                                                                    : 0;
                                                                            $progressClass =
                                                                                $percentage >= 30
                                                                                    ? 'bg-danger'
                                                                                    : ($percentage >= 15
                                                                                        ? 'bg-warning'
                                                                                        : 'bg-info');
                                                                        @endphp

                                                                        @foreach ($rek['detail_gap'] as $gapIndex => $gap)
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    {{ $counter++ }}</td>

                                                                                <td>
                                                                                    @if ($gapIndex === 0)
                                                                                        <small
                                                                                            class="text-muted">{{ $rek['sub_indikator_code'] }}</small><br>
                                                                                        <span
                                                                                            class="fw-medium">{{ $rek['sub_indikator_name'] }}</span>
                                                                                    @endif
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    <span class="badge-level"
                                                                                        style="background-color: {{ $getLevelColor($gap['level_dicapai']) }}; color:#fff;">
                                                                                        Level {{ $gap['level_dicapai'] }}
                                                                                    </span>
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    <span class="badge-level"
                                                                                        style="background-color: {{ $getLevelColor($gap['level_harus']) }}; color:#fff;">
                                                                                        Level {{ $gap['level_harus'] }}
                                                                                    </span>
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    @php $gapLevel = $gap['level_gap'] ?? 0; @endphp
                                                                                    @if ($gapLevel > 0)
                                                                                        <span
                                                                                            class="badge bg-danger">+{{ $gapLevel }}</span>
                                                                                    @else
                                                                                        <span
                                                                                            class="badge bg-success">0</span>
                                                                                    @endif
                                                                                </td>

                                                                                <td><small>{{ $gap['rekomendasi'] }}</small>
                                                                                </td>

                                                                                @if ($gapIndex === 0)
                                                                                    <td rowspan="{{ count($rek['detail_gap']) }}"
                                                                                        class="text-center"
                                                                                        style="vertical-align: middle;">
                                                                                        {{ $jumlahPtKSubIndikator }}
                                                                                    </td>

                                                                                    <td class="text-center">
                                                                                        <form
                                                                                            action="{{ route('analisis.rekomendasi-gap.index') }}"
                                                                                            method="GET" target="_blank"
                                                                                            style="display:inline;">
                                                                                            <input type="hidden"
                                                                                                name="kegiatan_id"
                                                                                                value="{{ request('kegiatan_id', '') }}">
                                                                                            <input type="hidden"
                                                                                                name="pangkat_jabatan_id"
                                                                                                value="{{ request('pangkat_jabatan_id', '') }}">
                                                                                            <input type="hidden"
                                                                                                name="jenis_ptk_id"
                                                                                                value="{{ request('jenis_ptk_id', '') }}">
                                                                                            <input type="hidden"
                                                                                                name="kota_id"
                                                                                                value="{{ request('kota_id', '') }}">
                                                                                            <input type="hidden"
                                                                                                name="jenjang_pendidikan_id"
                                                                                                value="{{ request('jenjang_pendidikan_id', '') }}">
                                                                                            <input type="hidden"
                                                                                                name="jenis_kelamin"
                                                                                                value="{{ request('jenis_kelamin', '') }}">

                                                                                            <input type="hidden"
                                                                                                name="sub_indikator_id"
                                                                                                value="{{ $rek['sub_indikator_id'] ?? '' }}">
                                                                                            <input type="hidden"
                                                                                                name="jenjang_jabatan"
                                                                                                value="{{ $jenjang['jenjang_jabatan'] ?? '' }}">

                                                                                            <button type="submit"
                                                                                                class="btn btn-sm btn-info">
                                                                                                <i class="ri-eye-line"></i>
                                                                                                Lihat Detail PTK
                                                                                            </button>
                                                                                        </form>
                                                                                    </td>

                                                                                    <td rowspan="{{ count($rek['detail_gap']) }}"
                                                                                        class="text-center"
                                                                                        style="vertical-align: middle;">
                                                                                        <div
                                                                                            class="d-flex align-items-center gap-2">
                                                                                            <span
                                                                                                class="fw-bold">{{ $percentage }}%</span>
                                                                                            <div class="progress flex-grow-1"
                                                                                                style="height:6px;">
                                                                                                <div class="progress-bar {{ $progressClass }}"
                                                                                                    role="progressbar"
                                                                                                    style="width: {{ min($percentage, 100) }}%;">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                @endif
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </tbody>

                                                            <tfoot>
                                                                <tr class="table-light">
                                                                    <td colspan="6" class="text-end fw-bold">Total PTK
                                                                        pada jenjang ini:</td>
                                                                    <td colspan="3" class="text-center fw-bold"
                                                                        style="vertical-align:middle;">
                                                                        {{ $jenjangTotalPtk }}
                                                                    </td>
                                                                </tr>

                                                                <tr class="table-info">
                                                                    <td colspan="6" class="text-end fw-bold">Total sub
                                                                        indikator dengan gap:</td>
                                                                    <td colspan="3" class="text-center fw-bold">
                                                                        {{ count($jenjang['rekomendasi'] ?? []) }}</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-success text-center py-4">
                                                        <i class="ri-checkbox-circle-fill fs-4 text-success"></i>
                                                        <h5 class="mt-2 mb-0">Semua PTK sudah mencapai level kebutuhan
                                                            belajar!</h5>
                                                        <p class="text-muted mb-0">Tidak ada gap untuk jenjang
                                                            {{ $jenjang['jenjang_jabatan'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- PROGRESS KOTA --}}
                    @if (!empty($analisisData['progress_kota']))
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
                                                @foreach ($analisisData['progress_kota'] as $kota)
                                                    <tr>
                                                        <td>{{ $kota->nama_kota }}</td>
                                                        <td class="text-center">{{ $kota->total_ptk }}</td>
                                                        <td class="text-center">{{ $kota->sudah_isi }}</td>
                                                        <td class="text-center" style="font-weight:600;">
                                                            {{ $kota->persentase }}%</td>
                                                        <td>
                                                            <div class="progress" style="height:8px;">
                                                                <div class="progress-bar
                                                                @if ($kota->persentase >= 80) bg-success
                                                                @elseif($kota->persentase >= 50) bg-warning
                                                                @else bg-danger @endif"
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
                    <!-- {{-- EMPTY STATE --}} -->
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 500px;">
                            <div class="text-center" style="max-width: 520px;">
                                <div class="mb-4">
                                    <i class="ri-bar-chart-box-line" style="font-size: 5rem; color:#dee2e6;"></i>
                                </div>
                                <h4 class="mb-3" style="color:#495057;">Belum Ada Data Analisis</h4>
                                <p class="text-muted mb-4" style="font-size:1.1rem;line-height:1.6;">
                                    Silakan pilih filter yang diinginkan untuk melihat analisis data instrumen
                                </p>
                                <button class="btn btn-primary btn-lg mt-3"
                                    onclick="document.getElementById('analisisForm').submit()"
                                    style="padding:12px 30px;font-size:1.1rem;">
                                    <i class="ri-filter-line align-bottom me-2"></i> Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('sipproja-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // GLOBALS
        const ANALISIS_INDEX_URL = @json(route('analisis.index'));
        const EXPORT_LENGKAP_URL = @json(route('analisis.export-excel'));
        const EXPORT_REKOM_URL = @json(route('export.rekomendasi-gap'));

        let jenjangDistributionChart = null;
        let jenjangPendidikanChart = null;
        let jenisKelaminChart = null;
        let allSubIndikatorsChart = null;
        let pelatihanChart = null;
        let levelTerendahChart = null;
        let levelTerendahkabkotaChart = null;
        let levelPerKotaChart = null;

        // HELPERS
        function getFilterValue(paramName) {
            const el = document.querySelector(`[name="${paramName}"]`);
            return el ? el.value : '';
        }

        function getLevelColor(level) {
            const colors = {
                1: 'rgba(220, 53, 69, 0.8)',
                2: '#17a2b8',
                3: '#007bff',
                4: '#ffc107',
                5: '#28a745',
            };
            return colors[level] || '#17a2b8';
        }

        function getLevelName(level) {
            const names = {
                1: 'Dasar',
                2: 'Penerapan',
                3: 'Analisis',
                4: 'Evaluasi',
                5: 'Pembimbingan',
            };
            return names[level] || 'Penerapan';
        }

        function statCard(icon, label, value, color) {
            return `
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:${color}22;color:${color}">
                        <i class="${icon} fs-4"></i>
                    </div>
                    <div class="stat-number">${value}</div>
                    <div class="stat-label">${label}</div>
                </div>
            </div>
        `;
        }

        function hitungRataRataLevel(jenjangData) {
            try {
                if (jenjangData.rata_rata_level !== undefined && jenjangData.rata_rata_level !== null) {
                    return parseFloat(jenjangData.rata_rata_level).toFixed(2);
                }

                let totalLevel = 0;
                let totalCount = 0;

                if (jenjangData.datasets && Array.isArray(jenjangData.datasets)) {
                    jenjangData.datasets.forEach((dataset) => {
                        const label = dataset.label || '';
                        const match = label.match(/Level (\d+)/);

                        if (match && dataset.data && Array.isArray(dataset.data)) {
                            const level = parseInt(match[1], 10);
                            const jumlah = dataset.data.reduce((sum, v) => sum + (parseInt(v, 10) || 0), 0);
                            totalLevel += level * jumlah;
                            totalCount += jumlah;
                        }
                    });
                }

                if (totalCount > 0) return (totalLevel / totalCount).toFixed(2);
                return '0.00';
            } catch (e) {
                console.error('Error hitungRataRataLevel:', e);
                return 'N/A';
            }
        }

        // EVENTS
        function resetFiltersAndSubmit() {
            document.getElementById('kegiatanSelect').value = '';
            document.getElementById('pangkatSelect').value = '';
            document.getElementById('jenisPtkSelect').value = '';
            document.getElementById('kotaSelect').value = '';
            document.getElementById('jenjangPendidikanSelect').value = '';
            document.getElementById('jenisKelaminSelect').value = '';
            document.getElementById('analisisForm').submit();
        }

        document.getElementById('btnReset')?.addEventListener('click', resetFiltersAndSubmit);

        document.getElementById('analisisForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            loadAnalisis();
        });

        // Export Excel Lengkap
        document.getElementById('btnExportLengkap')?.addEventListener('click', function() {
            const form = document.getElementById('analisisForm');
            const params = new URLSearchParams(new FormData(form));

            const btn = this;
            const originalText = btn.innerHTML;
            const originalWidth = btn.offsetWidth;

            btn.style.minWidth = originalWidth + 'px';
            btn.innerHTML = '<i class="ri-loader-4-line align-bottom me-1 spin-icon"></i> Exporting...';
            btn.disabled = true;

            const style = document.createElement('style');
            style.innerHTML = `
            .spin-icon { animation: spin 1s linear infinite; }
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        `;
            document.head.appendChild(style);

            window.location.href = EXPORT_LENGKAP_URL + '?' + params.toString();

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.style.minWidth = '';
                style.remove();
            }, 5000);
        });

        // Export Rekomendasi KB
        document.getElementById('btnExportRekomendasi')?.addEventListener('click', function() {
            const form = document.getElementById('analisisForm');
            const params = new URLSearchParams(new FormData(form));

            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ri-loader-4-line align-bottom me-1 spin-icon"></i> Exporting...';
            btn.disabled = true;

            window.location.href = EXPORT_REKOM_URL + '?' + params.toString();

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 5000);
        });

        // AJAX LOAD
        function loadAnalisis() {
            const form = document.getElementById('analisisForm');
            const params = new URLSearchParams(new FormData(form));

            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('analisisContent').style.display = 'none';
            document.getElementById('errorAlert').style.display = 'none';

            fetch(`${ANALISIS_INDEX_URL}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                })
                .then((res) => {
                    if (!res.ok) throw new Error('Gagal memuat data');
                    return res.json();
                })
                .then((data) => {
                    document.getElementById('loadingSpinner').style.display = 'none';

                    if (data.error) {
                        document.getElementById('errorAlert').innerText = data.error;
                        document.getElementById('errorAlert').style.display = 'block';
                        return;
                    }

                    updateAnalisisContent(data);
                })
                .catch((err) => {
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('errorAlert').innerText = err.message;
                    document.getElementById('errorAlert').style.display = 'block';
                });
        }

        // RENDER HTML (AJAX)
        function updateAnalisisContent(data) {
            let html = `
            <div class="row">
                ${statCard('ri-user-3-line','Total PTK<br><small>Semua Filter</small>',data.statistik?.total_ptk ?? 0,'#1a5bb8')}
                ${statCard('ri-checkbox-circle-line','PTK Menjawab<br><small>Kegiatan</small>',data.statistik?.ptk_menjawab ?? 0,'#28a745')}
                ${statCard('ri-user-forbid-line','PTK Belum<br>Menjawab',data.statistik?.ptk_belum_menjawab ?? 0,'#dc3545')}
                ${statCard('ri-progress-4-line','Progress<br>Pengisian',(data.statistik?.persentase_isi ?? 0)+'%','#dc3545')}
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container" id="sec-level-ptk">
                        <div class="chart-title">
                            <i class="ri-arrow-down-line"></i> Distribusi Level PTK Per Provinsi
                            <small class="text-muted">(Level terendah yang dicapai PTK di semua sub indikator)</small>
                        </div>
                        <canvas id="levelTerendahChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="ri-arrow-down-line"></i> Distribusi Level PTK Per Kab/Kota
                            <small class="text-muted">(Level terendah yang dicapai PTK di semua sub indikator)</small>
                        </div>
                        <canvas id="levelTerendahkabkotaChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container" id="sec-jenjang">
                        <div class="chart-title">
                            <i class="ri-pie-chart-line"></i> Distribusi Jenjang Jabatan
                            <small class="text-muted">(PTK yang menjawab)</small>
                        </div>
                        <div class="chart-canvas-wrap is-doughnut">
                            <canvas id="jenjangDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="ri-school-line"></i> Distribusi Jenjang Pendidikan
                            <small class="text-muted">(PTK yang menjawab)</small>
                        </div>
                        <div class="chart-canvas-wrap is-doughnut">
                            <canvas id="jenjangPendidikanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="ri-user-line"></i> Distribusi Jenis Kelamin
                            <small class="text-muted">(PTK yang menjawab)</small>
                        </div>
                        <div class="chart-canvas-wrap is-doughnut">
                            <canvas id="jenisKelaminChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // LEVEL PER KOTA
            if (data.distribusi_level_per_kota?.labels?.length > 0) {
                html += `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="chart-container chart-container-large" id="sec-level-kota">
                            <div class="chart-title">
                                <i class="ri-map-pin-2-line"></i> Distribusi Level per Kota (Layered Bar)
                                <span class="badge bg-info ms-2">Jumlah PTK per Level</span>
                                <small class="text-muted ms-2">
                                    ${data.distribusi_level_per_kota.total_kota ?? data.distribusi_level_per_kota.labels.length} kota ditampilkan
                                </small>
                            </div>
                            <canvas id="levelPerKotaChart" height="400"></canvas>
                        </div>
                    </div>
                </div>
            `;
            }

            // PTK BELUM MENJAWAB (LIST)
            if (Array.isArray(data.ptk_belum_menjawab) && data.ptk_belum_menjawab.length > 0) {
                let ptkRows = '';

                data.ptk_belum_menjawab.forEach((ptk, index) => {
                    let sekolahInstansi = '-';
                    if (ptk.nama_sekolah) sekolahInstansi = `<small>${ptk.nama_sekolah}</small>`;
                    else if (ptk.instansi) sekolahInstansi = `<small>${ptk.instansi}</small>`;

                    ptkRows += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td>${ptk.nip || '-'}</td>
                        <td><strong>${ptk.nama || '-'}</strong></td>
                        <td>${ptk.jenjang_jabatan || '-'}</td>
                        <td>${ptk.jenis_ptk || '-'}</td>
                        <td>${ptk.nama_kota || '-'}</td>
                        <td>${ptk.jenjang_pendidikan || '-'}</td>
                        <td>${ptk.no_hp || '-'}</td>
                        <td>${sekolahInstansi}</td>
                        <td class="text-center"><span class="badge bg-danger">Belum Isi</span></td>
                    </tr>
                `;
                });

                html += `
                <div class="row">
                    <div class="col-12">
                        <div class="table-card" id="sec-ptk-belum">
                            <div class="chart-title">
                                <i class="ri-user-forbid-line"></i> PTK yang Belum Menjawab Instrumen
                                <span class="badge bg-danger ms-2">${data.ptk_belum_menjawab.length} PTK</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered modus-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="15%">NIP</th>
                                            <th>Nama</th>
                                            <th width="12%">Jenjang Jabatan</th>
                                            <th width="10%">Jenis PTK</th>
                                            <th width="12%">Kota</th>
                                            <th width="10%">Jenjang Pendidikan</th>
                                            <th width="8%">No. HP</th>
                                            <th width="15%">Sekolah/Instansi</th>
                                            <th width="8%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>${ptkRows}</tbody>
                                </table>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="ri-information-line"></i>
                                    Menampilkan ${data.ptk_belum_menjawab.length} PTK yang belum menjawab instrumen
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            } else if (Array.isArray(data.ptk_belum_menjawab) && data.ptk_belum_menjawab.length === 0) {
                html += `
                <div class="row">
                    <div class="col-12">
                        <div class="table-card" id="sec-ptk-belum">
                            <div class="chart-title">
                                <i class="ri-user-forbid-line"></i> PTK yang Belum Menjawab Instrumen
                                <span class="badge bg-success ms-2">0 PTK</span>
                            </div>

                            <div class="alert alert-success text-center py-4">
                                <i class="ri-checkbox-circle-fill fs-4 text-success"></i>
                                <h5 class="mt-2 mb-0">Semua PTK sudah menjawab instrumen!</h5>
                                <p class="text-muted mb-0">Tidak ada PTK yang belum mengisi instrumen</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }

            // MODUS TABLE
            if (data.modus_per_kota?.length > 0) {
                let modusRows = '';

                data.modus_per_kota.forEach((kota) => {
                    if (kota.sub_indikator_modus?.length) {
                        kota.sub_indikator_modus.forEach((sub, subIndex) => {
                            modusRows += `
                            <tr>
                                ${
                                    subIndex === 0
                                        ? `
                                                    <td rowspan="${kota.sub_indikator_modus.length}" style="vertical-align:middle;font-weight:600;">
                                                        ${kota.nama_kota || 'Banten'}
                                                    </td>
                                                    <td rowspan="${kota.sub_indikator_modus.length}" style="vertical-align:middle;text-align:center;">
                                                        ${kota.total_jawaban || 0}
                                                    </td>
                                                `
                                        : ''
                                }

                                <td>
                                    <small class="text-muted">${sub.sub_indikator_code || '-'}</small><br>
                                    <span class="fw-medium">${sub.sub_indikator_name || '-'}</span>
                                </td>

                                <td>
                                    <span class="badge-level" style="background-color:${getLevelColor(sub.modus_level)}; color:#fff;">
                                        Level ${sub.modus_level} (${getLevelName(sub.modus_level)})
                                    </span>
                                </td>

                                <td class="text-center">${sub.jumlah_jawaban || 0}</td>
                            </tr>
                        `;
                        });
                    }
                });

                html += `
                <div class="row">
                    <div class="col-12">
                        <div class="table-card" id="sec-modus">
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
                                    <tbody>${modusRows}</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }

            // ALL SUB CHART
            if (data.all_sub_indikators_chart?.labels?.length > 0) {
                html += `
                <div class="row">
                    <div class="col-12">
                        <div class="chart-container chart-container-large" id="sec-sub-indikator">
                            <div class="chart-title">
                                <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator
                                <span class="badge bg-info ms-2">Jumlah PTK</span>
                            </div>
                            <canvas id="allSubIndikatorsChart" height="400"></canvas>
                        </div>
                    </div>
                </div>
            `;
            }

            // CHART PER JENJANG
            if (data.sub_indikator_per_jenjang?.length > 0) {
                html += `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="chart-container chart-container-large" id="sec-sub-jenjang">
                            <div class="chart-title">
                                <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator per Jenjang Jabatan
                                <span class="badge bg-info ms-2">Jumlah PTK per Jenjang</span>
                            </div>
                            <div class="row" id="jenjangChartsContainer">
            `;

                data.sub_indikator_per_jenjang.forEach((jenjangData, index) => {
                    const rataLevel = jenjangData.rata_rata_level ?
                        Number(jenjangData.rata_rata_level).toFixed(2) :
                        hitungRataRataLevel(jenjangData);

                    html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="chart-card" style="background:white;border-radius:12px;padding:15px;box-shadow:0 4px 12px rgba(0,0,0,.05);height:370px;">
                            <h6 class="mb-3 text-center" style="color:#1a5bb8;font-weight:600;font-size:15px;">
                                <i class="ri-user-star-line me-2"></i>${jenjangData.jenjang_jabatan}
                            </h6>
                            <canvas id="jenjangChart_${index}" height="250" style="margin-top:10px;"></canvas>
                        </div>
                    </div>
                `;
                });

                html += `
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }

            // TAB GAP (AJAX VERSION)
            if (data.rekomendasi_gap_per_jenjang?.length > 0) {
                let tabNavs = '';
                let panes = '';

                const slugify = (s) =>
                    String(s || '')
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9\-]/g, '');

                const totalSub = data.all_sub_indikators_chart?.labels?.length || 0;

                data.rekomendasi_gap_per_jenjang.forEach((jenjang, idx) => {
                    const safeId = slugify(jenjang.jenjang_jabatan);
                    const gapCount = (jenjang.rekomendasi || []).length;
                    const jenjangTotalPtk = jenjang.total_ptk || 0;

                    tabNavs += `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ${idx === 0 ? 'active' : ''}"
                                id="tab-${safeId}"
                                data-bs-toggle="tab"
                                data-bs-target="#content-${safeId}"
                                type="button" role="tab">
                            ${jenjang.jenjang_jabatan}
                        </button>
                    </li>
                `;

                    panes += `
                    <div class="tab-pane fade ${idx === 0 ? 'show active' : ''}" id="content-${safeId}" role="tabpanel">
                        <div class="alert alert-danger py-3 px-4 mb-3" style="border-radius:14px; font-size:14px; font-weight:700; line-height:1.35;">
                            Ada <b>${gapCount}</b> dari <b>${totalSub}</b> sub indikator yang belum mencapai level kompetensi
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered modus-table">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th>Sub Indikator</th>
                                        <th width="10%" class="text-center">No Urut</th>
                                        <th width="10%" class="text-center">Level Dicapai</th>
                                        <th width="10%" class="text-center">Level Gap</th>
                                        <th>Rekomendasi Kebutuhan Belajar</th>
                                        <th width="10%" class="text-center">Jumlah PTK</th>
                                        <th width="10%" class="text-center">Detail PTK</th>
                                        <th width="12%" class="text-center">% dari Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;

                    let counter = 1;

                    (jenjang.rekomendasi || []).forEach((rek) => {
                        if (!rek.detail_gap?.length) return;

                        rek.detail_gap.forEach((gap, gapIndex) => {
                            const jumlah = gap.jumlah_ptk || 0;
                            const percentage = jenjangTotalPtk > 0 ? Math.round((jumlah / jenjang
                                .total_ptk) * 100 * 10) / 10 : 0;
                            const progressClass = percentage >= 30 ? 'bg-danger' : percentage >=
                                15 ? 'bg-warning' : 'bg-info';

                            panes += `
                            <tr>
                                <td class="text-center">${counter++}</td>
                                <td>
                                    ${
                                        gapIndex === 0
                                            ? `
                                                        <small class="text-muted">${rek.sub_indikator_code || '-'}</small><br>
                                                        <span class="fw-medium">${rek.sub_indikator_name || '-'}</span>
                                                    `
                                            : ''
                                    }
                                </td>
                                <td class="text-center">${gap.level_gap ?? 0}</td>
                                <td class="text-center">
                                    <span class="badge-level" style="background-color:${getLevelColor(gap.level_dicapai)};color:#fff;">
                                        Level ${gap.level_dicapai}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-level" style="background-color:${getLevelColor(gap.level_harus)};color:#fff;">
                                        Level ${gap.level_harus}
                                    </span>
                                </td>
                                <td><small>${gap.rekomendasi || '-'}</small></td>
                                <td class="text-center">${jumlah}</td>

                                <td class="text-center">
                                    <form action="{{ route('analisis.rekomendasi-gap.index') }}" method="GET" target="_blank" style="display:inline;">
                                        <input type="hidden" name="kegiatan_id" value="${getFilterValue('kegiatan_id')}">
                                        <input type="hidden" name="pangkat_jabatan_id" value="${getFilterValue('pangkat_jabatan_id')}">
                                        <input type="hidden" name="jenis_ptk_id" value="${getFilterValue('jenis_ptk_id')}">
                                        <input type="hidden" name="kota_id" value="${getFilterValue('kota_id')}">
                                        <input type="hidden" name="jenjang_pendidikan_id" value="${getFilterValue('jenjang_pendidikan_id')}">
                                        <input type="hidden" name="jenis_kelamin" value="${getFilterValue('jenis_kelamin')}">
                                        <input type="hidden" name="sub_indikator_id" value="${rek.sub_indikator_id || ''}">
                                        <input type="hidden" name="jenjang_jabatan" value="${jenjang.jenjang_jabatan || ''}">
                                        <button type="submit" class="btn btn-sm btn-info">Lihat Detail PTK</button>
                                    </form>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="fw-bold">${percentage}%</span>
                                        <div class="progress w-100" style="height:6px;">
                                            <div class="progress-bar ${progressClass}" role="progressbar" style="width:${Math.min(percentage,100)}%;"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                    });

                    panes += `
                                </tbody>

                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="6" class="text-end fw-bold">Total PTK pada jenjang ini:</td>
                                        <td colspan="3" class="text-center fw-bold" style="vertical-align:middle;">
                                            ${jenjangTotalPtk}
                                        </td>
                                    </tr>
                                    <tr class="table-info">
                                        <td colspan="6" class="text-end fw-bold">Total sub indikator dengan gap:</td>
                                        <td colspan="3" class="text-center fw-bold">${gapCount}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                `;
                });

                html += `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="table-card" id="sec-gap">
                            <div class="chart-title">
                                <i class="ri-list-check-2"></i> Rekomendasi Kebutuhan Belajar per Jenjang Jabatan
                            </div>

                            <div class="mb-4">
                                <ul class="nav nav-tabs" id="jenjangTab" role="tablist">${tabNavs}</ul>
                            </div>

                            <div class="tab-content" id="jenjangTabContent">${panes}</div>
                        </div>
                    </div>
                </div>
            `;
            }

            // PROGRESS KOTA
            if (data.progress_kota?.length > 0) {
                let rows = '';

                data.progress_kota.forEach((kota) => {
                    const cls = kota.persentase >= 80 ? 'bg-success' : kota.persentase >= 50 ? 'bg-warning' :
                        'bg-danger';

                    rows += `
                    <tr>
                        <td>${kota.nama_kota}</td>
                        <td class="text-center">${kota.total_ptk}</td>
                        <td class="text-center">${kota.sudah_isi}</td>
                        <td class="text-center" style="font-weight:600;">${kota.persentase}%</td>
                        <td>
                            <div class="progress" style="height:8px;">
                                <div class="progress-bar ${cls}" role="progressbar" style="width:${kota.persentase}%;"></div>
                            </div>
                        </td>
                    </tr>
                `;
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
                                    <tbody>${rows}</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }

            // PELATIHAN
            if (data.pelatihan_data?.length > 0) {
                const totalPelatihan = data.pelatihan_data.reduce((sum, it) => sum + (it.jumlah_ptk || 0), 0);

                html += `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="chart-container chart-container-large" id="sec-pelatihan">
                            <div class="chart-title">
                                <i class="ri-book-mark-line"></i> Distribusi Pelatihan yang diperlukan PTK
                                <span class="badge bg-info ms-2">Berdasarkan Kegiatan</span>
                            </div>
                            <canvas id="pelatihanChart" height="400"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-card">
                            <div class="chart-title">
                                <i class="ri-list-check-2"></i> Detail Pelatihan yang Diikuti
                                <span class="badge bg-info ms-2">Total ${totalPelatihan} PTK</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered modus-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Nama Pelatihan</th>
                                            <th width="10%" class="text-center">Tipe</th>
                                            <th width="15%" class="text-center">Jumlah PTK</th>
                                            <th width="15%" class="text-center">Persentase</th>
                                            <th width="20%" class="text-center">Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.pelatihan_data
                                            .map((p, i) => {
                                                const persen = totalPelatihan > 0 ? ((p.jumlah_ptk / totalPelatihan) * 100).toFixed(1) : 0;
                                                const cls = persen >= 30 ? 'bg-success' : persen >= 15 ? 'bg-warning' : 'bg-danger';
                                                const tipeBadge =
                                                    p.tipe === 'master'
                                                        ? '<span class="badge bg-primary">Master</span>'
                                                        : p.tipe === 'manual'
                                                          ? '<span class="badge bg-secondary">Manual</span>'
                                                          : '-';

                                                return `
                                                            <tr>
                                                                <td class="text-center">${i + 1}</td>
                                                                <td>${p.nama_pelatihan || 'Pelatihan Lainnya'}</td>
                                                                <td class="text-center">${tipeBadge}</td>
                                                                <td class="text-center">${p.jumlah_ptk || 0}</td>
                                                                <td class="text-center fw-bold">${persen}%</td>
                                                                <td>
                                                                    <div class="progress" style="height:8px;">
                                                                        <div class="progress-bar ${cls}" role="progressbar" style="width:${persen}%;"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        `;
                                            })
                                            .join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }

            document.getElementById('analisisContent').innerHTML = html;
            document.getElementById('analisisContent').style.display = 'block';

            if (typeof window.__buildAnalisisNav === 'function') {
                try {
                    window.__buildAnalisisNav();
                } catch (e) {}
            }

            setTimeout(() => renderCharts(data), 80);
        }

        // CHARTS
        function destroyChartInstance(chart) {
            if (!chart) return;
            try {
                chart.destroy();
            } catch (e) {}
        }

        function renderCharts(data) {
            [
                jenjangDistributionChart,
                jenjangPendidikanChart,
                jenisKelaminChart,
                allSubIndikatorsChart,
                pelatihanChart,
                levelTerendahChart,
                levelTerendahkabkotaChart,
                levelPerKotaChart,
            ].forEach(destroyChartInstance);

            // PROVINSI BAR
            const levelTerendahCtx = document.getElementById('levelTerendahChart')?.getContext('2d');
            if (levelTerendahCtx && data.level_terendah_per_ptk) {
                levelTerendahChart = new Chart(levelTerendahCtx, {
                    type: 'bar',
                    data: {
                        labels: data.level_terendah_per_ptk.labels || ['Level 1', 'Level 2', 'Level 3', 'Level 4',
                            'Level 5'
                        ],
                        datasets: [{
                            label: 'Jumlah PTK',
                            data: data.level_terendah_per_ptk.data || [0, 0, 0, 0, 0],
                            backgroundColor: ['rgba(220, 53, 69, 0.8)', '#17a2b8', '#007bff', '#ffc107',
                                '#28a745'
                            ],
                            borderColor: ['rgba(220, 53, 69, 0.8)', '#148899', '#0069d9', '#e0a800',
                                '#218838'
                            ],
                            borderWidth: 1,
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => {
                                        const total = data.level_terendah_per_ptk.total_ptk || 0;
                                        const val = ctx.raw;
                                        const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return `${val} PTK (${pct}%)`;
                                    },
                                    afterLabel: (ctx) => {
                                        const level = ctx.dataIndex + 1;
                                        const names = ['Dasar', 'Penerapan', 'Analisis', 'Evaluasi',
                                            'Pembimbingan'
                                        ];
                                        return `Level ${level}: ${names[level - 1]}`;
                                    },
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah PTK'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Level Kompetensi Terendah'
                                }
                            },
                        },
                    },
                });
            }

            // KAB/KOTA BAR
            const levelTerendahkabkotaCtx = document.getElementById('levelTerendahkabkotaChart')?.getContext('2d');
            if (levelTerendahkabkotaCtx && data.level_kota_per_ptk) {
                levelTerendahkabkotaChart = new Chart(levelTerendahkabkotaCtx, {
                    type: 'bar',
                    data: {
                        labels: data.level_kota_per_ptk.labels || ['Level 1', 'Level 2', 'Level 3', 'Level 4',
                            'Level 5'
                        ],
                        datasets: [{
                            label: 'Jumlah PTK',
                            data: data.level_kota_per_ptk.data || [0, 0, 0, 0, 0],
                            backgroundColor: ['rgba(220, 53, 69, 0.8)', '#17a2b8', '#007bff', '#ffc107',
                                '#28a745'
                            ],
                            borderColor: ['rgba(220, 53, 69, 0.8)', '#148899', '#0069d9', '#e0a800',
                                '#218838'
                            ],
                            borderWidth: 1,
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => {
                                        const total = data.level_kota_per_ptk.total_ptk || 0;
                                        const val = ctx.raw;
                                        const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return `${val} PTK (${pct}%)`;
                                    },
                                    afterLabel: (ctx) => {
                                        const level = ctx.dataIndex + 1;
                                        const names = ['Dasar', 'Penerapan', 'Analisis', 'Evaluasi',
                                            'Pembimbingan'
                                        ];
                                        return `Level ${level}: ${names[level - 1]}`;
                                    },
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah PTK'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Level Kompetensi Terendah'
                                }
                            },
                        },
                    },
                });
            }

            // LEVEL PER KOTA STACKED
            const levelPerKotaCtx = document.getElementById('levelPerKotaChart')?.getContext('2d');
            if (levelPerKotaCtx && data.distribusi_level_per_kota) {
                levelPerKotaChart = new Chart(levelPerKotaCtx, {
                    type: 'bar',
                    data: {
                        labels: data.distribusi_level_per_kota.labels || [],
                        datasets: data.distribusi_level_per_kota.datasets || [],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                                title: {
                                    display: true,
                                    text: 'Kota'
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah PTK'
                                },
                            },
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    boxHeight: 12,
                                    padding: 10
                                },
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: (ctx) => {
                                        const label = ctx.dataset.label || '';
                                        const value = ctx.raw;
                                        const total = ctx.chart.data.datasets
                                            .map((ds) => ds.data[ctx.dataIndex])
                                            .reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} PTK (${pct}%)`;
                                    },
                                    footer: (items) => {
                                        const total = items.reduce((sum, it) => sum + it.raw, 0);
                                        return `Total: ${total} PTK`;
                                    },
                                },
                            },
                        },
                    },
                });
            }

            // DOUGHNUT: JABATAN
            const jenjangCtx = document.getElementById('jenjangDistributionChart')?.getContext('2d');
            if (jenjangCtx) {
                const src = data.jenjang_distribution?.length ? data.jenjang_distribution : [{
                    jenjang_jabatan: 'Tidak Ada Data',
                    count: 0
                }];

                jenjangDistributionChart = new Chart(jenjangCtx, {
                    type: 'doughnut',
                    data: {
                        labels: src.map((x) => x.jenjang_jabatan),
                        datasets: [{
                            data: src.map((x) => x.count),
                            backgroundColor: ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57',
                                '#5f27cd'
                            ],
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        radius: '70%',
                        cutout: '65%',
                        layout: {
                            padding: {
                                bottom: 26
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'rectRounded',
                                    boxWidth: 10,
                                    boxHeight: 10,
                                    padding: 8,
                                    font: {
                                        size: 10
                                    },
                                },
                            },
                        },
                    },
                });
            }

            // DOUGHNUT: PENDIDIKAN
            const jenjangPendidikanCtx = document.getElementById('jenjangPendidikanChart')?.getContext('2d');
            if (jenjangPendidikanCtx) {
                const src = data.jenjang_pendidikan_distribution?.length ?
                    data.jenjang_pendidikan_distribution : [{
                        jenjang_pendidikan: 'Tidak Ada Data',
                        count: 0
                    }];

                jenjangPendidikanChart = new Chart(jenjangPendidikanCtx, {
                    type: 'doughnut',
                    data: {
                        labels: src.map((x) => x.jenjang_pendidikan),
                        datasets: [{
                            data: src.map((x) => x.count),
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                                '#FF9F40'
                            ],
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        radius: '70%',
                        cutout: '65%',
                        layout: {
                            padding: {
                                bottom: 26
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'rectRounded',
                                    boxWidth: 10,
                                    boxHeight: 10,
                                    padding: 8,
                                    font: {
                                        size: 10
                                    },
                                },
                            },
                        },
                    },
                });
            }

            // DOUGHNUT: KELAMIN
            const jenisKelaminCtx = document.getElementById('jenisKelaminChart')?.getContext('2d');
            if (jenisKelaminCtx) {
                const src = data.jenis_kelamin_distribution?.length ? data.jenis_kelamin_distribution : [{
                    jenis_kelamin: 'Tidak Ada Data',
                    count: 0
                }];

                jenisKelaminChart = new Chart(jenisKelaminCtx, {
                    type: 'doughnut',
                    data: {
                        labels: src.map((x) => x.jenis_kelamin),
                        datasets: [{
                            data: src.map((x) => x.count),
                            backgroundColor: ['#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236',
                                '#166a8f'
                            ],
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        radius: '70%',
                        cutout: '65%',
                        layout: {
                            padding: {
                                bottom: 26
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'rectRounded',
                                    boxWidth: 10,
                                    boxHeight: 10,
                                    padding: 8,
                                    font: {
                                        size: 10
                                    },
                                },
                            },
                        },
                    },
                });
            }

            // ALL SUB INDIKATOR BAR
            const allSubCtx = document.getElementById('allSubIndikatorsChart')?.getContext('2d');
            if (
                allSubCtx &&
                data.all_sub_indikators_chart?.labels?.length > 0 &&
                data.all_sub_indikators_chart?.datasets?.length > 0
            ) {
                const chartData = data.all_sub_indikators_chart;

                allSubIndikatorsChart = new Chart(allSubCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: chartData.datasets,
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 8,
                                bottom: 24
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    padding: 8,
                                    font: {
                                        size: 10
                                    },
                                },
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: (ctx) => `${ctx.dataset.label || ''}: ${ctx.raw} PTK`,
                                },
                            },
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            },
                        },
                    },
                });

                allSubIndikatorsChart.data.datasets.forEach((ds) => {
                    if (ds.label && ds.label.includes('Level 1')) {
                        ds.backgroundColor = 'rgba(220, 53, 69, 0.8)';
                        ds.borderColor = 'rgba(220, 53, 69, 1)';
                    }
                });
                allSubIndikatorsChart.update();
            }

            // PELATIHAN CHART
            const pelatihanCtx = document.getElementById('pelatihanChart')?.getContext('2d');
            if (pelatihanCtx && data.pelatihan_data?.length > 0) {
                const pelatihanData = data.pelatihan_data;
                const labels = pelatihanData.map((it) => {
                    const name = it.nama_pelatihan || 'Pelatihan Lainnya';
                    return name.length > 30 ? name.substring(0, 30) + '...' : name;
                });
                const values = pelatihanData.map((it) => it.jumlah_ptk || 0);

                const palette = [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF',
                    '#4DC9F6', '#F67019', '#F53794', '#537BC4', '#ACC236', '#166A8F', '#00A950', '#58595B',
                ];
                const backgroundColors = labels.map((_, i) => palette[i % palette.length]);

                pelatihanChart = new Chart(pelatihanCtx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Jumlah PTK',
                            data: values,
                            backgroundColor: backgroundColors,
                            borderWidth: 1,
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => {
                                        const total = values.reduce((a, b) => a + b, 0);
                                        const val = ctx.raw;
                                        const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return `${labels[ctx.dataIndex] || ''}: ${val} PTK (${pct}%)`;
                                    },
                                },
                            },
                        },
                        scales: {
                            x: {
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                title: {
                                    display: true,
                                    text: 'Nama Pelatihan',
                                    font: {
                                        size: 13
                                    }
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah PTK',
                                    font: {
                                        size: 13
                                    }
                                },
                            },
                        },
                    },
                });
            }

            renderJenjangCharts(data);

            if (typeof window.__buildAnalisisNav === 'function') {
                try {
                    window.__buildAnalisisNav();
                } catch (e) {}
            }
        }

        function renderJenjangCharts(data) {
            const container = document.getElementById('jenjangChartsContainer');
            if (!container || !data?.sub_indikator_per_jenjang) return;

            document.querySelectorAll('[id^="jenjangChart_"]').forEach((canvas) => {
                const id = canvas.id;
                if (window[id]) {
                    try {
                        window[id].destroy();
                    } catch (e) {}
                }
            });

            data.sub_indikator_per_jenjang.forEach((jenjangData, index) => {
                const canvasId = `jenjangChart_${index}`;
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                if (!ctx) return;

                if (!jenjangData.datasets || !Array.isArray(jenjangData.datasets)) return;

                window[canvasId] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: jenjangData.labels || [],
                        datasets: jenjangData.datasets,
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        layout: {
                            padding: {
                                top: 2,
                                bottom: 4,
                                left: 6,
                                right: 6
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 8
                                    },
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    padding: 6,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                },
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: (ctx) => `${ctx.dataset.label}: ${ctx.raw} PTK`,
                                },
                            },
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
                                },
                            },
                        },
                    },
                });

                window[canvasId].data.datasets.forEach((ds) => {
                    if (ds.label && ds.label.includes('Level 1')) {
                        ds.backgroundColor = 'rgba(220, 53, 69, 0.8)';
                        ds.borderColor = 'rgba(220, 53, 69, 1)';
                    }
                });
                window[canvasId].update();

                window[canvasId].resize();
                setTimeout(() => window[canvasId]?.resize(), 50);
            });
        }

        // INITIAL RENDER (SERVER DATA)
        @if (isset($analisisData) && !isset($analisisData['error']))
            document.addEventListener('DOMContentLoaded', () => {
                renderCharts(@json($analisisData));
            });
        @endif

        // FLOATING NAV LIST (ISI NAVBAR SESUAI LIST LU)
        (function() {
            const navList = document.getElementById("navListAnalisis");
            const panel = document.getElementById("floatingPanelAnalisis");
            const toggleBtn = document.getElementById("toggleFloatingAnalisis");
            const navWrap = document.getElementById("floatingNavAnalisis");
            const backdrop = document.getElementById("floatingBackdropAnalisis");
            const closeBtn = document.getElementById("closePanelBtnAnalisis");

            if (!navList || !panel || !toggleBtn || !navWrap) return;

            const NAV_ITEMS = [{
                    id: "sec-level-ptk",
                    label: "grafik Distribusi Level PTK"
                },
                {
                    id: "sec-jenjang",
                    label: "grafik Distribusi Jenjang"
                },
                {
                    id: "sec-level-kota",
                    label: "grafik Distribusi Level per Kota (Layered Bar)"
                },
                {
                    id: "sec-ptk-belum",
                    label: "tabel PTK yang Belum Menjawab Instrumen"
                },
                {
                    id: "sec-modus",
                    label: "tabel Modus Level per Kota (Jumlah PTK)"
                },
                {
                    id: "sec-sub-indikator",
                    label: "grafik Distribusi PTK per Sub Indikator Jumlah PTK"
                },
                {
                    id: "sec-sub-jenjang",
                    label: "grafik Distribusi PTK per Sub Indikator per Jenjang Jabatan"
                },
                {
                    id: "sec-gap",
                    label: "tabel Rekomendasi Kebutuhan Belajar per Jenjang Jabatan"
                },
                {
                    id: "sec-pelatihan",
                    label: "grafik Distribusi Pelatihan yang diperlukan PTK"
                },
            ];

            function openNav() {
                panel.classList.add("open");
                panel.setAttribute("aria-hidden", "false");
                navWrap.classList.add("hide");
                if (backdrop) backdrop.hidden = false;
            }

            function closeNav() {
                panel.classList.remove("open");
                panel.setAttribute("aria-hidden", "true");
                navWrap.classList.remove("hide");
                if (backdrop) backdrop.hidden = true;
            }

            toggleBtn.addEventListener("click", openNav);
            closeBtn?.addEventListener("click", closeNav);
            backdrop?.addEventListener("click", closeNav);

            function renderList() {
                navList.innerHTML = "";
                NAV_ITEMS.forEach((it) => {
                    const el = document.createElement("div");
                    el.className = "nav-case-item";
                    el.innerHTML = `
                    <span>${it.label}</span>
                    <i class="ri-arrow-right-s-line"></i>
                `;
                    el.addEventListener("click", () => {
                        const target = document.getElementById(it.id);
                        if (!target) {
                            console.warn("Section belum ada:", it.id, it.label);
                            return;
                        }
                        closeNav();
                        const y = target.getBoundingClientRect().top + window.scrollY - 90;
                        window.scrollTo({
                            top: y,
                            behavior: "smooth"
                        });
                    });
                    navList.appendChild(el);
                });
            }

            window.__buildAnalisisNav = function() {
                renderList();
            };

            document.addEventListener("DOMContentLoaded", () => {
                setTimeout(() => {
                    renderList();
                }, 120);
            });
        })();

        // FULLSCREEN POPUP (TABLE/CHART)
        (function() {
            function ensureModal() {
                if (document.getElementById("fsModalAnalisis")) return;

                const modal = document.createElement("div");
                modal.id = "fsModalAnalisis";
                modal.innerHTML = `
        <div class="fs-backdrop" data-fs-close="1"></div>
        <div class="fs-panel" role="dialog" aria-modal="true">
            <div class="fs-head">
            <div class="fs-title" id="fsTitleAnalisis">Preview</div>
            <button class="fs-close" type="button" aria-label="Tutup" data-fs-close="1">
                <i class="ri-close-line"></i>
            </button>
            </div>
            <div class="fs-body" id="fsBodyAnalisis"></div>
        </div>
        `;
                document.body.appendChild(modal);

                // close handler
                modal.addEventListener("click", (e) => {
                    if (e.target.closest('[data-fs-close]')) closeModal();
                });

                document.addEventListener("keydown", (e) => {
                    if (e.key === "Escape") closeModal();
                });
            }

            function openModal(title, bodyHtml) {
                ensureModal();
                const modal = document.getElementById("fsModalAnalisis");
                const titleEl = document.getElementById("fsTitleAnalisis");
                const bodyEl = document.getElementById("fsBodyAnalisis");

                titleEl.textContent = title || "Preview";
                bodyEl.innerHTML = bodyHtml || "";
                modal.classList.add("show");

                // lock scroll body
                document.body.style.overflow = "hidden";
            }

            function closeModal() {
                const modal = document.getElementById("fsModalAnalisis");
                if (!modal) return;
                modal.classList.remove("show");

                // unlock scroll
                document.body.style.overflow = "";
                const bodyEl = document.getElementById("fsBodyAnalisis");
                if (bodyEl) bodyEl.innerHTML = "";
            }

            // --- OPEN TABLE FULLSCREEN ---
            // Klik .table-card -> clone tabelnya (kecuali klik tombol/link/form)
            document.addEventListener("click", (e) => {
                const banned = e.target.closest(
                    "button, a, input, select, textarea, form, .nav-tabs, .nav-link");
                if (banned) return;

                const tableCard = e.target.closest(".table-card");
                if (!tableCard) return;

                const title = tableCard.querySelector(".chart-title")?.innerText?.trim() || "Tabel";
                const tableResp = tableCard.querySelector(".table-responsive");
                const tableOnly = tableCard.querySelector("table");

                if (tableResp) {
                    openModal(title, tableResp.outerHTML);
                } else if (tableOnly) {
                    openModal(title, `<div class="table-responsive">${tableOnly.outerHTML}</div>`);
                }
            });

            // --- OPEN CHART FULLSCREEN ---
            // Klik canvas -> jadi gambar fullscreen (simple & aman)
            document.addEventListener("click", (e) => {
                const canvas = e.target.closest("canvas");
                if (!canvas) return;

                // biar gak ganggu canvas yang bukan chart (kalau ada)
                const id = canvas.id || "";
                const looksLikeChart = /Chart|jenjangChart_/i.test(id);
                if (!looksLikeChart) return;

                // ambil judul dari container terdekat
                const container =
                    canvas.closest(".chart-container") ||
                    canvas.closest(".chart-card") ||
                    canvas.parentElement;

                const title =
                    container?.querySelector(".chart-title")?.innerText?.trim() ||
                    container?.querySelector("h6")?.innerText?.trim() ||
                    "Grafik";

                let imgSrc = "";
                try {
                    imgSrc = canvas.toDataURL("image/png", 1.0);
                } catch (err) {
                    // fallback: kalau ada isu CORS (jarang), ya skip
                    return;
                }

                openModal(
                    title,
                    `<div class="fs-chart-wrap"><img src="${imgSrc}" alt="chart fullscreen"></div>`
                );
            });

            // expose kalau mau dipanggil manual
            window.__openAnalisisFullscreen = openModal;
            window.__closeAnalisisFullscreen = closeModal;
        })();
    </script>
@endsection
