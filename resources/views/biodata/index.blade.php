@extends('layouts.main')
@section('mycontent')

@php
    // total data (paginate)
    $totalRows = isset($data['peserta']) && method_exists($data['peserta'],'total')
        ? $data['peserta']->total()
        : (isset($data['peserta']) ? $data['peserta']->count() : 0);
@endphp

<style>
    :root{
        --ink:#1f2937;
        --muted:#6b7280;
        --line:#e5e7eb;
        --blue:#1a5bb8;
        --soft:#f8fafc;
        --success:#16a34a;
        --danger:#dc2626;
        --info:#0dcaf0;
    }

    .page-title-box{ padding: 6px 0 14px 0; }

    /* ===== CARD WRAPPER ===== */
    .kegiatan-card{
        border: 0;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(2,6,23,.08);
        overflow: hidden;
        background: #fff;
    }

    .kegiatan-card .card-header{
        background: none !important;
        border-bottom: 0 !important;
        padding: 0 !important;
    }

    /* ===== BADUY HERO HEADER ===== */
    .baduy-hero{
        position: relative;
        border-radius: 18px 18px 0 0;
        overflow: hidden;
        padding: 18px 18px;
        min-height: 92px;
        background: var(--blue);
        border-bottom: 1px solid rgba(255,255,255,.14);
    }
    .baduy-hero::before{
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
    .baduy-hero::after{
        content:"";
        position:absolute;
        inset:0;
        background: rgba(26,91,184,.50);
        z-index: 1;
        pointer-events:none;
    }
    .baduy-hero .hero-inner{
        position: relative;
        z-index: 2;
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .hero-left{
        display:flex;
        align-items:center;
        gap: 12px;
        min-width: 260px;
    }
    .hero-icon{
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.18);
        color: #fff;
        flex: 0 0 auto;
        backdrop-filter: blur(6px);
    }
    .hero-icon i{ font-size: 20px; }

    .hero-title{
        margin:0;
        font-weight: 900;
        font-size: 20px;
        letter-spacing: .2px;
        color:#fff;
        line-height: 1.15;
        text-shadow: 0 2px 12px rgba(0,0,0,.35);
    }
    .hero-sub{
        margin-top: 4px;
        font-weight: 500;
        font-size: 13px;
        color: rgba(255,255,255,.92);
        text-shadow: 0 2px 12px rgba(0,0,0,.35);
    }
    /* ✅ JANGAN BOLD walau pakai <b> */
    .hero-sub b{ font-weight: 600 !important; }

    .btn-hero{
        background: rgba(255,255,255,.16) !important;
        border: 1px solid rgba(255,255,255,.22) !important;
        color: #fff !important;
        font-weight: 900;
        border-radius: 14px;
        padding: 10px 14px;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition: .2s ease;
        backdrop-filter: blur(6px);
        box-shadow: 0 10px 18px rgba(2,6,23,.12);
        text-decoration:none;
    }
    .btn-hero:hover{
        background: rgba(255,255,255,.22) !important;
        transform: translateY(-1px);
    }

    /* ===== FILTER BAR (RAPIH) ===== */
    .filter-bar{
        background: var(--soft);
        border: 1px solid rgba(229,231,235,.9);
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 14px;
    }
    .filter-bar .form-control,
    .filter-bar .form-select{
        border-radius: 12px;
        height: 44px;
        font-weight: 500; /* ✅ jangan bold */
        color: var(--ink);
        border: 1px solid rgba(229,231,235,.95);
    }
    .filter-bar .btn{
        border-radius: 12px;
        height: 44px;
        font-weight: 900;
        display:inline-flex;
        align-items:center;
        gap:8px;
    }

    /* ===== TABLE: KASI MARGIN SAMPING + CARD RAPIH ===== */
    .table-card{
        padding: 0 16px;              /* ✅ margin samping kiri-kanan */
        background: transparent;
        border: 0;
        margin-top: 10px;
    }

    /* border + rounded ada di wrapper scroll */
    .table-card > .table-scroll{
        border: 1px solid rgba(229,231,235,.85);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
    }

    .table-scroll{
        position: relative;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }

    #biodataTable{ min-width: 1050px; }

    #biodataTable thead th{
        background: #f8fafc;
        border-bottom: 1px solid rgba(229,231,235,.9);
        color: var(--muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 14px 14px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
        font-weight: 700; /* header boleh tegas */
    }

    #biodataTable tbody td{
        padding: 16px 14px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(229,231,235,.75);
        color: var(--ink);
        font-weight: 500;  /* ✅ jangan bold */
        font-size: 13.5px;
        background: #fff;
    }

    #biodataTable tbody tr:hover td{
        background: rgba(26,91,184,.04);
    }

    .cell-no{ width: 64px; }
    .cell-aksi{ width: 130px; }

    .cell-muted{
        color: var(--muted) !important;
        font-weight: 500 !important; /* ✅ jangan bold */
    }

    .cell-kegiatan{
        max-width: 520px;
        white-space: normal;
        word-break: break-word;
        line-height: 1.3;
    }

    .badge-soft{
        padding: 7px 10px;
        border-radius: 999px;
        font-weight: 500; /* ✅ jangan bold */
        letter-spacing:.2px;
        font-size: 12px;
        display:inline-flex;
        align-items:center;
        gap:7px;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .badge-ada{
        background: rgba(22,163,74,.12);
        color: var(--success);
        border-color: rgba(22,163,74,.18);
    }
    .badge-tidak{
        background: rgba(107,114,128,.10);
        color: #6b7280;
        border-color: rgba(107,114,128,.18);
    }

    .btn-mini{
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border: 1px solid rgba(229,231,235,.9);
        background: #fff;
        transition: .2s ease;
        text-decoration: none;
    }
    .btn-mini:hover{
        background: rgba(2,6,23,.04);
        transform: translateY(-1px);
    }
    .btn-mini.info{
        border-color: rgba(13,202,240,.35);
        background: rgba(13,202,240,.08);
        color: #087990;
    }
    .btn-mini.danger{
        border-color: rgba(220,53,69,.28);
        background: rgba(220,53,69,.08);
        color: #b02a37;
    }

    /* scrollbar rapi */
    .table-scroll::-webkit-scrollbar{ height: 10px; }
    .table-scroll::-webkit-scrollbar-track{ background: #f1f5f9; border-radius: 999px; }
    .table-scroll::-webkit-scrollbar-thumb{ background: rgba(2,6,23,.18); border-radius: 999px; }
    .table-scroll::-webkit-scrollbar-thumb:hover{ background: rgba(2,6,23,.28); }

    /* ===== TOAST ===== */
    .toast{
        margin-bottom: 10px;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.12);
        border-radius: 14px;
        overflow: hidden;
    }

    /* ===== MODAL polish ===== */
    .modal-content{ border-radius: 16px; overflow: hidden; }
    .modal-header{
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
    }
    /* === OVERRIDE WARNA TOMBOL CARI (BTN PRIMARY DI FILTER BAR) === */
    .filter-bar .btn.btn-primary{
        background: #1a5bb8 !important;
        border-color: #1a5bb8 !important;
        color: #fff !important;
    }

    /* hover */
    .filter-bar .btn.btn-primary:hover{
        background: #174fa1 !important;   /* sedikit lebih gelap */
        border-color: #174fa1 !important;
        transform: translateY(-1px);
    }

    /* focus */
    .filter-bar .btn.btn-primary:focus{
        box-shadow: 0 0 0 .25rem rgba(26, 91, 184, .25) !important;
    }
</style>

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

    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ri-checkbox-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ri-error-warning-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    @if(session('info'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="ri-information-line me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card kegiatan-card">

                {{-- HEADER BADUY --}}
                <div class="card-header">
                    <div class="baduy-hero">
                        <div class="hero-inner">
                            <div class="hero-left">
                                <div class="hero-icon">
                                    <i class="ri-profile-line"></i>
                                </div>
                                <div>
                                    <h5 class="hero-title">Daftar Biodata</h5>
                                    <div class="hero-sub">
                                        Menampilkan <b>{{ $totalRows }}</b> data
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-hero" data-bs-toggle="modal" data-bs-target="#exportModal">
                                    <i class="ri-file-pdf-line"></i> Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- FILTER BAR -->
                    <form method="GET" action="{{ route('biodata.index') }}" class="filter-bar" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-5 col-md-6">
                                <label class="small fw-bold text-muted mb-1">Pencarian</label>
                                <input type="text" class="form-control" name="search" id="searchInput"
                                       placeholder="Cari NIP/Nama/NIK..." value="{{ request('search') }}">
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label class="small fw-bold text-muted mb-1">Kegiatan</label>
                                <select class="form-select" name="kegiatan_id" id="kegiatanSelect">
                                    <option value="">Semua Kegiatan</option>
                                    @foreach ($data['kegiatans'] as $kegiatan)
                                        <option value="{{ $kegiatan->kegiatan_id }}"
                                            {{ request('kegiatan_id') == $kegiatan->kegiatan_id ? 'selected' : '' }}>
                                            {{ $kegiatan->kegiatan_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-search-line"></i> Cari
                                </button>
                                <a href="{{ route('biodata.index') }}" class="btn btn-secondary w-100">
                                    <i class="ri-refresh-line"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- TABLE -->
                    <div class="table-card">
                        <div class="table-scroll">
                            <table class="table align-middle mb-0" id="biodataTable">
                                <thead>
                                    <tr>
                                        <th class="cell-no">No</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Unit Kerja</th>
                                        <th>Kegiatan</th>
                                        <th style="width:110px;">TTD</th>
                                        <th class="cell-aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['peserta'] as $row)
                                        <tr>
                                            <td class="cell-no">
                                                {{ ($data['peserta']->currentPage() - 1) * $data['peserta']->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="text-nowrap">{{ $row->nip }}</td>
                                            <td class="text-nowrap">{{ $row->nama }}</td>
                                            <td class="cell-muted text-nowrap">{{ $row->jenjang_jabatan ?? '-' }}</td>
                                            <td class="text-nowrap">
                                                @if(!empty($row->nama_sekolah))
                                                    {{ $row->nama_sekolah }}
                                                @elseif(!empty($row->instansi))
                                                    {{ $row->instansi }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="cell-kegiatan">{{ $row->kegiatan_name ?? '-' }}</td>
                                            <td>
                                                @if(!empty($row->ttd_base64))
                                                    <span class="badge-soft badge-ada">
                                                        <i class="ri-check-line"></i> Ada
                                                    </span>
                                                @else
                                                    <span class="badge-soft badge-tidak">
                                                        <i class="ri-close-line"></i> Tidak
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button"
                                                            class="btn-mini info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewSignatureModal"
                                                            data-nama="{{ $row->nama }}"
                                                            data-ttd-base64="{{ $row->ttd_base64 ?? '' }}"
                                                            title="Lihat TTD">
                                                        <i class="ri-eye-line"></i>
                                                    </button>

                                                    <a href="{{ route('biodata.exportPdf', $row->peserta_id) }}"
                                                       class="btn-mini danger"
                                                       onclick="return confirmExportSingle(event)"
                                                       title="Export PDF">
                                                        <i class="ri-file-pdf-line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        {!! $data['peserta']->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Melihat Tanda Tangan -->
    <div class="modal fade" id="viewSignatureModal" tabindex="-1" aria-labelledby="viewSignatureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSignatureModalLabel">
                        <i class="ri-signature-line me-2"></i> Tanda Tangan Digital
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h6 id="signatureOwnerName" class="mb-3">Nama Peserta</h6>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div id="signatureImageContainer" style="min-height: 200px; display:flex; align-items:center; justify-content:center; background:#f8fafc; border-radius:12px; border:1px dashed #dee2e6;">
                                <div id="signaturePlaceholder" class="text-muted">
                                    <i class="ri-signature-line fs-1"></i>
                                    <p class="mt-2">Tanda tangan tidak tersedia</p>
                                </div>
                                <img id="signatureImage" src="" alt="Tanda Tangan"
                                     style="max-width: 100%; max-height: 150px; display:none; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:10px;">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="ri-information-line me-2"></i>
                        Tanda tangan digital ini disimpan dalam format base64 dan dapat digunakan untuk dokumen resmi.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="downloadSignatureBtn">
                        <i class="ri-download-line me-1"></i> Download Gambar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Export -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="ri-file-pdf-line text-danger me-2"></i> Konfirmasi Export PDF
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        Export data dengan filter berikut:
                    </div>

                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4 fw-bold">Pencarian:</div>
                                <div class="col-8">
                                    <span id="exportSearchInfo" class="text-primary">(Semua)</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 fw-bold">Kegiatan:</div>
                                <div class="col-8">
                                    <span id="exportKegiatanInfo" class="text-primary">(Semua Kegiatan)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <i class="ri-alert-line me-2"></i>
                        Data akan diexport dalam format PDF dan otomatis didownload.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmExportBtn" onclick="proceedExport()">
                        <i class="ri-file-pdf-line me-1"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-dark fw-bold">Sedang memproses...</p>
                    <p class="text-muted small">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Single Export -->
    <div class="modal fade" id="singleExportModal" tabindex="-1" aria-labelledby="singleExportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singleExportModalLabel">
                        <i class="ri-file-pdf-line text-danger me-2"></i> Export PDF Perorangan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ri-file-pdf-line text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin export PDF biodata ini?</p>
                    <p class="text-center text-muted small">File PDF akan otomatis didownload.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Batal
                    </button>
                    <a href="#" class="btn btn-danger" id="singleExportLink" onclick="startSingleExport()">
                        <i class="ri-file-pdf-line me-1"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('sipproja-js')
<script>
    // Variabel untuk menyimpan instance modal
    let loadingModalInstance = null;
    let exportModalInstance = null;
    let singleExportModalInstance = null;

    document.getElementById('exportModal')?.addEventListener('show.bs.modal', function() {
        const search = document.getElementById('searchInput').value;
        const kegiatanId = document.getElementById('kegiatanSelect').value;

        document.getElementById('exportSearchInfo').textContent = search || '(Semua)';

        if (kegiatanId) {
            const kegiatanSelect = document.getElementById('kegiatanSelect');
            const kegiatanText = kegiatanSelect.options[kegiatanSelect.selectedIndex].text;
            document.getElementById('exportKegiatanInfo').textContent = kegiatanText;
        } else {
            document.getElementById('exportKegiatanInfo').textContent = '(Semua Kegiatan)';
        }
    });

    function proceedExport() {
        const search = document.getElementById('searchInput').value;
        const kegiatanId = document.getElementById('kegiatanSelect').value;

        let url = "{{ route('biodata.exportAllPdf') }}";
        const params = new URLSearchParams();

        if (search) params.append('search', search);
        if (kegiatanId) params.append('kegiatan_id', kegiatanId);

        if (params.toString()) url += '?' + params.toString();

        if (exportModalInstance) exportModalInstance.hide();

        showLoadingModal();

        setTimeout(function() {
            window.location.href = url;
            setTimeout(hideLoadingModal, 3000);
        }, 500);
    }

    function confirmExportSingle(event) {
        event.preventDefault();
        const exportUrl = event.currentTarget.href;

        document.getElementById('singleExportLink').href = exportUrl;

        singleExportModalInstance = new bootstrap.Modal(document.getElementById('singleExportModal'));
        singleExportModalInstance.show();

        return false;
    }

    function startSingleExport() {
        const url = document.getElementById('singleExportLink').href;

        if (singleExportModalInstance) singleExportModalInstance.hide();
        showLoadingModal();

        setTimeout(function() {
            window.location.href = url;
            setTimeout(hideLoadingModal, 3000);
        }, 500);

        return false;
    }

    function showLoadingModal() {
        const loadingModal = document.getElementById('loadingModal');
        loadingModalInstance = new bootstrap.Modal(loadingModal, { backdrop:'static', keyboard:false });
        loadingModalInstance.show();
        window.loadingStartTime = Date.now();
    }

    function hideLoadingModal() {
        if (loadingModalInstance) {
            loadingModalInstance.hide();
            loadingModalInstance = null;
        }
    }

    function checkIfDownloadComplete() {
        if (window.loadingStartTime && Date.now() - window.loadingStartTime > 3000) {
            hideLoadingModal();
        }
    }

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
            hideLoadingModal();
        }
    });

    setInterval(checkIfDownloadComplete, 1000);

    document.addEventListener('DOMContentLoaded', function() {
        // Bootstrap Toast init
        const toastElList = document.querySelectorAll('.toast');
        const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
        toastList.forEach(toast => toast.show());

        // Auto-hide alerts 5s
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.toast .alert)');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        exportModalInstance = new bootstrap.Modal(document.getElementById('exportModal'));
        singleExportModalInstance = new bootstrap.Modal(document.getElementById('singleExportModal'));
    });

    // Modal lihat tanda tangan
    document.addEventListener('DOMContentLoaded', function() {
        const viewSignatureModal = document.getElementById('viewSignatureModal');
        if (viewSignatureModal) {
            viewSignatureModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const nama = button.getAttribute('data-nama');
                const ttdBase64 = button.getAttribute('data-ttd-base64');

                document.getElementById('signatureOwnerName').textContent = nama;

                const signatureImage = document.getElementById('signatureImage');
                const signaturePlaceholder = document.getElementById('signaturePlaceholder');

                if (ttdBase64 && ttdBase64.trim() !== '') {
                    signatureImage.src = ttdBase64;
                    signatureImage.style.display = 'block';
                    signaturePlaceholder.style.display = 'none';
                    document.getElementById('downloadSignatureBtn').disabled = false;
                } else {
                    signatureImage.style.display = 'none';
                    signaturePlaceholder.style.display = 'block';
                    document.getElementById('downloadSignatureBtn').disabled = true;
                }
            });

            viewSignatureModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('signatureImage').src = '';
                document.getElementById('signatureImage').style.display = 'none';
                document.getElementById('signaturePlaceholder').style.display = 'block';
            });
        }

        // Download signature button
        document.getElementById('downloadSignatureBtn')?.addEventListener('click', function() {
            const signatureImage = document.getElementById('signatureImage');
            const nama = document.getElementById('signatureOwnerName').textContent;

            if (signatureImage.src) {
                const link = document.createElement('a');
                link.href = signatureImage.src;
                link.download = 'ttd-' + nama.replace(/\s+/g, '-').toLowerCase() + '.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
    });
</script>
@endsection
