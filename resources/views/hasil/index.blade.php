@extends('layouts.main')
@section('mycontent')
@php
$tittle = $tittle ?? 'Hasil Instrumen PTK';
$kegiatans = DB::table('kegiatan')->get();

$levelNames = [
    2 => 'Penerapan',
    3 => 'Analisis', 
    4 => 'Evaluasi',
    5 => 'Pembimbingan'
];

$levelColors = [
    2 => 'info',
    3 => 'primary',
    4 => 'warning',
    5 => 'success'
];

$statusColors = [
    'Mencapai Semua Level' => 'success',
    'Mendekati Target' => 'warning',
    'Perlu Peningkatan' => 'danger'
];
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Daftar {{ $tittle }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $tittle }}</a></li>
                        <li class="breadcrumb-item active">Daftar {{ $tittle }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="ticketsList">
                <div class="card-header border-0">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="card-title mb-0 flex-grow-1">{{ $tittle }}</h5>
                            <div class="flex-shrink-0">
                                @if($data->isNotEmpty())
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-download-line align-bottom me-1"></i> Export
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('hasil-instrumen.export-all', request()->query()) }}">
                                                    <i class="ri-file-pdf-line align-bottom me-2"></i> Export PDF Semua
                                                </a>
                                                <a class="dropdown-item" href="{{ route('hasil-instrumen.export-excel', request()->query()) }}">
                                                    <i class="ri-file-excel-line align-bottom me-2"></i> Export Excel
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Search Form -->
                        <form action="{{ route('hasil-instrumen.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Cari Nama/NIP PTK/Sub Indikator...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="kegiatan_id">
                                    <option value="">Semua Kegiatan</option>
                                    @foreach($kegiatans as $kegiatan)
                                    <option value="{{ $kegiatan->kegiatan_id }}"
                                        {{ request('kegiatan_id') == $kegiatan->kegiatan_id ? 'selected' : '' }}>
                                        {{ $kegiatan->kegiatan_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="tahap">
                                    <option value="">Semua Tahap</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}"
                                        {{ request('tahap') == $i ? 'selected' : '' }}>
                                        Tahap {{ $i }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-search-line align-bottom me-1"></i> Cari
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if($data->isEmpty())
                    <div class="alert alert-info">
                        @if(request()->hasAny(['search', 'kegiatan_id', 'tahap']))
                        Tidak ada data ditemukan dengan filter yang diterapkan.
                        @else
                        Tidak ada data ditemukan.
                        @endif
                    </div>
                    @else
                    <!-- Summary Info -->
                    @if(request()->hasAny(['search', 'kegiatan_id', 'tahap']))
                    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                        <i class="ri-information-line me-2"></i>
                        Menampilkan {{ $data->total() }} data
                        @if(request('search'))
                        dengan pencarian: "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('kegiatan_id'))
                        | Kegiatan: <strong>{{ $kegiatans->where('kegiatan_id', request('kegiatan_id'))->first()->kegiatan_name ?? '' }}</strong>
                        @endif
                        @if(request('tahap'))
                        | Tahap: <strong>{{ request('tahap') }}</strong>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap mb-0" id="ticketTable">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jenjang</th>
                                    <th>Sub Indikator</th>
                                    <th>Level Dicapai</th>
                                    <th>Level Harus</th>
                                    <th>Status</th>
                                    <th>Rekomendasi (GAP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $row)
                                @php
                                $info = $row->rekomendasi_info ?? null;
                                $jenjang = $row->jenjang_jabatan ?? '-';
                                $levelJawaban = $row->level_jawaban ?? 0;
                                $levelMin = $info['level_min'] ?? 0;
                                $levelMax = $info['level_max'] ?? 0;
                                $status = $info['status'] ?? '-';
                                $statusClass = $info['status_class'] ?? 'secondary';
                                $rekomendasiGap = $info['rekomendasi_gap'] ?? [];
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $row->nama }}</strong><br>
                                        <small class="text-muted">{{ $row->instansi }}</small>
                                    </td>
                                    <td>{{ $row->nip }}</td>
                                    <td>
                                        <span class="fw-medium">{{ $jenjang }}</span><br>
                                        <small class="text-muted">Level {{ $levelMin }}-{{ $levelMax }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $row->sub_indikator_name }}</div>
                                        <small class="text-muted">{{ $row->sub_indikator_code }}</small>
                                    </td>
                                    <td>
                                        @if($levelJawaban > 0)
                                        <span class="badge bg-{{ $levelColors[$levelJawaban] ?? 'secondary' }}-subtle text-{{ $levelColors[$levelJawaban] ?? 'secondary' }}">
                                            Level {{ $levelJawaban }}
                                        </span><br>
                                        <small class="text-muted">{{ $levelNames[$levelJawaban] ?? '' }}</small>
                                        @else
                                        <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @for($i = $levelMin; $i <= $levelMax; $i++)
                                                <span class="badge bg-{{ $levelColors[$i] ?? 'secondary' }}-subtle text-{{ $levelColors[$i] ?? 'secondary' }} mb-1" style="font-size: 0.75rem;">
                                                    Level {{ $i }}
                                                </span>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}">
                                            {{ $status }}
                                        </span><br>
                                        <small class="text-muted">
                                            {{ $info['level_dicapai_count'] ?? 0 }}/{{ $info['total_level'] ?? 0 }} level
                                        </small>
                                    </td>
                                    <td>
                                        @if(count($rekomendasiGap) > 0)
                                        <div class="rekomendasi-gap">
                                            @foreach($rekomendasiGap as $rek)
                                            <div class="mb-2 p-2 border rounded bg-light">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        Gap Level {{ $rek['level'] }}
                                                    </span>
                                                    <small class="text-muted">{{ $levelNames[$rek['level']] ?? '' }}</small>
                                                </div>
                                                <div class="small">
                                                    {{ Str::limit($rek['rekomendasi'], 150) }}
                                                    @if(strlen($rek['rekomendasi']) > 150)
                                                    <a href="javascript:void(0);" class="text-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="{{ $rek['rekomendasi'] }}">
                                                        ...selengkapnya
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <div class="text-center">
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ri-check-line me-1"></i> Sudah mencapai semua level
                                            </span>
                                            @if(isset($info['rekomendasi_dicapai']) && count($info['rekomendasi_dicapai']) > 0)
                                            <div class="mt-2 small text-muted">
                                                <em>Telah mencapai level {{ $levelMax }}</em>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{-- Pagination --}}
                        {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                    
                   
                                        
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('sipproja-js')
<script>
    // Tooltip untuk rekomendasi
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection