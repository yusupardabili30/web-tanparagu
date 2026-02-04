@extends('layouts.main-user')
@section('mycontent')

<div class="container-fluid">
    <!-- start page title -->
    <div class="row mb-1 pt-0" style="margin-top:-50px;">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('ptk.show', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $nip]) }}"
                               class="text-primary fw-bold">
                                <i class="ri-arrow-left-line me-1"></i> Kembali
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('ptk.riwayat', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $nip]) }}"
                               class="text-primary fw-bold">
                                Riwayat Kegiatan
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Detail Hasil Instrumen</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <style>
        :root {
            --mm-blue: #1a4d8e;
            --mm-soft: #f6f9ff;
            --mm-text: #1f2937;
            --mm-muted: #6b7280;
            --mm-line: #e5e7eb;
            --mm-card: #ffffff;
            --mm-shadow: 0 10px 24px rgba(17, 24, 39, .10);
            --mm-shadow2: 0 6px 16px rgba(26, 91, 184, .12);
            --radius: 16px;
        }

        .hi-wrap {
            background: #f3f7ff;
            border-radius: 18px;
            padding: 18px;
        }

        /* =========================
           HEADER PAGE
           ========================= */
        .hi-head {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            padding: 22px 24px;
            margin-bottom: 14px;
            background: var(--mm-blue);
            border: 1px solid rgba(255, 255, 255, .20);
            box-shadow: 0 10px 24px rgba(17, 24, 39, .12);
        }

        .hi-head::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("{{ asset('build/images/baduy.jpg') }}");
            background-repeat: repeat;
            background-size: 140px auto;
            background-position: center;
            opacity: .55;
            filter: grayscale(100%) contrast(1.15);
            z-index: 0;
        }

        .hi-head::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(26, 91, 184, .45);
            z-index: 1;
        }

        .hi-head>* {
            position: relative;
            z-index: 2;
        }

        .hi-head,
        .hi-head * {
            color: #fff !important;
        }

        .hi-head h5 {
            margin: 0;
            font-size: 18px !important;
            font-weight: 900;
            letter-spacing: .2px;
            text-shadow: 0 2px 12px rgba(0, 0, 0, .35) !important;
        }

        .hi-head .meta {
            font-size: 12.5px;
            opacity: .95;
            margin-top: 6px;
            color: rgba(255, 255, 255, .92) !important;
            text-shadow: 0 2px 12px rgba(0, 0, 0, .35) !important;
        }

        .hi-title-icon {
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

        .hi-title-icon i {
            font-size: 18px;
        }

        /* =========================
           BUTTON EXPORT
           ========================= */
        .btn-export-pill {
            border-radius: 18px !important;
            padding: 12px 18px !important;
            font-weight: 900 !important;
            box-shadow: 0 10px 18px rgba(0, 0, 0, .10);
        }

        /* =========================
           LIST PER PTK
           ========================= */
        .hi-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .ptk-card {
            border-radius: 18px;
            background: #fff;
            border: 1px solid rgba(229, 231, 235, .95);
            box-shadow: var(--mm-shadow);
            overflow: hidden;
        }

        /* =========================
           PROFIL CARD
           ========================= */
        .ptk-head {
            padding: 14px;
            background: #fff;
            border-bottom: 1px solid rgba(229, 231, 235, .9);
        }

        .ptk-profile {
            border: 1px solid rgba(229, 231, 235, .95);
            background: #f6f9ff;
            border-radius: 16px;
            padding: 14px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .ptk-avatar {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            background: rgba(26, 91, 184, .12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            color: var(--mm-blue);
        }

        .ptk-avatar i {
            font-size: 20px;
        }

        .ptk-profile-body {
            flex: 1;
            min-width: 0;
        }

        .ptk-name {
            font-size: 18px;
            font-weight: 900;
            color: var(--mm-text);
            margin: 0 0 8px 0;
            line-height: 1.2;
            word-break: break-word;
        }

        .ptk-lines {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 14px;
        }

        .ptk-line {
            display: flex;
            gap: 10px;
            align-items: baseline;
            flex-wrap: wrap;
        }

        .ptk-line .k {
            width: 90px;
            color: var(--mm-muted);
            font-weight: 800;
        }

        .ptk-line .v {
            color: var(--mm-text);
            font-weight: 800;
            word-break: break-word;
            flex: 1;
            min-width: 200px;
        }

        /* BODY indikator */
        .ptk-body {
            padding: 14px;
        }

        .indikator-card {
            border: 1px solid rgba(229, 231, 235, .95);
            border-radius: 16px;
            background: #fff;
            overflow: hidden;
        }

        .indikator-card .head {
            padding: 12px 14px;
            background: var(--mm-soft);
            border-bottom: 1px solid rgba(229, 231, 235, .9);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .indikator-card .head .ttl {
            font-weight: 900;
            color: var(--mm-text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .indikator-card .head .count {
            color: var(--mm-muted);
            font-weight: 900;
            font-size: 12px;
            white-space: nowrap;
        }

        /* =========================
           ✅ TIAP INDIKATOR JADI CARD
           ========================= */
        .indikator-item {
            padding: 12px 14px;
            background: #fff;
            border-top: 1px dashed rgba(229, 231, 235, .9);
        }

        .indikator-item:first-of-type {
            border-top: none;
        }

        .indikator-row {
            border: 1px solid rgba(229, 231, 235, .95);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 26px rgba(17, 24, 39, .18);
            padding: 12px 14px;
        }

        .indikator-item+.indikator-item .indikator-row {
            margin-top: 12px;
        }

        .indikator-grid {
            display: grid;
            grid-template-columns: 80px 250px 1.1fr 1.4fr;
            gap: 12px;
            align-items: start;
        }

        @media (max-width: 1200px) {
            .indikator-grid { grid-template-columns: 1fr; }
            .ptk-line .k { width: 110px; }
            .ptk-line .v { min-width: 0; }
        }

        .cell-title {
            font-weight: 900;
            color: var(--mm-muted);
            font-size: 12px;
            margin-bottom: 6px;
        }

        .no-box {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(26, 91, 184, .12);
            color: var(--mm-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 14px;
        }

        .lv-box {
            border: 1px solid rgba(229, 231, 235, .95);
            background: var(--mm-soft);
            border-radius: 14px;
            padding: 10px;
        }

        .lv-sub {
            color: var(--mm-muted);
            font-weight: 900;
            font-size: 12px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .lv-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .ind-box {
            border: 1px solid rgba(229, 231, 235, .95);
            background: #fff;
            border-radius: 14px;
            padding: 10px;
        }

        .ind-name {
            font-weight: 900;
            color: var(--mm-text);
            font-size: 13.5px;
            line-height: 1.25;
            margin-bottom: 6px;
        }

        .ind-code {
            color: var(--mm-muted);
            font-weight: 800;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rek-box {
            border: 1px solid rgba(229, 231, 235, .95);
            background: #fff;
            border-radius: 14px;
            padding: 10px;
        }

        .rek-item {
            border-radius: 12px;
            border: 1px solid rgba(229, 231, 235, .95);
            background: var(--mm-soft);
            padding: 10px;
        }

        .rek-item+.rek-item { margin-top: 10px; }

        .rek-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 6px;
        }

        .rek-desc {
            color: var(--mm-text);
            font-weight: 500;
            font-size: 12.5px;
            line-height: 1.35;
        }

        /* Info Kegiatan */
        .kegiatan-info {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(229, 231, 235, .95);
            padding: 14px;
            margin-bottom: 20px;
        }

        .kegiatan-title {
            font-size: 18px;
            font-weight: 900;
            color: var(--mm-blue);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kegiatan-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .kegiatan-detail-item { display: flex; flex-direction: column; }

        .kegiatan-detail-label {
            font-size: 12px;
            color: var(--mm-muted);
            font-weight: 800;
            margin-bottom: 4px;
        }

        .kegiatan-detail-value {
            font-size: 14px;
            color: var(--mm-text);
            font-weight: 800;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(229, 231, 235, .95);
        }

        .empty-state i {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 16px;
        }

        .empty-state h5 {
            color: var(--mm-muted);
            font-weight: 800;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--mm-muted);
            font-size: 14px;
        }

        /* Badge level colors */
        .badge-info-subtle    { background-color: rgba(13, 202, 240, .1) !important; color: #0dcaf0 !important; border-color: rgba(13, 202, 240, .2) !important; }
        .badge-primary-subtle { background-color: rgba(13, 110, 253, .1) !important; color: #0d6efd !important; border-color: rgba(13, 110, 253, .2) !important; }
        .badge-warning-subtle { background-color: rgba(255, 193, 7, .1) !important; color: #ffc107 !important; border-color: rgba(255, 193, 7, .2) !important; }
        .badge-success-subtle { background-color: rgba(25, 135, 84, .1) !important; color: #198754 !important; border-color: rgba(25, 135, 84, .2) !important; }
        .badge-danger-subtle  { background-color: rgba(220, 53, 69, .1) !important; color: #dc3545 !important; border-color: rgba(220, 53, 69, .2) !important; }

        /* =========================
           ✅ TOGGLE CARD INDIKATOR & REKOMENDASI (COLLAPSE)
           ========================= */
        .btn-toggle-indikator{
            border-radius: 12px !important;
            padding: 8px 10px !important;
            font-weight: 900 !important;
            background: var(--mm-soft) !important;
            border: 1px solid rgba(229,231,235,.95) !important;
            color: var(--mm-text) !important;
            line-height: 1;
        }
        .btn-toggle-indikator:hover,
        .btn-toggle-indikator:active,
        .btn-toggle-indikator:focus{
            background: var(--mm-soft) !important;
            border-color: rgba(229,231,235,.95) !important;
            color: var(--mm-text) !important;
            box-shadow: none !important;
        }
        .btn-toggle-indikator i{
            font-size: 18px;
            transition: transform .2s ease;
        }
        .btn-toggle-indikator.collapsed i{
            transform: rotate(180deg);
        }

        /* =========================
           ✅ SUMMARY CAPAIAN: SPLIT
           ========================= */
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

        /* =========================
           ✅ SUMMARY ITEM versi "samain kaya yang atas": ada header nama sub indikator
           ========================= */
        .summary-item{
            display:block;
            padding: 0;
            overflow: hidden;
            border-radius: 12px;
            background: var(--mm-soft);
            border: 1px solid rgba(229,231,235,.9);
        }
        .summary-item + .summary-item{ margin-top: 8px; }

        .summary-item-head{
            display:flex;
            align-items:flex-start;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 10px 8px 10px;
            background: #fff;
            border-bottom: 1px dashed rgba(229,231,235,.9);
        }
        .summary-item-title{
            font-weight: 900;
            color: var(--mm-text);
            font-size: 13px;
            line-height: 1.25;
            margin: 0;
        }
        .summary-item-code{
            margin-top: 3px;
            color: var(--mm-muted);
            font-weight: 800;
            font-size: 12px;
            display:flex;
            align-items:center;
            gap: 6px;
        }
        .summary-item-body{
            display:flex;
            align-items:flex-start;
            gap: 10px;
            padding: 10px;
            background: var(--mm-soft);
        }
        .summary-item-body .tx{
            flex: 1;
            font-size: 12.5px;
            line-height: 1.45;
            color: var(--mm-text);
            font-weight: 700;
        }

        /* ✅ HEADER "DETAIL INDIKATOR & REKOMENDASI" TANPA GRADASI */
        .indikator-card .head{
            background: #1a4d8e !important;
            border-bottom: 1px solid rgba(255,255,255,.22) !important;
        }
        .indikator-card .head .ttl,
        .indikator-card .head .ttl i{
            color: #fff !important;
        }
        .indikator-card .head .count{
            color: #fff !important;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.28);
            padding: 6px 10px;
            border-radius: 999px;
        }
        .btn-toggle-indikator{
            background: rgba(255,255,255,.18) !important;
            border: 1px solid rgba(255,255,255,.28) !important;
            color: #fff !important;
        }
        .btn-toggle-indikator:hover,
        .btn-toggle-indikator:active,
        .btn-toggle-indikator:focus{
            background: rgba(255,255,255,.28) !important;
            border-color: rgba(255,255,255,.36) !important;
            color: #fff !important;
            box-shadow: none !important;
        }
    </style>

    <div class="hi-wrap">
        <div class="hi-head d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <span class="hi-title-icon"><i class="ri-bar-chart-2-line"></i></span>
                    Detail Hasil Instrumen
                </h5>
                <div class="meta">
                    Hasil penilaian kompetensi untuk kegiatan yang dipilih
                </div>
            </div>

            @if($data && $data->count() > 0)
            <div>
                <a class="btn btn-success btn-export-pill"
                   href="{{ route('ptk.export-hasil', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $nip]) }}">
                    <i class="ri-file-pdf-line align-bottom me-1"></i> Export PDF
                </a>
            </div>
            @endif
        </div>

        <!-- Info Kegiatan -->
        <div class="kegiatan-info">
            <div class="kegiatan-title">
                <i class="ri-calendar-event-line"></i>
                {{ $kegiatan->kegiatan_name }}
            </div>
            <div class="kegiatan-details">
                <div class="kegiatan-detail-item">
                    <span class="kegiatan-detail-label">Entity</span>
                    <span class="kegiatan-detail-value">{{ $kegiatan->entity }}</span>
                </div>
                <div class="kegiatan-detail-item">
                    <span class="kegiatan-detail-label">Periode</span>
                    <span class="kegiatan-detail-value">{{ $start_date }} - {{ $end_date }}</span>
                </div>
                <div class="kegiatan-detail-item">
                    <span class="kegiatan-detail-label">Status Kegiatan</span>
                    <span class="kegiatan-detail-value">
                        @if($kegiatan->status == 'Active')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Selesai</span>
                        @endif
                    </span>
                </div>
                <div class="kegiatan-detail-item">
                    <span class="kegiatan-detail-label">Terakhir Aktif</span>
                    <span class="kegiatan-detail-value">{{ $tanggalTerakhir }}</span>
                </div>
            </div>
        </div>

        @php
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

            // ✅ jenjang PTK dari relasi pangkatJabatan
            $jenjangPtk = $ptk->pangkatJabatan->jenjang_jabatan ?? '';
        @endphp

        @if(!$data || $data->count() == 0)
            <div class="empty-state">
                <i class="ri-file-search-line"></i>
                <h5>Belum Ada Data Hasil</h5>
                <p>Anda belum menyelesaikan instrumen untuk kegiatan ini.</p>
            </div>
        @else
            <!-- ✅ LIST HASIL INSTRUMEN -->
            <div class="hi-list">
                <div class="ptk-card">
                    <!-- ✅ IDENTITAS PTK -->
                    <div class="ptk-head">
                        <div class="ptk-profile">
                            <div class="ptk-avatar">
                                <i class="ri-user-3-line"></i>
                            </div>

                            <div class="ptk-profile-body">
                                <div class="ptk-name">{{ $ptk->nama }}</div>

                                <div class="ptk-lines">
                                    <div class="ptk-line">
                                        <div class="k">NIP</div>
                                        <div class="v">{{ $ptk->nip }}</div>
                                    </div>

                                    <div class="ptk-line">
                                        <div class="k">Jenjang</div>
                                        <div class="v">
                                            @if($ptk->pangkatJabatan)
                                                {{ $ptk->pangkatJabatan->jenjang_jabatan }}
                                                @if($ptk->pangkatJabatan->pangkat)
                                                    - {{ $ptk->pangkatJabatan->pangkat }}
                                                @endif
                                                @if($ptk->pangkatJabatan->golongan_ruang)
                                                    ({{ $ptk->pangkatJabatan->golongan_ruang }})
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>

                                    <div class="ptk-line">
                                        <div class="k">Instansi</div>
                                        <div class="v">
                                            @if($ptk->sekolah)
                                                {{ $ptk->sekolah->nama_sekolah }}
                                                @if($ptk->sekolah->npsn)
                                                    ({{ $ptk->sekolah->npsn }})
                                                @endif
                                            @elseif($ptk->instansi)
                                                {{ $ptk->instansi }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SEMUA INDIKATOR -->
                    <div class="ptk-body">

                        @php
                            // ✅ ID collapse unik (karena ini 1 PTK aja)
                            $panelId = 'indikatorPanel_' . preg_replace('/[^a-zA-Z0-9]/', '', (string)($ptk->nip ?? 'x'));

                            // ✅ target level dari jenjang
                            $wajib = $getWajibLevels($jenjangPtk, 0, 0);
                            $targetLevel = (int)(count($wajib) ? $wajib[0] : 0);

                            // ✅ hitung ringkasan (tanpa backend)
                            $totalIndikator = $data->unique('sub_indikator_id')->count();

                            $meetSet = [];
                            $summaryAch = []; // indikator tercapai (unique)
                            $summaryRek = []; // rekomendasi gap (unique, match targetLevel)

                            foreach ($data as $r) {
                                // decode rekomendasi_info biar aman (string/object/array)
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

                                $lvlJawab = (int)($r->level_jawaban ?? 0);
                                $sid      = (string)($r->sub_indikator_id ?? '');

                                // indikator tercapai
                                if ($targetLevel > 0 && $lvlJawab >= $targetLevel && $sid !== '') {
                                    $meetSet[$sid] = true;

                                    $nm = trim((string)($r->sub_indikator_name ?? ''));
                                    $cd = trim((string)($r->sub_indikator_code ?? ''));
                                    if ($nm !== '') {
                                        $key = md5($nm.'|'.$cd);
                                        $summaryAch[$key] = [
                                            'level' => $lvlJawab,
                                            'name'  => $nm,
                                            'code'  => $cd,
                                        ];
                                    }
                                    continue;
                                }

                                // rekomendasi gap yang match targetLevel
                                $rg = $infoSum['rekomendasi_gap'] ?? [];
                                if (is_string($rg)) {
                                    $rg = json_decode($rg, true) ?: [];
                                } elseif (is_object($rg)) {
                                    $rg = (array)$rg;
                                } elseif (!is_array($rg)) {
                                    $rg = [];
                                }

                                foreach ($rg as $g) {
                                    $gl = (int)($g['level'] ?? 0);
                                    $gt = trim((string)($g['rekomendasi'] ?? ''));

                                    // ✅ simpan nama + kode sub indikator buat header item (samain kaya yang atas)
                                    $nm = trim((string)($r->sub_indikator_name ?? ''));
                                    $cd = trim((string)($r->sub_indikator_code ?? ''));

                                    if ($gt !== '' && $targetLevel > 0 && $gl === $targetLevel) {
                                        // ✅ unik per rekom + indikator (biar ga ketiban)
                                        $key = md5($gt.'|'.$nm.'|'.$cd);

                                        $summaryRek[$key] = [
                                            'level' => $gl,
                                            'text'  => $gt,
                                            'name'  => $nm,
                                            'code'  => $cd,
                                        ];
                                    }
                                }
                            }

                            $cntMeet = count($meetSet);
                            $cntNot  = max(0, $totalIndikator - $cntMeet);
                            $isAllMeet = ($totalIndikator > 0 && $cntNot === 0);

                            $summaryAchList = array_values($summaryAch);
                            $summaryRekList = array_values($summaryRek);
                        @endphp

                        {{-- ✅ SUMMARY (DISAMAININ KAYA YG ATAS: item ada header nama sub indikator) --}}
                        <div class="summary-box">
                            <div class="summary-top">
                                <p class="summary-title mb-0">
                                    <i class="ri-award-line"></i> Ringkasan Capaian
                                </p>

                                <div class="d-flex align-items-center gap-2">
                                    @if($targetLevel > 0)
                                        <span class="badge bg-{{ $levelColors[$targetLevel] ?? 'secondary' }}-subtle text-{{ $levelColors[$targetLevel] ?? 'secondary' }}"
                                              style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                            Target Lv {{ $targetLevel }}
                                        </span>
                                        <span class="badge bg-secondary-subtle text-secondary"
                                              style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                            Memenuhi: {{ $cntMeet }}/{{ $totalIndikator }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary"
                                              style="border-radius:999px; padding:8px 12px; font-weight:900;">
                                            Target belum terbaca
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
                                        Anda telah memenuhi sesuai dengan level capaian Anda (Target Level {{ $targetLevel }}).
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
                                                    @foreach($summaryAchList as $sa)
                                                        <div class="summary-item">
                                                            <div class="summary-item-head">
                                                                <div>
                                                                    <div class="summary-item-title">{{ $sa['name'] }}</div>
                                                                    @if(!empty($sa['code']))
                                                                        <div class="summary-item-code">
                                                                            <i class="ri-hashtag"></i> <span>Kode: {{ $sa['code'] }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <span class="badge bg-success-subtle text-success"
                                                                      style="border-radius:999px; font-weight:900; padding:8px 12px; white-space:nowrap;">
                                                                    Tercapai Lv {{ (int)$sa['level'] }}
                                                                </span>
                                                            </div>

                                                            <div class="summary-item-body">
                                                                <div class="tx">Indikator ini sudah memenuhi capaian level Anda.</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="summary-item">
                                                        <div class="summary-item-head">
                                                            <div>
                                                                <div class="summary-item-title">Tidak ada data indikator tercapai.</div>
                                                            </div>
                                                            <span class="badge bg-secondary-subtle text-secondary"
                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px; white-space:nowrap;">-</span>
                                                        </div>
                                                        <div class="summary-item-body">
                                                            <div class="tx">Tidak ada data indikator tercapai.</div>
                                                        </div>
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
                                        Anda belum mencapai sesuai dengan level jabatan Anda (Target Level {{ $targetLevel }}).
                                        Maka direkomendasikan:
                                    </span>

                                    <div class="summary-rek">
                                        <div class="summary-sections">

                                            <div class="summary-sec">
                                                <div class="summary-sec-head">
                                                    <div class="summary-sec-title">
                                                        <i class="ri-checkbox-circle-line"></i> Sudah tercapai
                                                    </div>
                                                    <div class="summary-sec-note">{{ count($summaryAchList) }} item</div>
                                                </div>

                                                @if(count($summaryAchList))
                                                    @foreach($summaryAchList as $sa)
                                                        <div class="summary-item">
                                                            <div class="summary-item-head">
                                                                <div>
                                                                    <div class="summary-item-title">{{ $sa['name'] }}</div>
                                                                    @if(!empty($sa['code']))
                                                                        <div class="summary-item-code">
                                                                            <i class="ri-hashtag"></i> <span>Kode: {{ $sa['code'] }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <span class="badge bg-success-subtle text-success"
                                                                      style="border-radius:999px; font-weight:900; padding:8px 12px; white-space:nowrap;">
                                                                    Tercapai Lv {{ (int)$sa['level'] }}
                                                                </span>
                                                            </div>

                                                            <div class="summary-item-body">
                                                                <div class="tx">Indikator ini sudah memenuhi capaian level Anda.</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="summary-item">
                                                        <div class="summary-item-head">
                                                            <div>
                                                                <div class="summary-item-title">Belum ada indikator yang memenuhi target.</div>
                                                            </div>
                                                            <span class="badge bg-secondary-subtle text-secondary"
                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px; white-space:nowrap;">-</span>
                                                        </div>
                                                        <div class="summary-item-body">
                                                            <div class="tx">Belum ada indikator yang memenuhi target.</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="summary-sec">
                                                <div class="summary-sec-head">
                                                    <div class="summary-sec-title">
                                                        <i class="ri-arrow-up-circle-line"></i> Perlu ditingkatkan
                                                    </div>
                                                    <div class="summary-sec-note">{{ count($summaryRekList) ?: 0 }} rekomendasi</div>
                                                </div>

                                                @if(count($summaryRekList))
                                                    @foreach($summaryRekList as $sr)
                                                        <div class="summary-item">
                                                            <div class="summary-item-head">
                                                                <div>
                                                                    <div class="summary-item-title">{{ $sr['name'] ?? '-' }}</div>
                                                                    @if(!empty($sr['code']))
                                                                        <div class="summary-item-code">
                                                                            <i class="ri-hashtag"></i> <span>Kode: {{ $sr['code'] }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <span class="badge bg-danger-subtle text-danger"
                                                                      style="border-radius:999px; font-weight:900; padding:8px 12px; white-space:nowrap;">
                                                                    Rekom Lv {{ (int)$sr['level'] }}
                                                                </span>
                                                            </div>

                                                            <div class="summary-item-body">
                                                                <div class="tx">{{ $sr['text'] }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="summary-item">
                                                        <div class="summary-item-head">
                                                            <div>
                                                                <div class="summary-item-title">Belum ada rekomendasi yang cocok.</div>
                                                            </div>
                                                            <span class="badge bg-secondary-subtle text-secondary"
                                                                  style="border-radius:999px; font-weight:900; padding:8px 12px; white-space:nowrap;">-</span>
                                                        </div>
                                                        <div class="summary-item-body">
                                                            <div class="tx">Belum ada rekomendasi gap yang cocok dengan target level Anda.</div>
                                                        </div>
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
                                        Target level belum bisa ditentukan dari jenjang/min-max, jadi ringkasan tidak dapat dihitung.
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- ✅ CARD DETAIL (PUNYA TOGGLE) --}}
                        <div class="indikator-card">
                            <div class="head">
                                <p class="ttl mb-0"><i class="ri-list-check-2"></i> Detail Indikator & Rekomendasi</p>

                                <div class="d-flex align-items-center gap-2">
                                    <div class="count">{{ $data->count() }} indikator (di halaman ini)</div>

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
                                @foreach($data->values() as $idx => $row)
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

                                        $wajibLevels = $getWajibLevels($jenjangPtk, $levelMin, $levelMax);
                                        $cid = 'rek_' . preg_replace('/[^a-zA-Z0-9]/', '', (string)($ptk->nip ?? 'x')) . '_' . $idx;
                                    @endphp

                                    <div class="indikator-item">
                                        <div class="indikator-row">
                                            <div class="indikator-grid">

                                                <div>
                                                    <div class="cell-title">Nomor</div>
                                                    <div class="no-box">{{ $idx + 1 }}</div>
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
            </div>
        @endif
    </div>
</div>
@endsection