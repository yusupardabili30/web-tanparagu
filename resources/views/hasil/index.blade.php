@extends('layouts.main')
@section('mycontent')

@php
    $tittle = $tittle ?? 'Hasil Instrumen PTK';
    $kegiatans = DB::table('kegiatan')->get();

    // ✅ map kegiatan_id => kegiatan_name (biar cepat & gak query di loop)
    $kegiatanMap = $kegiatans->pluck('kegiatan_name', 'kegiatan_id')->toArray();

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

    // ✅ Mapping jenjang/jabatan → level wajib
    $jenjangLevelMap = [
        'pertama' => [2],
        'pratama' => [2],
        'muda'    => [3],
        'madya'   => [4],
        'utama'   => [5],
    ];

    // ✅ Helper: ambil level wajib dari jenjang, fallback ke info min-max kalau gak kebaca
    $getWajibLevels = function($jenjang, $fallbackMin = 0, $fallbackMax = 0) use ($jenjangLevelMap) {
        $j = strtolower(trim((string)$jenjang));

        foreach ($jenjangLevelMap as $key => $levels) {
            if ($j !== '' && str_contains($j, $key)) {
                return $levels;
            }
        }

        $fallbackMin = (int)$fallbackMin;
        $fallbackMax = (int)$fallbackMax;

        if ($fallbackMin > 0 && $fallbackMax > 0 && $fallbackMax >= $fallbackMin) {
            return range($fallbackMin, $fallbackMax);
        }

        return [];
    };

    // ✅ Grouping per NIP (di page ini saja, karena paginator)
    $groups = $data->getCollection()->groupBy(function($r){
        return (string)($r->nip ?? 'tanpa_nip');
    });
@endphp

<style>
    :root{
        --mm-blue:#1a4d8e;
        --mm-soft:#f6f9ff;
        --mm-text:#1f2937;
        --mm-muted:#6b7280;
        --mm-line:#e5e7eb;
        --mm-card:#ffffff;
        --mm-shadow: 0 10px 24px rgba(17,24,39,.10);
        --mm-shadow2: 0 6px 16px rgba(26,91,184,.12);
        --radius: 16px;
    }

    .hi-wrap{
        background: #f3f7ff;
        border-radius: 18px;
        padding: 18px;
    }

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
    .hi-head, .hi-head *{ color:#fff !important; }

    .hi-head h5{
        margin:0;
        font-size: 18px !important;
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

    /* FILTER */
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
    .btn-cari{
        background: #1a4d8e !important;
        border-color: #1a4d8e !important;
        color: #fff !important;
    }
    .btn-cari:hover{
        background: #163f74 !important;
        border-color: #163f74 !important;
        color: #fff !important;
    }
    .btn-cari:focus{
        box-shadow: 0 0 0 .25rem rgba(26,91,184,.25) !important;
    }
    .btn-export-pill{
        border-radius: 18px !important;
        padding: 12px 18px !important;
        font-weight: 900 !important;
        box-shadow: 0 10px 18px rgba(0,0,0,.10);
    }

    /* LIST PER PTK */
    .hi-list{ display:flex; flex-direction:column; gap:14px; }

    .ptk-card{
        border-radius: 18px;
        background: #fff;
        border: 1px solid rgba(229,231,235,.95);
        box-shadow: var(--mm-shadow);
        overflow: hidden;
    }

    .ptk-head{
        padding: 14px;
        background: #fff;
        border-bottom: 1px solid rgba(229,231,235,.9);
    }

    .ptk-profile{
        border: 1px solid rgba(229,231,235,.95);
        background: #f6f9ff;
        border-radius: 16px;
        padding: 14px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .ptk-avatar{
        width: 44px;
        height: 44px;
        border-radius: 999px;
        background: rgba(26,91,184,.12);
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        color: var(--mm-blue);
    }
    .ptk-avatar i{ font-size: 20px; }

    .ptk-profile-body{ flex: 1; min-width: 0; }

    .ptk-name{
        font-size: 18px;
        font-weight: 900;
        color: var(--mm-text);
        margin: 0 0 8px 0;
        line-height: 1.2;
        word-break: break-word;
    }

    .ptk-lines{
        display: flex;
        flex-direction: column;
        gap: 6px;
        font-size: 14px;
    }

    .ptk-line{
        display: flex;
        gap: 10px;
        align-items: baseline;
        flex-wrap: wrap;
    }
    .ptk-line .k{
        width: 90px;
        color: var(--mm-muted);
        font-weight: 800;
    }
    .ptk-line .v{
        color: var(--mm-text);
        font-weight: 800;
        word-break: break-word;
        flex: 1;
        min-width: 200px;
    }

    .ptk-kegiatan{
        margin-top: 2px;
        margin-bottom: 8px;
        font-size: 20px;
        color: var(--mm-muted);
        font-weight: 800;
        display:flex;
        align-items:center;
        gap: 6px;
        word-break: break-word;
        padding-left: 10px;
    }

    .ptk-body{ padding: 14px; }

    .indikator-card{
        border: 1px solid rgba(229,231,235,.95);
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
    }

    .indikator-card .head{
        padding: 12px 14px;
        background: #1a4d8e !important;
        border-bottom: 1px solid rgba(255,255,255,.22) !important;
        display:flex;
        justify-content: space-between;
        align-items:center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .indikator-card .head .ttl,
    .indikator-card .head .ttl i{
        font-weight: 900;
        margin: 0;
        display:flex;
        align-items:center;
        gap: 8px;
        color:#fff !important;
    }
    .indikator-card .head .count{
        color: #fff !important;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.28);
        padding: 6px 10px;
        border-radius: 999px;
        font-weight: 900;
        font-size: 12px;
        white-space: nowrap;
    }

    .btn-toggle-indikator{
        border-radius: 12px !important;
        padding: 8px 10px !important;
        font-weight: 900 !important;
        background: rgba(255,255,255,.18) !important;
        border: 1px solid rgba(255,255,255,.28) !important;
        color: #fff !important;
        line-height: 1;
    }
    .btn-toggle-indikator:hover,
    .btn-toggle-indikator:active,
    .btn-toggle-indikator:focus{
        background: rgba(255,255,255,.28) !important;
        border-color: rgba(255,255,255,.36) !important;
        color: #fff !important;
        box-shadow: none !important;
    }
    .btn-toggle-indikator i{
        font-size: 18px;
        transition: transform .2s ease;
    }
    .btn-toggle-indikator.collapsed i{ transform: rotate(180deg); }

    .indikator-item{
        padding: 12px 14px;
        background: #fff;
        border-top: 1px dashed rgba(229,231,235,.9);
    }
    .indikator-item:first-of-type{ border-top: none; }

    .indikator-row{
        border: 1px solid rgba(229,231,235,.95);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 3px 15px rgba(26,75,184,.25);
        padding: 12px 14px;
    }
    .indikator-item + .indikator-item .indikator-row{ margin-top: 12px; }

    .indikator-grid{
        display:grid;
        grid-template-columns: 80px 250px 1.1fr 1.4fr;
        gap: 12px;
        align-items: start;
    }
    @media (max-width: 1200px){
        .indikator-grid{ grid-template-columns: 1fr; }
        .ptk-line .k{ width: 110px; }
        .ptk-line .v{ min-width: 0; }
    }

    .cell-title{
        font-weight: 900;
        color: var(--mm-muted);
        font-size: 12px;
        margin-bottom: 6px;
    }

    .no-box{
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: rgba(26,91,184,.12);
        color: var(--mm-blue);
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight: 900;
        font-size: 14px;
    }

    .lv-box{
        border: 1px solid rgba(229,231,235,.95);
        background: var(--mm-soft);
        border-radius: 14px;
        padding: 10px;
    }
    .lv-sub{
        color: var(--mm-muted);
        font-weight: 900;
        font-size: 12px;
        margin-top: 10px;
        display:flex;
        align-items:center;
        gap: 6px;
    }
    .lv-badges{ display:flex; flex-wrap:wrap; gap:8px; margin-top: 8px; }

    .ind-box{
        border: 1px solid rgba(229,231,235,.95);
        background: #fff;
        border-radius: 14px;
        padding: 10px;
    }
    .ind-name{
        font-weight: 900;
        color: var(--mm-text);
        font-size: 13.5px;
        line-height: 1.25;
        margin-bottom: 6px;
    }
    .ind-code{
        color: var(--mm-muted);
        font-weight: 800;
        font-size: 12px;
        display:flex;
        align-items:center;
        gap: 8px;
    }

    .rek-box{
        border: 1px solid rgba(229,231,235,.95);
        background: #fff;
        border-radius: 14px;
        padding: 10px;
    }
    .rek-item{
        border-radius: 12px;
        border: 1px solid rgba(229,231,235,.95);
        background: var(--mm-soft);
        padding: 10px;
    }
    .rek-item + .rek-item{ margin-top: 10px; }
    .rek-top{
        display:flex;
        justify-content: space-between;
        align-items:flex-start;
        gap: 10px;
        margin-bottom: 6px;
    }
    .rek-desc{
        color: var(--mm-text);
        font-weight: 500;
        font-size: 12.5px;
        line-height: 1.35;
    }

    /* SUMMARY */
    .summary-box{
        border: 1px solid rgba(229,231,235,.95);
        border-radius: 16px;
        background: #fff;
        padding: 12px 14px;
        margin-bottom: 12px;
        box-shadow: 0 6px 16px rgba(17,24,39,.06);
    }
    .summary-top{
        display:flex;
        align-items:flex-start;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .summary-title{
        font-weight: 900;
        color: var(--mm-text);
        margin: 0;
        display:flex;
        align-items:center;
        gap: 8px;
        font-size: 13.5px;
    }
    .summary-desc{
        margin-top: 6px;
        color: var(--mm-text);
        font-weight: 600;
        font-size: 12.8px;
        line-height: 1.45;
    }
    .summary-rek{
        margin-top: 10px;
        border-top: 1px dashed rgba(229,231,235,.9);
        padding-top: 10px;
    }

    .summary-sections{
        margin-top: 10px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    @media (max-width: 992px){
        .summary-sections{ grid-template-columns: 1fr; }
    }

    .summary-sec{
        border: 1px solid rgba(229,231,235,.9);
        border-radius: 14px;
        background: #fff;
        padding: 10px;
    }

    .summary-sec-head{
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
    }

    .summary-sec-title{
        font-weight: 900;
        color: var(--mm-text);
        display:flex;
        align-items:center;
        gap: 8px;
        font-size: 12.8px;
    }

    .summary-sec-note{
        color: var(--mm-muted);
        font-weight: 900;
        font-size: 12px;
        white-space: nowrap;
    }

    .summary-item{
        display:flex;
        align-items:flex-start;
        gap: 10px;
        padding: 8px;
        border-radius: 12px;
        background: var(--mm-soft);
        border: 1px solid rgba(229,231,235,.9);
    }
    .summary-item + .summary-item{ margin-top: 8px; }

    .summary-item .tx{
        flex: 1;
        font-size: 12.5px;
        line-height: 1.45;
        color: var(--mm-text);
        font-weight: 700;
    }
    .summary-item .tx small{
        display:block;
        margin-top: 3px;
        color: var(--mm-muted);
        font-weight: 800;
    }

    /* PELATIHAN */
    .pelatihan-section {
        margin-top: 12px;
        border-top: 1px dashed rgba(229,231,235,.7);
        padding-top: 12px;
    }
    .pelatihan-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(26,91,184,.08);
        border: 1px solid rgba(26,91,184,.2);
        color: var(--mm-blue);
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 800;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .pelatihan-badge i { font-size: 14px; }
    .pelatihan-kategori {
        font-size: 11px;
        color: var(--mm-muted);
        font-weight: 800;
        display: block;
        margin-top: 4px;
    }
    .no-pelatihan {
        color: var(--mm-muted);
        font-style: italic;
        font-size: 12.5px;
        font-weight: 500;
    }

    .page-link{ border-radius: 10px !important; font-weight: 800; }

    
</style>

<div class="container-fluid">
    {{-- TITLE --}}
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
        <div class="hi-head d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0 d-flex align-items-center gap-2">
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

        {{-- FILTER --}}
        <div class="hi-filter">
            <form action="{{ route('hasil-instrumen.index') }}" method="GET" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control" name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari Nama/NIP PTK/Sub Indikator...">
                </div>

                <div class="col-md-2">
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

                <div class="col-md-2">
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

                <div class="col-md-2">
                    <label class="form-label">Jenjang Jabatan</label>
                    <select class="form-select" name="pangkat_jabatan_id">
                        <option value="">Semua</option>
                        @foreach($pangkatJabatans as $pangkat)
                            <option value="{{ $pangkat->jenjang_jabatan }}"
                                {{ request('pangkat_jabatan_id') == $pangkat->jenjang_jabatan ? 'selected' : '' }}>
                                {{ $pangkat->jenjang_jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Jenis PTK</label>
                    <select class="form-select" name="jenis_ptk_id">
                        <option value="">Semua</option>
                        @foreach($jenisPtk as $jenis)
                            <option value="{{ $jenis->jenis_ptk_id }}"
                                {{ request('jenis_ptk_id') == $jenis->jenis_ptk_id ? 'selected' : '' }}>
                                {{ $jenis->jenis_ptk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    @if($data->isNotEmpty())
                        <div class="w-100 mb-2">
                            <a class="btn btn-success w-100 btn-export-pill"
                               href="{{ route('hasil-instrumen.export-all', request()->query()) }}"
                               title="Export ke PDF">
                                <i class="ri-file-pdf-line align-bottom"></i>
                            </a>
                        </div>
                        <div class="w-100 mb-2">
                            <a class="btn btn-warning w-100 btn-export-pill"
                               href="{{ route('hasil-instrumen.export-excel-all', request()->query()) }}"
                               title="Export ke Excel">
                                <i class="ri-file-excel-line align-bottom"></i>
                            </a>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 btn-cari" title="Cari Data">
                        <i class="ri-search-line align-bottom"></i>
                    </button>
                </div>

                @if($data->isEmpty())
                    <div class="alert alert-info mb-0 mt-2">
                        @if(request()->hasAny(['search', 'kegiatan_id', 'tahap', 'pangkat_jabatan_id', 'jenis_ptk_id']))
                            Tidak ada data ditemukan dengan filter yang diterapkan.
                        @else
                            Tidak ada data ditemukan.
                        @endif
                    </div>
                @else
                    @if(request()->hasAny(['search', 'kegiatan_id', 'tahap', 'pangkat_jabatan_id', 'jenis_ptk_id']))
                        <div class="alert alert-info alert-dismissible fade show mb-0 mt-2" role="alert">
                            <i class="ri-information-line me-2"></i>
                            Menampilkan {{ $data->total() }} data
                            @if(request('search'))
                                dengan pencarian: "<strong>{{ request('search') }}</strong>"
                            @endif
                            @if(request('kegiatan_id'))
                                | Kegiatan:
                                <strong>{{ $kegiatans->where('kegiatan_id', request('kegiatan_id'))->first()->kegiatan_name ?? '' }}</strong>
                            @endif
                            @if(request('tahap'))
                                | Tahap: <strong>{{ request('tahap') }}</strong>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                @endif

            </form>
        </div>

        @php
            $globalNo = $data->firstItem() ?? 1;
        @endphp

        @if($data->isNotEmpty())
            <div class="hi-list">
                @foreach($groups as $nipKey => $rows)
                    @php
                        $first = $rows->first();

                        $infoFirstRaw = $first->rekomendasi_info ?? [];
                        if (is_string($infoFirstRaw)) {
                            $infoFirst = json_decode($infoFirstRaw, true) ?: [];
                        } elseif (is_object($infoFirstRaw)) {
                            $infoFirst = (array) $infoFirstRaw;
                        } elseif ($infoFirstRaw instanceof \Illuminate\Support\Collection) {
                            $infoFirst = $infoFirstRaw->toArray();
                        } elseif (is_array($infoFirstRaw)) {
                            $infoFirst = $infoFirstRaw;
                        } else {
                            $infoFirst = [];
                        }

                        $jenjang = $first->jenjang_jabatan ?? '-';
                        $levelMinFirst = (int)($infoFirst['level_min'] ?? 0);
                        $levelMaxFirst = (int)($infoFirst['level_max'] ?? 0);

                        $wajibFirst = $getWajibLevels($jenjang, $levelMinFirst, $levelMaxFirst);
                        $targetLevel = (int)(count($wajibFirst) ? $wajibFirst[0] : 0);

                        $panelId = 'indikatorPanel_' . preg_replace('/[^a-zA-Z0-9]/', '', (string)$nipKey);

                        $nipKeyTrim = trim((string)$nipKey);
                        $sum = $summaryByNip[$nipKeyTrim] ?? null;

                        $totalIndikator = $sum ? (int)($sum['total'] ?? 0) : (int)$rows->unique('sub_indikator_id')->count();
                        $cntMeet        = $sum ? (int)($sum['memenuhi'] ?? 0) : 0;

                        if (!$sum) {
                            $meetSet = [];
                            foreach ($rows as $r) {
                                $lvlJawab = (int)($r->level_jawaban ?? 0);
                                $sid      = (string)($r->sub_indikator_id ?? '');
                                if ($targetLevel > 0 && $lvlJawab >= $targetLevel && $sid !== '') {
                                    $meetSet[$sid] = true;
                                }
                            }
                            $cntMeet = count($meetSet);
                        }

                        $cntNot = max(0, $totalIndikator - $cntMeet);
                        $isAllMeet = ($totalIndikator > 0 && $cntNot === 0);

                        // ✅ LIST UNTUK UI SUMMARY (TERCAPAI + REKOMENDASI)
                        $summaryRek = [];
                        $summaryAch = [];

                        foreach ($rows as $r) {
                            $lvlJawab = (int)($r->level_jawaban ?? 0);

                            // ✅ TERCAPAI: simpen juga "isi" rekomendasi level yang tercapai (kalau ada)
                            // (ambil dari rekomendasi_gap yang levelnya sama dengan level jawaban)
                            $infoRawSum = $r->rekomendasi_info ?? [];
                            if (is_string($infoRawSum)) {
                                $infoSum = json_decode($infoRawSum, true) ?: [];
                            } elseif (is_object($infoRawSum)) {
                                $infoSum = (array)$infoRawSum;
                            } elseif ($infoRawSum instanceof \Illuminate\Support\Collection) {
                                $infoSum = $infoRawSum->toArray();
                            } elseif (is_array($infoRawSum)) {
                                $infoSum = $infoRawSum;
                            } else {
                                $infoSum = [];
                            }

                            $rg = $infoSum['rekomendasi_gap'] ?? [];
                            if (is_string($rg)) {
                                $rg = json_decode($rg, true) ?: [];
                            } elseif (is_object($rg)) {
                                $rg = (array)$rg;
                            } elseif (!is_array($rg)) {
                                $rg = [];
                            }

                            $nm = trim((string)($r->sub_indikator_name ?? ''));
                            $cd = trim((string)($r->sub_indikator_code ?? ''));

                            // ✅ kalau tercapai (>= target), masuk "sudah tercapai"
                            if ($targetLevel > 0 && $lvlJawab >= $targetLevel) {
                                $achText = '';

                                // cari "isi" yang paling relevan untuk level jawaban (kalau ada)
                                foreach ($rg as $g) {
                                    $gl = (int)($g['level'] ?? 0);
                                    $gt = trim((string)($g['rekomendasi'] ?? ''));
                                    if ($gt !== '' && $gl === $lvlJawab) {
                                        $achText = $gt;
                                        break;
                                    }
                                }

                                if ($nm !== '') {
                                    $key = md5($nm.'|'.$cd.'|'.$achText);
                                    $summaryAch[$key] = [
                                        'level'       => $lvlJawab,
                                        'name'        => $nm,
                                        'code'        => $cd,
                                        'rekomendasi' => $achText, // ✅ isi ikut tampil (kalau ketemu)
                                    ];
                                }
                                continue;
                            }

                            // ✅ rekomendasi gap (untuk tampilan) -> level target
                            foreach ($rg as $g) {
                                $gl = (int)($g['level'] ?? 0);
                                $gt = trim((string)($g['rekomendasi'] ?? ''));

                                if ($gt !== '' && $targetLevel > 0 && $gl === $targetLevel) {
                                    $key = md5($gt.'|'.$nm.'|'.$cd);
                                    $summaryRek[$key] = [
                                        'level'       => $gl,
                                        'name'        => $nm,
                                        'code'        => $cd,
                                        'rekomendasi' => $gt,
                                    ];
                                }
                            }
                        }

                        $summaryRekList = array_values($summaryRek);
                        $summaryAchList = array_values($summaryAch);
                    @endphp

                    <div class="ptk-card">
                        <div class="ptk-head">
                            <div class="ptk-kegiatan">
                                <i class="ri-calendar-event-line"></i>
                                <span>{{ $kegiatanMap[$first->kegiatan_id] ?? '-' }}</span>
                            </div>

                            <div class="ptk-profile">
                                <div class="ptk-avatar">
                                    <i class="ri-user-3-line"></i>
                                </div>

                                <div class="ptk-profile-body">
                                    <div class="ptk-name">{{ $first->nama }}</div>

                                    <div class="ptk-lines">
                                        <div class="ptk-line">
                                            <div class="k">NIP</div>
                                            <div class="v">{{ $first->nip }}</div>
                                        </div>

                                        <div class="ptk-line">
                                            <div class="k">Jenjang</div>
                                            <div class="v">{{ $jenjang }}</div>
                                        </div>

                                        <div class="ptk-line">
                                            <div class="k">Capaian</div>
                                            <div class="v">
                                                @if(count($wajibFirst))
                                                    Level {{ implode(', ', $wajibFirst) }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- PELATIHAN --}}
                                    @if(isset($first->pelatihan) && $first->pelatihan->count() > 0)
                                        <div class="pelatihan-section">
                                            <div style="margin-bottom: 8px; color: var(--mm-text); font-weight: 900; font-size: 13px;">
                                                <i class="ri-book-open-line me-1"></i> Pelatihan yang Anda Perlukan
                                            </div>
                                            <div>
                                                @foreach($first->pelatihan as $pelatihan)
                                                    <div class="pelatihan-badge">
                                                        <i class="ri-checkbox-circle-fill"></i>
                                                        <span>{{ $pelatihan->nama_pelatihan_lengkap }}</span>
                                                        <span class="pelatihan-kategori">{{ $pelatihan->kategori_pelatihan }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="pelatihan-section">
                                            <div class="no-pelatihan">
                                                <i class="ri-information-line me-1"></i> Belum ada data pelatihan
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="ptk-body">

                            {{-- SUMMARY --}}
                            <div class="summary-box">
                                <div class="summary-top">
                                    <p class="summary-title mb-0">
                                        <i class="ri-award-line"></i> Ringkasan Capaian
                                    </p>

                                    <div class="d-flex align-items-center gap-2">
                                        @if($targetLevel > 0)
                                            <span class="badge bg-{{ $levelColors[$targetLevel] ?? 'secondary' }}-subtle text-{{ $levelColors[$targetLevel] ?? 'secondary' }}"
                                                  style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                                Capaian Lv {{ $targetLevel }}
                                            </span>
                                            <span class="badge bg-secondary-subtle text-secondary"
                                                  style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                                Memenuhi: {{ $cntMeet }}/{{ $totalIndikator }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary"
                                                  style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                                Capaian belum terbaca
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="summary-desc">
                                    @if($targetLevel > 0 && $isAllMeet)
                                        <span class="badge bg-success-subtle text-success"
                                              style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                            <i class="ri-check-line me-1"></i> Good job!
                                        </span>
                                        <span class="ms-2">
                                            Anda telah memenuhi sesuai dengan level capaian Anda (Capaian Level {{ $targetLevel }}).
                                        </span>

                                        <div class="summary-rek">
                                            <div class="summary-sections" style="grid-template-columns: 1fr;">
                                                <div class="summary-sec">
                                                    <div class="summary-sec-head">
                                                        <div class="summary-sec-title">
                                                            <i class="ri-checkbox-circle-line"></i> Indikator yang sudah tercapai
                                                        </div>
                                                        <div class="summary-sec-note">{{ count($summaryAchList) }} item</div>
                                                    </div>

                                                    @if(count($summaryAchList))
                                                        @foreach($summaryAchList as $i => $sa)
                                                            @php
                                                                $fullA = (string)($sa['rekomendasi'] ?? '');
                                                                $shortA = \Illuminate\Support\Str::limit($fullA, 140);
                                                                $needMoreA = strlen($fullA) > 140;
                                                                $aid = $panelId.'_sumach_'.$i;
                                                            @endphp

                                                            <div class="summary-item">
                                                                <span class="badge bg-success-subtle text-success"
                                                                      style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                                                    Tercapai Lv {{ (int)($sa['level'] ?? 0) }}
                                                                </span>
                                                                <div class="tx">
                                                                    {{ $sa['name'] ?? '-' }}
                                                                    @if(!empty($sa['code']))
                                                                        <small>Kode: {{ $sa['code'] }}</small>
                                                                    @endif

                                                                    {{-- ✅ isi untuk yang tercapai (kalau ada) --}}
                                                                    @if($fullA !== '')
                                                                        <small style="margin-top:6px;">
                                                                            {{ $shortA }}
                                                                        </small>

                                                                        @if($needMoreA)
                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-outline-primary mt-2"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#{{ $aid }}"
                                                                                    aria-expanded="false"
                                                                                    style="border-radius:10px; font-weight:900;">
                                                                                Selengkapnya
                                                                            </button>

                                                                            <div class="collapse mt-2" id="{{ $aid }}">
                                                                                <div class="small text-muted fw-semibold" style="line-height:1.35;">
                                                                                    {{ $fullA }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="summary-item">
                                                            <span class="badge bg-secondary-subtle text-secondary"
                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px;">-</span>
                                                            <div class="tx">Tidak ada data indikator tercapai.</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($targetLevel > 0)
                                        <span class="badge bg-danger-subtle text-danger"
                                              style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                            <i class="ri-error-warning-line me-1"></i> Perlu peningkatan
                                        </span>
                                        <span class="ms-2">
                                            Anda belum mencapai sesuai dengan level jabatan Anda (Capaian Level {{ $targetLevel }}).
                                            Maka direkomendasikan:
                                        </span>

                                        <div class="summary-rek">
                                            <div class="summary-sections">

                                                {{-- KIRI: YANG TERCAPAI + ISI --}}
                                                <div class="summary-sec">
                                                    <div class="summary-sec-head">
                                                        <div class="summary-sec-title">
                                                            <i class="ri-checkbox-circle-line"></i> Sudah tercapai
                                                        </div>
                                                        <div class="summary-sec-note">{{ count($summaryAchList) }} item</div>
                                                    </div>

                                                    @if(count($summaryAchList))
                                                        @foreach($summaryAchList as $i => $sa)
                                                            @php
                                                                $fullA = (string)($sa['rekomendasi'] ?? '');
                                                                $shortA = \Illuminate\Support\Str::limit($fullA, 140);
                                                                $needMoreA = strlen($fullA) > 140;
                                                                $aid = $panelId.'_sumach_'.$i;
                                                            @endphp

                                                            <div class="summary-item">
                                                                <span class="badge bg-success-subtle text-success"
                                                                      style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                                                    Tercapai Lv {{ (int)($sa['level'] ?? 0) }}
                                                                </span>
                                                                <div class="tx">
                                                                    {{ $sa['name'] ?? '-' }}
                                                                    @if(!empty($sa['code']))
                                                                        <small>Kode: {{ $sa['code'] }}</small>
                                                                    @endif

                                                                    @if($fullA !== '')
                                                                        <small style="margin-top:6px;">
                                                                            {{ $shortA }}
                                                                        </small>

                                                                        @if($needMoreA)
                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-outline-primary mt-2"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#{{ $aid }}"
                                                                                    aria-expanded="false"
                                                                                    style="border-radius:10px; font-weight:900;">
                                                                                Selengkapnya
                                                                            </button>

                                                                            <div class="collapse mt-2" id="{{ $aid }}">
                                                                                <div class="small text-muted fw-semibold" style="line-height:1.35;">
                                                                                    {{ $fullA }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="summary-item">
                                                            <span class="badge bg-secondary-subtle text-secondary"
                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px;">-</span>
                                                            <div class="tx">Belum ada indikator yang memenuhi capaian.</div>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- KANAN: PERLU DITINGKATKAN + ISI --}}
                                                <div class="summary-sec">
                                                    <div class="summary-sec-head">
                                                        <div class="summary-sec-title">
                                                            <i class="ri-arrow-up-circle-line"></i> Perlu ditingkatkan
                                                        </div>
                                                        <div class="summary-sec-note">{{ count($summaryRekList) ?: 0 }} rekomendasi</div>
                                                    </div>

                                                    @if(count($summaryRekList))
                                                        @foreach($summaryRekList as $i => $sr)
                                                            @php
                                                                $full = (string)($sr['rekomendasi'] ?? '');
                                                                $short = \Illuminate\Support\Str::limit($full, 140);
                                                                $needMore = strlen($full) > 140;
                                                                $sid = $panelId.'_sumrek_'.$i;
                                                            @endphp

                                                            <div class="summary-item">
                                                                <span class="badge bg-danger-subtle text-danger"
                                                                      style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                                                    Rekom Lv {{ (int)($sr['level'] ?? 0) }}
                                                                </span>

                                                                <div class="tx">
                                                                    {{ $sr['name'] ?? '-' }}
                                                                    @if(!empty($sr['code']))
                                                                        <small>Kode: {{ $sr['code'] }}</small>
                                                                    @endif

                                                                    @if($full !== '')
                                                                        <small style="margin-top:6px;">
                                                                            {{ $short }}
                                                                        </small>

                                                                        @if($needMore)
                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-outline-primary mt-2"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#{{ $sid }}"
                                                                                    aria-expanded="false"
                                                                                    style="border-radius:10px; font-weight:900;">
                                                                                Selengkapnya
                                                                            </button>

                                                                            <div class="collapse mt-2" id="{{ $sid }}">
                                                                                <div class="small text-muted fw-semibold" style="line-height:1.35;">
                                                                                    {{ $full }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="summary-item">
                                                            <span class="badge bg-secondary-subtle text-secondary"
                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px;">-</span>
                                                            <div class="tx">Belum ada rekomendasi gap yang cocok dengan capaian level Anda.</div>
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>

                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary"
                                              style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                            <i class="ri-information-line me-1"></i> Info
                                        </span>
                                        <span class="ms-2">
                                            Capaian level belum bisa ditentukan dari jenjang/min-max, jadi ringkasan tidak dapat dihitung.
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- DETAIL (COLLAPSE) --}}
                            <div class="indikator-card">
                                <div class="head">
                                    <p class="ttl mb-0"><i class="ri-list-check-2"></i> Detail Indikator & Rekomendasi</p>

                                    <div class="d-flex align-items-center gap-2">
                                        <div class="count">{{ $rows->count() }} indikator (di halaman ini)</div>

                                        <button type="button"
                                                class="btn btn-toggle-indikator collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $panelId }}"
                                                aria-expanded="false"
                                                aria-controls="{{ $panelId }}"
                                                title="Buka/Tutup">
                                            <i class="ri-arrow-up-s-line"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="collapse" id="{{ $panelId }}">
                                    @foreach($rows->values() as $idx => $row)
                                        @php
                                            $infoRaw = $row->rekomendasi_info ?? [];
                                            if (is_string($infoRaw)) {
                                                $info = json_decode($infoRaw, true) ?: [];
                                            } elseif (is_object($infoRaw)) {
                                                $info = (array) $infoRaw;
                                            } elseif ($infoRaw instanceof \Illuminate\Support\Collection) {
                                                $info = $infoRaw->toArray();
                                            } elseif (is_array($infoRaw)) {
                                                $info = $infoRaw;
                                            } else {
                                                $info = [];
                                            }

                                            $levelJawaban = (int)($row->level_jawaban ?? 0);
                                            $levelMin = (int)($info['level_min'] ?? 0);
                                            $levelMax = (int)($info['level_max'] ?? 0);

                                            $rekomendasiGap = $info['rekomendasi_gap'] ?? [];
                                            if (is_string($rekomendasiGap)) {
                                                $rekomendasiGap = json_decode($rekomendasiGap, true) ?: [];
                                            } elseif (is_object($rekomendasiGap)) {
                                                $rekomendasiGap = (array) $rekomendasiGap;
                                            } elseif (!is_array($rekomendasiGap)) {
                                                $rekomendasiGap = [];
                                            }

                                            $nomor = $globalNo;
                                            $globalNo++;

                                            $wajibLevels = $getWajibLevels($jenjang, $levelMin, $levelMax);
                                            $cid = 'rek_' . preg_replace('/[^a-zA-Z0-9]/', '', (string)($row->nip ?? 'x')) . '_' . $idx;
                                        @endphp

                                        <div class="indikator-item">
                                            <div class="indikator-row">
                                                <div class="indikator-grid">

                                                    <div>
                                                        <div class="cell-title">Nomor</div>
                                                        <div class="no-box">{{ $nomor }}</div>
                                                    </div>

                                                    <div>
                                                        <div class="cell-title">Level yang dicapai</div>

                                                        <div class="lv-box">
                                                            @if($levelJawaban > 0)
                                                                <span class="badge bg-{{ $levelColors[$levelJawaban] ?? 'secondary' }}-subtle text-{{ $levelColors[$levelJawaban] ?? 'secondary' }}"
                                                                      style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                                                    Level {{ $levelJawaban }}
                                                                </span>
                                                                <div class="mt-2" style="color:var(--mm-muted); font-weight:800; font-size:12px;">
                                                                    {{ $levelNames[$levelJawaban] ?? '' }}
                                                                </div>
                                                            @else
                                                                <span class="badge bg-secondary"
                                                                      style="border-radius:999px; padding:8px 12px; font-weight:900;">-</span>
                                                            @endif

                                                            <div class="lv-sub">
                                                                <i class="ri-flag-line"></i>
                                                                <span>Level yang harus</span>
                                                            </div>

                                                            <div class="lv-badges">
                                                                @if(count($wajibLevels))
                                                                    @foreach($wajibLevels as $i)
                                                                        <span class="badge bg-{{ $levelColors[$i] ?? 'secondary' }}-subtle text-{{ $levelColors[$i] ?? 'secondary' }}"
                                                                              style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                                                            Lv {{ $i }}
                                                                        </span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge bg-secondary-subtle text-secondary"
                                                                          style="border-radius:999px; padding:8px 12px; font-weight:900;">-</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <div class="cell-title">Indikator</div>
                                                        <div class="ind-box">
                                                            <div class="ind-name">{{ $row->sub_indikator_name }}</div>
                                                            <div class="ind-code">
                                                                <i class="ri-hashtag"></i>
                                                                <span>Kode: <strong>{{ $row->sub_indikator_code }}</strong></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <div class="cell-title">Rekomendasi Kebutuhan Belajar</div>
                                                        <div class="rek-box">
                                                            @if(count($rekomendasiGap) > 0)
                                                                @foreach($rekomendasiGap as $rkIndex => $rek)
                                                                    @php
                                                                        $rekLevel = (int)($rek['level'] ?? 0);
                                                                        $rekText  = (string)($rek['rekomendasi'] ?? '');
                                                                        $short    = \Illuminate\Support\Str::limit($rekText, 160);
                                                                        $needMore = strlen($rekText) > 160;
                                                                        $collapseId = $cid . '_' . $rkIndex;
                                                                    @endphp

                                                                    <div class="rek-item">
                                                                        <div class="rek-top">
                                                                            <span class="badge bg-danger-subtle text-danger"
                                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px;">
                                                                                Gap Level {{ $rekLevel }}
                                                                            </span>
                                                                            <small class="text-muted fw-semibold">
                                                                                {{ $levelNames[$rekLevel] ?? '' }}
                                                                            </small>
                                                                        </div>

                                                                        <div class="rek-desc">{{ $short }}</div>

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
                                                            @else
                                                                <div class="text-center p-2">
                                                                    <span class="badge bg-success-subtle text-success px-3 py-2"
                                                                          style="border-radius:999px; font-weight:900;">
                                                                        <i class="ri-check-line me-1"></i> Memenuhi standar kompetensi jabatan
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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