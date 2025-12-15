@extends('layouts.main')
@section('mycontent')
@php
$tittle = $tittle ?? 'Hasil Instrumen PTK';
// Ambil data kegiatan untuk dropdown langsung dari database
$kegiatans = DB::table('kegiatan')->get();

// Definisikan nama level
$levelNames = [
1 => 'Sangat Rendah',
2 => 'Penerapan',
3 => 'Analisis',
4 => 'Evaluasi',
5 => 'Pembimbingan Rekan Sejawat'
];
@endphp

<div class="container-fluid">
    <!-- start page title -->
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
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="ticketsList">
                <!-- Card Header dengan Search Form dan Export Buttons -->
                <div class="card-header border-0">
                    <div class="d-flex flex-column">
                        <!-- Title dan Action Buttons -->
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

                <!--end card-body-->
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
                                    <th scope="col" style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th class="sort" data-sort="id">ID</th>
                                    <th class="sort" data-sort="kota">Kota</th>
                                    <th class="sort" data-sort="nama">Nama</th>
                                    <th class="sort" data-sort="nip">NIP</th>
                                    <th class="sort" data-sort="jabatan">Jabatan</th>
                                    <th class="sort" data-sort="instrumen">Kegiatan/Instrumen</th>
                                    <th class="sort" data-sort="sub_indikator_code">Sub Indikator Code</th>
                                    <th class="sort" data-sort="sub_indikator_name">Sub Indikator Name</th>
                                    <th class="sort" data-sort="level">Level</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all" id="ticket-list-data">
                                @foreach ($data as $row)
                                @php
                                // Data langsung dari query join
                                $kota = $row->nama_kota ?? '-';
                                $nama = $row->nama_ptk ?? $row->nama ?? '-';
                                $nip = $row->nip ?? '-';
                                $jabatan = $row->pangkat_jabatan_id ?? '-';
                                $kegiatan_name = $row->kegiatan_name ?? '-';
                                $tahap = $row->tahap ?? '0';
                                $level = $row->level ?? '0';
                                $sub_indikator_code = $row->sub_indikator_code ?? '-';
                                $sub_indikator_name = $row->sub_indikator_name ?? '-';
                                $levelName = $levelNames[$level] ?? 'Tidak Diketahui';
                                @endphp
                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="checkAll" value="{{ $row->ptk_jawaban_id }}">
                                        </div>
                                    </th>
                                    <td class="id">
                                        {{ $row->ptk_jawaban_id }}
                                    </td>
                                    <td class="kota">{{ $kota }}</td>
                                    <td class="nama">{{ $nama }}</td>
                                    <td class="nip">{{ $nip }}</td>
                                    <td class="jabatan">
                                        @if(!empty($row->pangkat))
                                        {{ $row->pangkat }}
                                        @if(!empty($row->jenjang_jabatan))
                                        <br><small class="text-muted">{{ $row->jenjang_jabatan }}</small>
                                        @endif
                                        @if(!empty($row->golongan_ruang))
                                        <br><small class="text-muted">Gol. {{ $row->golongan_ruang }}</small>
                                        @endif
                                        @else
                                        -
                                        @endif
                                    </td>

                                    <td class="instrumen">{{ $kegiatan_name }}</td>
                                    <td class="sub_indikator_code">{{ $sub_indikator_code }}</td>
                                    <td class="sub_indikator_name">
                                        <div class="fw-medium">{{ $sub_indikator_name }}</div>
                                    </td>
                                    <td class="level">
                                        @php
                                        $levelColors = [
                                        1 => 'info',
                                        2 => 'info',
                                        3 => 'info',
                                        4 => 'info',
                                        5 => 'info'
                                        ];
                                        $color = $levelColors[$level] ?? 'secondary';
                                        @endphp
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge bg-{{ $color }}-subtle text-{{ $color }} mb-1">
                                                Level {{ $level }}
                                            </span>
                                            <small class="text-muted">{{ $levelName }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Pagination --}}
                        {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>


                    @endif


                    <!--end delete modal -->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
@endsection

@section('sipproja-js')
<script>
    // NOTIFIKASI SWEETALERT DARI CONTROLLER
    @if(session('success'))
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    @endif

    @if(session('error'))
    Swal.fire({
        position: 'center',
        icon: 'error',
        title: '{{ session("error") }}',
        showConfirmButton: true
    });
    @endif

    // Tooltip untuk level
    document.addEventListener('DOMContentLoaded', function() {
        var levelBadges = document.querySelectorAll('.level .badge');
        levelBadges.forEach(function(badge) {
            badge.setAttribute('title', 'Klik untuk info detail');
        });
    });
</script>
@endsection