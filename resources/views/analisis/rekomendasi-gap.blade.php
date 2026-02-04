@extends('layouts.main')
@section('mycontent')
    @php
        $title = 'Detail PTK dengan Kebutuhan Belajar';

        $levelNames = [
            1 => 'Gagal',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan',
        ];

        $levelColors = [
            1 => '#17a212',
            2 => '#17a2b8',
            3 => '#007bff',
            4 => '#ffc107',
            5 => '#28a745',
        ];

        // Helper function untuk mendapatkan rekomendasi pelatihan
        function getRekomendasiPelatihan(
            $subIndikatorId,
            $tahap,
            $entity,
            $subIndikatorCode,
            $levelDicapai,
            $levelTarget,
        ) {
            // Coba ambil dari database terlebih dahulu
            $rekomendasi = DB::table('ptk_rekomendasi')
                ->where('sub_indikator_id', $subIndikatorId)
                ->where('sub_indikator_code', $subIndikatorCode)
                ->where('level', $levelTarget)
                ->first();

            if ($rekomendasi) {
                return $rekomendasi->rekomendasi;
            }

            // Jika tidak ada di database, buat rekomendasi generik
            $levelNames = [
                1 => 'Gagal',
                2 => 'Penerapan',
                3 => 'Analisis',
                4 => 'Evaluasi',
                5 => 'Pembimbingan',
            ];

            $levelDicapaiName = $levelNames[$levelDicapai] ?? "Level $levelDicapai";
            $levelTargetName = $levelNames[$levelTarget] ?? "Level $levelTarget";
            $gap = $levelTarget - $levelDicapai;

            if ($gap == 1) {
                return "Pelatihan untuk meningkatkan dari {$levelDicapaiName} ke {$levelTargetName}";
            } else {
                return "Pelatihan intensif untuk meningkatkan dari {$levelDicapaiName} ke {$levelTargetName} (naik {$gap} level)";
            }
        }
    @endphp

    <style>
        .analisis-wrap {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .analisis-head {
            background: linear-gradient(135deg, #1a5bb8 0%, #2d6bc8 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 18px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(26, 91, 184, 0.2);
        }

        .filter-info-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .filter-badge {
            background: #e6f7ff;
            color: #0056b3;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid #b3d9ff;
        }

        /* Styles untuk sub indikator dengan gap */
        .sub-indikator-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }

        .sub-indikator-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8d7da;
        }

        .level-section {
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .level-header-card {
            background: #fff5f5;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #fecaca;
        }

        .level-table-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .ptk-table-container {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 10px;
        }

        .ptk-table {
            font-size: 0.85rem;
            width: 100%;
            border-collapse: collapse;
        }

        .ptk-table th {
            background-color: #e9ecef;
            font-weight: 600;
            padding: 10px 8px;
            position: sticky;
            top: 0;
            z-index: 1;
            white-space: nowrap;
        }

        .ptk-table td {
            padding: 8px;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        .ptk-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-level {
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
        }

        .badge-gap {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-target {
            background: linear-gradient(135deg, #1a5bb8, #2d6bc8);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Stat card khusus gap */
        .stat-card-gap {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }

        .stat-icon-gap {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #dc3545;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 0;
            font-weight: 500;
        }

        /* Empty state */
        .empty-level {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            border: 1px dashed #dee2e6;
            border-radius: 8px;
            margin: 10px 0;
        }

        .empty-level i {
            font-size: 2rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        .gap-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 10px 15px;
            margin: 10px 0;
            font-size: 0.9rem;
        }

        .gap-info i {
            color: #e74c3c;
            margin-right: 8px;
        }

        /* Styles untuk rekomendasi pelatihan (PENUH TIDAK DIPOTONG) */
        .rekomendasi-pelatihan {
            background: linear-gradient(135deg, #e7f1ff, #d1e7ff);
            border-left: 4px solid #0d6efd;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.9rem;
            white-space: normal !important;
            /* Pastikan tidak dipotong */
            word-wrap: break-word;
            /* Allow wrapping */
            overflow: visible !important;
            /* Pastikan visible */
        }

        .rekomendasi-pelatihan h6 {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .rekomendasi-pelatihan ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .rekomendasi-pelatihan li {
            margin-bottom: 8px;
            padding-left: 5px;
            white-space: normal !important;
            /* Pastikan tidak dipotong */
            word-wrap: break-word;
            /* Allow wrapping */
        }

        .rekomendasi-pelatihan li:last-child {
            margin-bottom: 0;
        }

        .rekomendasi-pelatihan strong {
            color: #0a58ca;
            white-space: normal !important;
            /* Pastikan tidak dipotong */
        }

        /* Untuk kontainer yang menampung rekomendasi */
        .rekomendasi-container {
            max-width: 100% !important;
            overflow: visible !important;
            white-space: normal !important;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            .ptk-table {
                font-size: 0.8rem;
            }

            .ptk-table th,
            .ptk-table td {
                padding: 6px 4px;
            }

            .rekomendasi-pelatihan {
                padding: 10px;
                font-size: 0.85rem;
            }

            /* Pagination Styles */
            .pagination-container {
                margin-top: 15px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .pagination-sm .page-link {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            .page-item.active .page-link {
                background-color: #1a5bb8;
                border-color: #1a5bb8;
            }

            .page-link {
                color: #1a5bb8;
                border: 1px solid #dee2e6;
            }

            .page-link:hover {
                color: #0d47a1;
                background-color: #e9ecef;
                border-color: #dee2e6;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('analisis.index') }}">Analisis</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="analisis-wrap">
            <!-- Header -->
            <div class="analisis-head">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="ri-alert-line"></i> {{ $title }}
                </h5>
                <div class="meta mt-2">
                    Detail PTK yang memiliki kebutuhan belajar (gap) berdasarkan filter yang diterapkan
                    @if ($request->filled('sub_indikator_id') || $request->filled('jenjang_jabatan'))
                        <br><small>Filter spesifik diterapkan:
                            @if ($request->filled('sub_indikator_id'))
                                Sub Indikator ID: {{ $request->sub_indikator_id }}
                            @endif
                            @if ($request->filled('jenjang_jabatan'))
                                | Jenjang: {{ $request->jenjang_jabatan }}
                            @endif
                        </small>
                    @endif
                </div>
                <!-- Di bagian atas halaman, setelah filter info -->
                @if ($request->filled('sub_indikator_id'))
                    <div class="alert alert-info">
                        <i class="ri-information-line"></i>
                        <strong>Menampilkan semua level untuk sub indikator ini:</strong>
                        {{ $subIndikatorName ?? 'Sub Indikator Terpilih' }}
                        @if ($request->filled('jenjang_jabatan'))
                            | Jenjang: {{ $request->jenjang_jabatan }}
                        @endif
                    </div>
                @endif
            </div>

            <!-- INFO FILTER YANG DITERAPKAN -->
            @if (
                $request->hasAny([
                    'kegiatan_id',
                    'pangkat_jabatan_id',
                    'jenis_ptk_id',
                    'kota_id',
                    'jenjang_pendidikan_id',
                    'jenis_kelamin',
                ]))
                <div class="filter-info-card">
                    <h6 class="mb-3 d-flex align-items-center gap-2">
                        <i class="ri-filter-line"></i> Filter yang Diterapkan
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($request->filled('kegiatan_id'))
                            <span class="filter-badge">Kegiatan:
                                {{ $kegiatans->where('kegiatan_id', $request->kegiatan_id)->first()->kegiatan_name ?? '-' }}</span>
                        @endif
                        @if ($request->filled('pangkat_jabatan_id'))
                            <span class="filter-badge">Jenjang:
                                {{ $pangkatJabatans->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)->first()->jenjang_jabatan ?? '-' }}</span>
                        @endif
                        @if ($request->filled('jenis_ptk_id'))
                            <span class="filter-badge">Jenis PTK:
                                {{ $jenisPtkList->where('jenis_ptk_id', $request->jenis_ptk_id)->first()->jenis_ptk ?? '-' }}</span>
                        @endif
                        @if ($request->filled('kota_id'))
                            <span class="filter-badge">Kota:
                                {{ $kotas->where('kota_id', $request->kota_id)->first()->nama_kota ?? '-' }}</span>
                        @endif
                        @if ($request->filled('jenjang_pendidikan_id'))
                            <span class="filter-badge">Jenjang Pendidikan:
                                {{ $jenjangPendidikanList->where('jenjang_pendidikan_id', $request->jenjang_pendidikan_id)->first()->jenjang_pendidikan ?? '-' }}</span>
                        @endif
                        @if ($request->filled('jenis_kelamin'))
                            <span class="filter-badge">Jenis Kelamin:
                                {{ $request->jenis_kelamin == 'L' ? 'Laki-laki' : ($request->jenis_kelamin == 'P' ? 'Perempuan' : $request->jenis_kelamin) }}
                            </span>
                        @endif
                        @if ($request->filled('bentuk_pendidikan'))
                            <span class="filter-badge">Bentuk Pendidikan: {{ $request->bentuk_pendidikan }}</span>
                        @endif
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('analisis.index', $request->all()) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-arrow-left-line"></i> Kembali ke Analisis
                        </a>
                    </div>
                </div>
            @endif
            <!-- Konten Data -->
            <div id="analisisContent">
                @if (isset($analisisData) && !empty($analisisData['detail_per_jenjang']))
                    <!-- Statistik Gap -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-group-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['total_unique_ptk'] ?? 0 }}</div>
                                <div class="stat-label">PTK dengan Gap</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-list-check fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['total_unique_sub_indikator'] ?? 0 }}</div>
                                <div class="stat-label">Sub Indikator Bermasalah</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-file-list-3-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['total_data_ptk'] ?? 0 }}</div>
                                <div class="stat-label">Total Data Gap</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-stack-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ count($analisisData['detail_per_jenjang']) ?? 0 }}</div>
                                <div class="stat-label">Jenjang dengan Gap</div>
                            </div>
                        </div>
                    </div>

                    @foreach ($analisisData['detail_per_jenjang'] as $jenjangIndex => $jenjang)
                        <!-- Hanya tampilkan jika ada sub indikator dengan gap -->
                        @if (!empty($jenjang['detail_per_sub_indikator']))
                            <div class="sub-indikator-card mb-4">
                                <div class="sub-indikator-header">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="ri-user-star-line text-primary"></i>
                                            Jenjang {{ $jenjang['jenjang_jabatan'] }}
                                        </h5>
                                        <div class="d-flex gap-2 mt-2">
                                            <span class="badge-target">Target Level: {{ $jenjang['target_level'] }}</span>
                                            <span class="badge bg-danger">{{ $jenjang['total_ptk'] }} PTK dengan Gap</span>
                                            <span class="badge bg-secondary">{{ $jenjang['total_sub_indikator'] }} Sub
                                                Indikator Bermasalah</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="gap-info">
                                    <i class="ri-information-line"></i>
                                    <strong>Keterangan:</strong> PTK di jenjang {{ $jenjang['jenjang_jabatan'] }} harus
                                    mencapai level {{ $jenjang['target_level'] }}.
                                    Hanya menampilkan sub indikator yang memiliki PTK dengan level di bawah target.
                                </div>

                                @foreach ($jenjang['detail_per_sub_indikator'] as $subIndex => $sub)
                                    <!-- Hanya tampilkan jika ada PTK di sub indikator ini -->
                                    @if ($sub['total_ptk'] > 0)
                                        <div class="mb-4">
                                            <div class="sub-indikator-header">
                                                <div>
                                                    <h6 class="mb-2">
                                                        <i class="ri-list-check text-danger"></i>
                                                        {{ $sub['sub_indikator_code'] }} -
                                                        {{ $sub['sub_indikator_name'] }}
                                                    </h6>
                                                    <div class="d-flex gap-2">
                                                        <span class="badge bg-secondary">{{ $sub['total_ptk'] }} PTK dengan
                                                            Gap</span>
                                                        <span class="badge bg-info">{{ $sub['total_levels'] }} Level
                                                            Bermasalah</span>
                                                        <span class="badge-target">Target: Level
                                                            {{ $sub['target_level'] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tampilkan tabel per level -->
                                            @for ($level = 1; $level <= 5; $level++)
                                                @php
                                                    // Cari data PTK untuk level ini
                                                    $levelData = null;
                                                    foreach ($sub['ptk_per_level'] as $item) {
                                                        if ($item['level'] == $level) {
                                                            $levelData = $item;
                                                            break;
                                                        }
                                                    }

                                                    // Tentukan jika level ini ada gap
                                                    $hasGap = $levelData && $level < $sub['target_level'];
                                                    $hasPtk = $levelData && !empty($levelData['ptk_list']);

                                                    // Ambil rekomendasi pelatihan jika ada gap
                                                    $rekomendasiPelatihan = [];
                                                    if ($hasGap && isset($levelData['rekomendasi_pelatihan'])) {
                                                        $rekomendasiPelatihan = $levelData['rekomendasi_pelatihan'];
                                                    } elseif ($hasGap && $level < $sub['target_level']) {
                                                        // Jika tidak ada data rekomendasi di levelData, buat rekomendasi dinamis
                                                        for (
                                                            $targetLevel = $level + 1;
                                                            $targetLevel <= $sub['target_level'];
                                                            $targetLevel++
                                                        ) {
                                                            $rekomendasi = getRekomendasiPelatihan(
                                                                $sub['sub_indikator_id'],
                                                                'Tahap 1',
                                                                'Guru',
                                                                $sub['sub_indikator_code'],
                                                                $level,
                                                                $targetLevel,
                                                            );
                                                            $rekomendasiPelatihan[] = [
                                                                'level_target' => $targetLevel,
                                                                'rekomendasi' => $rekomendasi,
                                                            ];
                                                        }
                                                    }
                                                @endphp

                                                @if ($hasPtk)
                                                    <div class="level-section">
                                                        <!-- Header Level -->
                                                        <div class="level-header-card">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="badge-level"
                                                                        style="background-color: {{ $levelColors[$level] ?? '#17a2b8' }}; color: white;">
                                                                        Level {{ $level }}
                                                                        ({{ $levelNames[$level] ?? 'Penerapan' }})
                                                                    </span>
                                                                    <span class="ms-3 fw-bold">
                                                                        <i class="ri-user-line"></i>
                                                                        {{ $levelData['ptk_count'] }} PTK
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    @if ($hasGap)
                                                                        <span class="badge-gap me-2">
                                                                            Gap: +{{ $sub['target_level'] - $level }} level
                                                                        </span>
                                                                    @endif
                                                                    <span
                                                                        class="badge bg-warning">{{ $levelData['status'] }}</span>
                                                                </div>
                                                            </div>

                                                            <!-- Tampilkan rekomendasi pelatihan jika ada (DITAMPILKAN PENUH) -->
                                                            @if (!empty($rekomendasiPelatihan))
                                                                <div class="rekomendasi-pelatihan mt-3">
                                                                    <h6 class="mb-2">
                                                                        <i class="ri-lightbulb-line text-warning"></i>
                                                                        Rekomendasi Kebutuhan Belajar:
                                                                    </h6>
                                                                    <div class="rekomendasi-container">
                                                                        <ul>
                                                                            @foreach ($rekomendasiPelatihan as $rek)
                                                                                <li>
                                                                                    <strong>Level
                                                                                        {{ $rek['level_target'] }}:</strong>
                                                                                    <span
                                                                                        style="white-space: normal !important;">
                                                                                        {{ $rek['rekomendasi'] }}
                                                                                    </span>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Tabel PTK per Level -->
                                                        <div class="level-table-card">
                                                            <div class="ptk-table-container">
                                                                <table class="ptk-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="3%">No</th>
                                                                            <th width="12%">NIP</th>
                                                                            <th width="18%">Nama</th>
                                                                            <th width="12%">No. HP</th>
                                                                            <th width="20%">Sekolah/Instansi</th>
                                                                            <th width="10%">Kota</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $startNumber =
                                                                                ($levelData['ptk_list']->currentPage() -
                                                                                    1) *
                                                                                    $levelData['ptk_list']->perPage() +
                                                                                1;
                                                                        @endphp
                                                                        @foreach ($levelData['ptk_list'] as $ptkIndex => $ptk)
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    {{ $startNumber + $ptkIndex }}</td>
                                                                                <td>{{ $ptk['nip'] ?? '-' }}</td>
                                                                                <td><strong>{{ $ptk['nama'] ?? '-' }}</strong>
                                                                                </td>
                                                                                <td>
                                                                                    @if (!empty($ptk['no_hp']))
                                                                                        <a
                                                                                            href="tel:{{ $ptk['no_hp'] }}">{{ $ptk['no_hp'] }}</a>
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if ($ptk['sekolah'])
                                                                                        {{ $ptk['sekolah'] }}
                                                                                    @elseif($ptk['instansi'])
                                                                                        {{ $ptk['instansi'] }}
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $ptk['kota'] ?? '-' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Pagination untuk tabel per level -->
                                                            @if ($levelData['ptk_list']->hasPages())
                                                                <div class="mt-3">
                                                                    <nav aria-label="Pagination">
                                                                        <ul
                                                                            class="pagination pagination-sm justify-content-center mb-0">
                                                                            {{-- Previous Page Link --}}
                                                                            @if ($levelData['ptk_list']->onFirstPage())
                                                                                <li class="page-item disabled">
                                                                                    <span class="page-link">&laquo;</span>
                                                                                </li>
                                                                            @else
                                                                                <li class="page-item">
                                                                                    <a class="page-link"
                                                                                        href="{{ $levelData['ptk_list']->previousPageUrl() }}"
                                                                                        rel="prev">&laquo;</a>
                                                                                </li>
                                                                            @endif

                                                                            {{-- Pagination Elements --}}
                                                                            @php
                                                                                $currentPage = $levelData[
                                                                                    'ptk_list'
                                                                                ]->currentPage();
                                                                                $lastPage = $levelData[
                                                                                    'ptk_list'
                                                                                ]->lastPage();
                                                                                $start = max(1, $currentPage - 2);
                                                                                $end = min($lastPage, $currentPage + 2);
                                                                            @endphp

                                                                            @for ($i = $start; $i <= $end; $i++)
                                                                                @if ($i == $currentPage)
                                                                                    <li class="page-item active">
                                                                                        <span
                                                                                            class="page-link">{{ $i }}</span>
                                                                                    </li>
                                                                                @else
                                                                                    <li class="page-item">
                                                                                        <a class="page-link"
                                                                                            href="{{ $levelData['ptk_list']->url($i) }}">{{ $i }}</a>
                                                                                    </li>
                                                                                @endif
                                                                            @endfor

                                                                            {{-- Next Page Link --}}
                                                                            @if ($levelData['ptk_list']->hasMorePages())
                                                                                <li class="page-item">
                                                                                    <a class="page-link"
                                                                                        href="{{ $levelData['ptk_list']->nextPageUrl() }}"
                                                                                        rel="next">&raquo;</a>
                                                                                </li>
                                                                            @else
                                                                                <li class="page-item disabled">
                                                                                    <span class="page-link">&raquo;</span>
                                                                                </li>
                                                                            @endif
                                                                        </ul>
                                                                        <div class="text-center">
                                                                            <small class="text-muted">
                                                                                Menampilkan
                                                                                {{ $levelData['ptk_list']->firstItem() ?? 0 }}
                                                                                -
                                                                                {{ $levelData['ptk_list']->lastItem() ?? 0 }}
                                                                                dari {{ $levelData['ptk_list']->total() }}
                                                                                PTK
                                                                            </small>
                                                                        </div>
                                                                    </nav>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                        </div>
                                    @elseif($level < $sub['target_level'])
                                        <!-- Level tanpa PTK tapi ada gap -->
                                        <div class="level-section">
                                            <div class="level-header-card">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="badge-level"
                                                            style="background-color: {{ $levelColors[$level] ?? '#17a2b8' }}; color: white;">
                                                            Level {{ $level }}
                                                            ({{ $levelNames[$level] ?? 'Penerapan' }})
                                                        </span>
                                                        <span class="ms-3">
                                                            <span class="badge-gap">
                                                                Gap: +{{ $sub['target_level'] - $level }} level
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-secondary">TIDAK ADA PTK</span>
                                                    </div>
                                                </div>

                                                <!-- Tampilkan rekomendasi pelatihan untuk level tanpa PTK (DITAMPILKAN PENUH) -->
                                                @php
                                                    // Buat rekomendasi pelatihan dinamis
                                                    $rekomendasiPelatihan = [];
                                                    for (
                                                        $targetLevel = $level + 1;
                                                        $targetLevel <= $sub['target_level'];
                                                        $targetLevel++
                                                    ) {
                                                        $rekomendasi = getRekomendasiPelatihan(
                                                            $sub['sub_indikator_id'],
                                                            'Tahap 1',
                                                            'Guru',
                                                            $sub['sub_indikator_code'],
                                                            $level,
                                                            $targetLevel,
                                                        );
                                                        $rekomendasiPelatihan[] = [
                                                            'level_target' => $targetLevel,
                                                            'rekomendasi' => $rekomendasi,
                                                        ];
                                                    }
                                                @endphp

                                                @if (!empty($rekomendasiPelatihan))
                                                    <div class="rekomendasi-pelatihan mt-3">
                                                        <h6 class="mb-2">
                                                            <i class="ri-lightbulb-line text-warning"></i>
                                                            Rekomendasi Kebutuhan Belajar:
                                                        </h6>
                                                        <div class="rekomendasi-container">
                                                            <ul>
                                                                @foreach ($rekomendasiPelatihan as $rek)
                                                                    <li>
                                                                        <strong>Level
                                                                            {{ $rek['level_target'] }}:</strong>
                                                                        <span style="white-space: normal !important;">
                                                                            {{ $rek['rekomendasi'] }}
                                                                        </span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="empty-level">
                                                <i class="ri-user-unfollow-line"></i>
                                                <p class="mt-2 mb-0">Tidak ada PTK di level ini</p>
                                                <small class="text-muted">Level {{ $level }}
                                                    membutuhkan
                                                    peningkatan ke level {{ $sub['target_level'] }}</small>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        @endif
                    @endforeach
            </div>
            @endif
            @endforeach

            <!-- Ringkasan Semua PTK -->
            <div class="sub-indikator-card mt-4">
                <div class="sub-indikator-header">
                    <h5 class="mb-0">
                        <i class="ri-group-line"></i> Ringkasan Semua PTK dengan Kebutuhan Belajar
                    </h5>
                    <div>
                        <span class="badge bg-primary">{{ $analisisData['total_unique_ptk'] }} PTK</span>
                        <span class="badge bg-info ms-2">20 data per halaman</span>
                    </div>
                </div>

                <div class="ptk-table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th width="12%">NIP</th>
                                <th width="18%">Nama</th>
                                <th width="12%">No. HP</th>
                                <th width="15%">Jenjang</th>
                                <th width="15%">Sekolah/Instansi</th>
                                <th width="10%">Kota</th>
                                <th width="10%">Jumlah Gap</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($analisisData['all_ptks_paginated'] as $ptk)
                                <tr>
                                    <td class="text-center">{{ $ptk['no'] }}</td>
                                    <td>{{ $ptk['nip'] ?? '-' }}</td>
                                    <td><strong>{{ $ptk['nama'] }}</strong></td>
                                    <td>
                                        @if ($ptk['no_hp'] != '-')
                                            <a href="tel:{{ $ptk['no_hp'] }}">{{ $ptk['no_hp'] }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $ptk['jenjang'] }}</td>
                                    <td>{{ $ptk['sekolah'] }}</td>
                                    <td>{{ $ptk['kota'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $ptk['gap_count'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination untuk ringkasan semua PTK -->
                @if ($analisisData['all_ptks_paginated']->hasPages())
                    <div class="mt-3">
                        <nav aria-label="Pagination Ringkasan">
                            <ul class="pagination pagination-sm justify-content-center mb-0">
                                {{-- Previous Page Link --}}
                                @if ($analisisData['all_ptks_paginated']->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $analisisData['all_ptks_paginated']->previousPageUrl() }}"
                                            rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @php
                                    $currentPage = $analisisData['all_ptks_paginated']->currentPage();
                                    $lastPage = $analisisData['all_ptks_paginated']->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp

                                @for ($i = $start; $i <= $end; $i++)
                                    @if ($i == $currentPage)
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $analisisData['all_ptks_paginated']->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($analisisData['all_ptks_paginated']->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $analisisData['all_ptks_paginated']->nextPageUrl() }}"
                                            rel="next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                            <div class="text-center">
                                <small class="text-muted">
                                    Menampilkan {{ $analisisData['all_ptks_paginated']->firstItem() ?? 0 }} -
                                    {{ $analisisData['all_ptks_paginated']->lastItem() ?? 0 }} dari
                                    {{ $analisisData['all_ptks_paginated']->total() }} PTK
                                </small>
                            </div>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Tidak ada gap -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="ri-checkbox-circle-line" style="font-size: 5rem; color: #28a745;"></i>
            </div>
            <h4 class="mb-3" style="color: #28a745;">Tidak Ada Kebutuhan Belajar</h4>
            <p class="text-muted mb-4">
                Semua PTK sudah mencapai target level berdasarkan filter yang diterapkan
            </p>
            <a href="{{ route('analisis.index') }}" class="btn btn-primary">
                <i class="ri-arrow-left-line"></i> Kembali ke Analisis
            </a>
        </div>
        @endif
    </div>
    </div>
    </div>

@endsection

@section('sipproja-js')
    <script>
        // Script untuk highlight gap
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight level dengan gap besar
            document.querySelectorAll('.badge-gap').forEach(badge => {
                const gapText = badge.textContent;
                const gapNumber = parseInt(gapText.match(/\d+/)[0]);

                if (gapNumber >= 3) {
                    badge.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
                    badge.style.boxShadow = '0 2px 4px rgba(220, 53, 69, 0.3)';
                } else if (gapNumber == 2) {
                    badge.style.background = 'linear-gradient(135deg, #fd7e14, #e8590c)';
                    badge.style.boxShadow = '0 2px 4px rgba(253, 126, 20, 0.3)';
                } else {
                    badge.style.background = 'linear-gradient(135deg, #ffc107, #e0a800)';
                    badge.style.boxShadow = '0 2px 4px rgba(255, 193, 7, 0.3)';
                }
            });

            // Add hover effect to table rows
            document.querySelectorAll('.ptk-table tbody tr').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f0f8ff';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>
@endsection
