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
@endphp

<style>
    :root{
        --mm-blue:#1a5bb8;
        --mm-soft:#f6f9ff;
        --mm-text:#1f2937;
        --mm-muted:#6b7280;
        --mm-line:#e5e7eb;
        --mm-card:#ffffff;
        --mm-shadow: 0 10px 24px rgba(17,24,39,.10);
        --mm-shadow2: 0 6px 16px rgba(26,91,184,.12);
        --radius: 16px;
    }

    /* ✅ NO GRADIENT */
    .hi-wrap{
        background: #f3f7ff;
        border-radius: 18px;
        padding: 18px;
    }

    /* =========================
       HEADER: motif baduy repeat + overlay SOLID
       ========================= */
    .hi-head{
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        padding: 22px 24px;
        margin-bottom: 14px;

        background: var(--mm-blue);
        border: 1px solid rgba(255,255,255,.20);
        box-shadow: 0 10px 24px rgba(17,24,39,.12);
    }

    .hi-head::before{
        content:"";
        position:absolute;
        inset:0;
        background-image: url("{{ asset('build/images/baduy.jpg') }}");
        background-repeat: repeat;
        background-size: 140px auto;
        background-position: center;
        opacity: .55;
        filter: grayscale(100%) contrast(1.15);
        z-index: 0;
    }

    .hi-head::after{
        content:"";
        position:absolute;
        inset:0;
        background: rgba(26,91,184,.45);
        z-index: 1;
    }

    .hi-head > *{ position: relative; z-index: 2; }

    /* ✅ teks header putih */
    .hi-head, .hi-head *{ color:#fff !important; }

    .hi-head h5{
        margin:0;
        font-size: 18px !important;   /* ✅ judul lebih besar */
        font-weight: 900;
        letter-spacing:.2px;
        text-shadow: 0 2px 12px rgba(0,0,0,.35) !important;
    }

    .hi-head .meta{
        font-size: 12.5px;
        opacity: .95;
        margin-top: 6px;
        color: rgba(255,255,255,.92) !important;
        text-shadow: 0 2px 12px rgba(0,0,0,.35) !important;
    }

    /* ✅ ICON JUDUL: jangan bg putih (ganti jadi glass/transparent) */
    .hi-title-icon{
        width: 40px;
        height: auto;
        border-radius: 12px;
        color: #fff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        backdrop-filter: blur(6px);
    }
    .hi-title-icon i{ font-size: 18px; }

    /* Filter card */
    .hi-filter{
        border-radius: var(--radius);
        background: var(--mm-card);
        border: 1px solid rgba(229,231,235,.9);
        box-shadow: 0 6px 18px rgba(17,24,39,.06);
        padding: 14px;
        margin-bottom: 14px;
    }
    .hi-filter .form-label{
        font-weight: 800;
        color: var(--mm-text);
        margin-bottom: 6px;
        font-size: 13px;
    }
    .hi-filter .form-control,
    .hi-filter .form-select{
        border-radius: 12px;
        height: 44px;
        font-weight: 500;
    }
    .hi-filter .btn{
        border-radius: 14px;
        height: 44px;
        font-weight: 900;
    }

    /* Tombol Cari jadi warna #1a5bb8 */
    .btn-cari{
        background: #1a5bb8 !important;
        border-color: #1a5bb8 !important;
        color: #fff !important;
    }

    /* Hover */
    .btn-cari:hover{
        background: #154a93 !important;
        border-color: #154a93 !important;
        color: #fff !important;
    }

    /* Focus */
    .btn-cari:focus{
        box-shadow: 0 0 0 .25rem rgba(26,91,184,.25) !important;
    }

    /* ✅ Export pill (dibikin gede & rounded) */
    .btn-export-pill{
        border-radius: 18px !important;
        padding: 12px 18px !important;
        font-weight: 900 !important;
        box-shadow: 0 10px 18px rgba(0,0,0,.10);
    }

    /* List cards */
    .hi-list{ display:flex; flex-direction:column; gap:12px; }

    .hi-item{
        border-radius: var(--radius);
        background: var(--mm-card);
        border: 1px solid rgba(229,231,235,.95);
        box-shadow: var(--mm-shadow);
        overflow: hidden;
    }

    .hi-item-top{
        display:flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 14px;
        border-bottom: 1px solid rgba(229,231,235,.9);
        background: #f6f9ff;
        gap: 12px;
        flex-wrap: wrap;
    }

    .hi-no{
        width: 42px; height: 42px;
        border-radius: 14px;
        background: rgba(26,91,184,.12);
        color: var(--mm-blue);
        display:flex; align-items:center; justify-content:center;
        font-weight: 900;
        flex: 0 0 auto;
    }

    .hi-status{
        display:flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        min-width: 170px;
    }
    .hi-status .badge{
        border-radius: 999px;
        padding: 8px 12px;
        font-weight: 900;
        font-size: 12px;
        white-space: nowrap;
    }
    .hi-status small{
        color: var(--mm-muted);
        font-weight: 700;
        white-space: nowrap;
    }

    .hi-item-body{ padding: 14px; }

    .hi-identity{
        border: 1px solid rgba(229,231,235,.95);
        border-radius: 14px;
        background: var(--mm-soft);
        padding: 12px 12px;
        height: 100%;
    }
    .hi-identity .nm{
        font-weight: 900;
        color: var(--mm-text);
        font-size: 15px;
        line-height: 1.25;
        margin-bottom: 8px;
    }
    .hi-identity .kv{
        display:flex;
        gap: 10px;
        padding: 6px 0;
        border-top: 1px dashed rgba(107,114,128,.25);
    }
    .hi-identity .kv:first-of-type{ border-top:none; padding-top:0; }
    .hi-identity .k{
        width: 62px;
        color: var(--mm-muted);
        font-weight: 900;
        font-size: 12px;
        flex: 0 0 auto;
    }
    .hi-identity .v{
        color: var(--mm-text);
        font-weight: 500; /* ✅ jangan bold */
        font-size: 12.5px;
        flex: 1;
        word-break: break-word;
    }
    .hi-identity .inst{
        margin-top: 8px;
        color: var(--mm-muted);
        font-weight: 700;
        font-size: 12px;
    }

    .hi-box{
        border: 1px solid rgba(229,231,235,.95);
        border-radius: 14px;
        background: #fff;
        padding: 12px;
        height: 100%;
    }
    .hi-box .ttl{
        font-weight: 900;
        color: var(--mm-text);
        font-size: 12px;
        letter-spacing: .2px;
        margin-bottom: 8px;
        display:flex;
        align-items:center;
        gap: 8px;
    }
    .hi-box .subttl{
        color: var(--mm-muted);
        font-weight: 700;
        font-size: 12px;
        margin-top: 6px;
    }

    .hi-levels{ display:flex; flex-wrap:wrap; gap:8px; }

    .hi-rek{
        border-radius: 12px;
        border: 1px solid rgba(229,231,235,.95);
        background: var(--mm-soft);
        padding: 10px;
    }
    .hi-rek .top{
        display:flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 6px;
    }
    .hi-rek .desc{
        color: var(--mm-text);
        font-weight: 500;
        font-size: 12.5px;
        line-height: 1.35;
    }

    .pagination{ margin-bottom: 0; }
    .page-link{ border-radius: 10px !important; font-weight: 800; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Daftar {{ $tittle }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $tittle }}</a></li>
                        <li class="breadcrumb-item active">Daftar {{ $tittle }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="hi-wrap">
        {{-- HEADER (EXPORT DIHILANGKAN DARI SINI) --}}
        <div class="hi-head d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    {{-- ✅ icon ganti: no bg putih --}}
                    <span class="hi-title-icon"><i class="ri-bar-chart-2-line"></i></span>
                    {{ $tittle }}
                </h5>
                <div class="meta">
                    @if($data->isNotEmpty())
                        Menampilkan {{ $data->total() }} data
                    @else
                        Tidak ada data
                    @endif
                </div>
            </div>
        </div>

        {{-- FILTER + EXPORT DITARO DI ATAS TOMBOL CARI --}}
        <div class="hi-filter">
            <form action="{{ route('hasil-instrumen.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control" name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari Nama/NIP PTK/Sub Indikator...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kegiatan</label>
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
                    <label class="form-label">Tahap</label>
                    <select class="form-select" name="tahap">
                        <option value="">Semua Tahap</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ request('tahap') == $i ? 'selected' : '' }}>
                                Tahap {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- ✅ kolom aksi: export di atas, cari di bawah --}}
                <div class="col-md-2">
                    @if($data->isNotEmpty())
                    <div class="w-100 mb-2">
                        <a class="btn btn-success w-100 btn-export-pill"
                        href="{{ route('hasil-instrumen.export-all', request()->query()) }}">
                            <i class="ri-file-pdf-line align-bottom me-1"></i> Export PDF
                        </a>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 btn-cari">
                        <i class="ri-search-line align-bottom me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- EMPTY --}}
        @if($data->isEmpty())
            <div class="alert alert-info mb-0">
                @if(request()->hasAny(['search', 'kegiatan_id', 'tahap']))
                    Tidak ada data ditemukan dengan filter yang diterapkan.
                @else
                    Tidak ada data ditemukan.
                @endif
            </div>
        @else

            {{-- ALERT FILTER --}}
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

            {{-- LIST CARD --}}
            <div class="hi-list">
                @foreach ($data as $index => $row)
                    @php
                        $info = $row->rekomendasi_info ?? [];

                        $jenjang = $row->jenjang_jabatan ?? '-';
                        $levelJawaban = (int)($row->level_jawaban ?? 0);

                        $levelMin = (int)($info['level_min'] ?? 0);
                        $levelMax = (int)($info['level_max'] ?? 0);

                        $status = $info['status'] ?? '-';
                        $statusClass = $info['status_class'] ?? 'secondary';

                        $rekomendasiGap = $info['rekomendasi_gap'] ?? [];
                        $cid = 'rek_' . $loop->index . '_' . preg_replace('/[^a-zA-Z0-9]/', '', (string)($row->nip ?? 'x'));
                    @endphp

                    <div class="hi-item">
                        <div class="hi-item-top">
                            <div class="d-flex align-items-center" style="gap:12px;">
                                <div class="hi-no">{{ $data->firstItem() + $index }}</div>
                                <div>
                                    <div class="fw-bold" style="color:var(--mm-text);">
                                        {{ $row->sub_indikator_name }}
                                    </div>
                                    <div class="text-muted small fw-semibold">
                                        Kode: {{ $row->sub_indikator_code }}
                                    </div>
                                </div>
                            </div>

                            <div class="hi-status">
                                <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}">
                                    {{ $status }}
                                </span>
                                <small>
                                    {{ (int)($info['level_dicapai_count'] ?? 0) }}/{{ (int)($info['total_level'] ?? 0) }} level
                                </small>
                            </div>
                        </div>

                        <div class="hi-item-body">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="hi-identity">
                                        <div class="nm">{{ $row->nama }}</div>

                                        <div class="kv">
                                            <div class="k">NIP</div>
                                            <div class="v">{{ $row->nip }}</div>
                                        </div>
                                        <div class="kv">
                                            <div class="k">Jenjang</div>
                                            <div class="v">{{ $jenjang }}</div>
                                        </div>
                                        <div class="kv">
                                            <div class="k">Target</div>
                                            <div class="v">Level {{ $levelMin }}–{{ $levelMax }}</div>
                                        </div>

                                        <div class="inst">
                                            <i class="ri-building-4-line me-1"></i>{{ $row->instansi }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="hi-box">
                                        <div class="ttl">
                                            <i class="ri-award-line"></i> Level Dicapai
                                        </div>

                                        @if($levelJawaban > 0)
                                            <span class="badge bg-{{ $levelColors[$levelJawaban] ?? 'secondary' }}-subtle text-{{ $levelColors[$levelJawaban] ?? 'secondary' }} px-3 py-2"
                                                  style="border-radius:999px; font-weight:900;">
                                                Level {{ $levelJawaban }}
                                            </span>
                                            <div class="subttl">
                                                {{ $levelNames[$levelJawaban] ?? '' }}
                                            </div>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2" style="border-radius:999px;">-</span>
                                        @endif

                                        <hr style="border-color:rgba(229,231,235,.9);">

                                        <div class="ttl mb-1">
                                            <i class="ri-flag-line"></i> Level Harus
                                        </div>
                                        <div class="hi-levels">
                                            @for($i = $levelMin; $i <= $levelMax; $i++)
                                                <span class="badge bg-{{ $levelColors[$i] ?? 'secondary' }}-subtle text-{{ $levelColors[$i] ?? 'secondary' }}"
                                                      style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                                    Level {{ $i }}
                                                </span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="hi-box">
                                        <div class="ttl">
                                            <i class="ri-lightbulb-flash-line"></i> Rekomendasi (GAP)
                                        </div>

                                        @if(count($rekomendasiGap) > 0)
                                            <div class="d-grid gap-2">
                                                @foreach($rekomendasiGap as $rkIndex => $rek)
                                                    @php
                                                        $rekLevel = (int)($rek['level'] ?? 0);
                                                        $rekText  = (string)($rek['rekomendasi'] ?? '');
                                                        $short    = \Illuminate\Support\Str::limit($rekText, 160);
                                                        $needMore = strlen($rekText) > 160;
                                                        $collapseId = $cid . '_' . $rkIndex;
                                                    @endphp

                                                    <div class="hi-rek">
                                                        <div class="top">
                                                            <span class="badge bg-danger-subtle text-danger"
                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                                                Gap Level {{ $rekLevel }}
                                                            </span>
                                                            <small class="text-muted fw-semibold">
                                                                {{ $levelNames[$rekLevel] ?? '' }}
                                                            </small>
                                                        </div>

                                                        <div class="desc">
                                                            {{ $short }}
                                                        </div>

                                                        @if($needMore)
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-primary mt-2"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#{{ $collapseId }}"
                                                                    aria-expanded="false"
                                                                    style="border-radius:10px; font-weight:900;">
                                                                Selengkapnya
                                                            </button>
                                                            <div class="collapse mt-2" id="{{ $collapseId }}">
                                                                <div class="small text-muted fw-semibold" style="line-height:1.35;">
                                                                    {{ $rekText }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center p-2">
                                                <span class="badge bg-success-subtle text-success px-3 py-2" style="border-radius:999px; font-weight:900;">
                                                    <i class="ri-check-line me-1"></i> Sudah mencapai semua level
                                                </span>
                                                @if(isset($info['rekomendasi_dicapai']) && count($info['rekomendasi_dicapai']) > 0)
                                                    <div class="mt-2 small text-muted fw-semibold">
                                                        <em>Telah mencapai level {{ $levelMax }}</em>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        @endif
    </div>
</div>
@endsection

@section('sipproja-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
