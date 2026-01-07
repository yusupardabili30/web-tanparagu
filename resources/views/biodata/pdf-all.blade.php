<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Daftar Biodata PTK (Per Peserta)</title>

    <style>
        /* ================================
        PAGE PDF (A4 1 LEMBAR)
        ================================ */
        @page {
            size: A4;
            margin: 8mm 10mm 8mm 10mm;
            /* margin halaman */
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            color: #111827;
        }

        /* ================================
        WATERMARK
        ================================ */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 54px;
            font-weight: 800;
            color: #1a3f6b;
            opacity: 0.05;
            z-index: -1;
            letter-spacing: 4px;
            white-space: nowrap;
        }

        /* ================================
        CONTAINER (FULL LEBAR)
        ================================ */
        .container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            background: #ffffff;
            padding: 12px 0 120px 0;
            box-sizing: border-box;
        }

        /* ================================
        INNER (FIX: MARGIN KIRI=KANAN, BIAR GA TEMBUS)
        total inset konten = @page margin(10mm) + inner padding(4mm) = 14mm
        sama kayak footer/ttd
        ================================ */
        .inner {
            padding: 0 4mm;
            box-sizing: border-box;
            width: 100%;
        }

        /* ================================
        HEADER
        ================================ */
        .header {
            border-bottom: 4px solid #1a3f6b;
            padding-bottom: 10px;
            margin-bottom: 12px;
            width: 96% !important;
        }

        /* header table 3 kolom: logo - text - spacer (biar center) */
        .header-table {
            width: 100% !important;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            border: 0;
            vertical-align: middle;
            padding: 0;
        }

        .header-col-logo,
        .header-col-spacer {
            width: 150px;
        }

        .header-col-logo {
            text-align: left;
        }

        .header-col-spacer {
            text-align: right;
        }

        .header-col-center {
            text-align: center;
        }

        .header-col-logo img {
            width: 130px;
            height: auto;
        }

        .title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #1a3f6b;
            line-height: 1.1;
        }

        .subtitle {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-top: 3px;
        }

        .kegiatan-date {
            font-size: 11px;
            font-weight: 500;
            color: #1a3f6b;
            margin-top: 2px;
        }

        .print-date {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Info filter untuk halaman pertama */
        .filter-info {
            background: #eef5ff;
            border-left: 4px solid #1a3f6b;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 11px;
            width: 96% !important;
        }

        .total-info {
            font-weight: bold;
            color: #1a3f6b;
            margin-bottom: 15px;
            font-size: 11px;
            width: 96% !important;
        }

        /* Informasi peserta di atas */
        .participant-header {
            background: #f0f7ff;
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 4px solid #1a3f6b;
            font-size: 11px;
            width: 96% !important;
        }

        .participant-header strong {
            color: #1a3f6b;
        }

        /* ================================
        SECTION
        ================================ */
        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 800;
            padding: 6px 10px;
            background: #1a3f6b;
            color: #ffffff;
            border-radius: 6px;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
            display: block;
            width: 94% !important;
            box-sizing: border-box;
        }

        /* ================================
        TABLE (FULL LEBAR + RAPI)
        ================================ */
        table {
            width: 96% !important;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: top;
            line-height: 1.25;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            width: 30%;
            background: #f1f5f9;
            font-weight: 800;
            color: #1a3f6b;
            text-align: left;
        }

        td {
            background: #ffffff;
            color: #111827;
        }

        tr:nth-child(even) td {
            background: #fbfdff;
        }

        small {
            font-size: 10px;
            color: #334155;
        }

        /* ================================
        SIGNATURE & FOOTER (FIXED BAWAH)
        ================================ */
        .signature-section {
            position: fixed;
            right: 14mm;
            /* selaras dengan konten (10mm + 4mm) */
            bottom: 20mm;
            text-align: right;
            width: 60%;
        }

        .signature-box {
            min-height: 55px;
            margin: 6px 0 4px;
        }

        .signature-name {
            font-weight: 800;
            font-size: 12px;
            border-top: 2px solid #1a3f6b;
            padding-top: 4px;
            display: inline-block;
            color: #1a3f6b;
        }

        .signature-title {
            font-size: 10px;
            color: #475569;
        }

        .no-signature {
            color: #64748b;
            font-style: italic;
            padding: 6px 10px;
            border: 1px dashed #94a3b8;
            display: inline-block;
            background: #f8fafc;
            font-size: 10px;
        }

        .footer {
            position: fixed;
            left: 14mm;
            /* selaras dengan konten (10mm + 4mm) */
            right: 14mm;
            /* selaras dengan konten (10mm + 4mm) */
            bottom: 8mm;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            font-size: 10px;
            color: #475569;
            text-align: center;
        }

        /* Page number */
        .page-number {
            position: fixed;
            bottom: 15mm;
            left: 14mm;
            font-size: 10px;
            color: #64748b;
        }

        /* Page break untuk setiap peserta */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
    $totalPages = count($data);
    $currentPage = 0;
    @endphp

    @foreach($data as $key => $item)
    @php
    $currentPage++;

    // Format data seperti di single PDF
    $item->tgl_lahir_formatted = $item->tgl_lahir ? date('d-m-Y', strtotime($item->tgl_lahir)) : '-';
    $item->last_update_formatted = $item->last_update ? date('d-m-Y H:i:s', strtotime($item->last_update)) : '-';
    $item->jenis_kelamin_formatted = $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    $item->has_signature = !empty($item->ttd_base64);

    // Format tanggal kegiatan jika ada
    $start_date_formatted = $item->start_date ? date('d-m-Y', strtotime($item->start_date)) : '';
    $end_date_formatted = $item->end_date ? date('d-m-Y', strtotime($item->end_date)) : '';

    $kegiatan_date_display = '';
    if ($start_date_formatted && $end_date_formatted) {
    $kegiatan_date_display = "$start_date_formatted s/d $end_date_formatted";
    } elseif ($start_date_formatted) {
    $kegiatan_date_display = "Mulai: $start_date_formatted";
    } elseif ($end_date_formatted) {
    $kegiatan_date_display = "Selesai: $end_date_formatted";
    }
    @endphp

    <div class="watermark">BIODATA PESERTA</div>

    <div class="container">
        <div class="inner">

            <!-- HEADER -->
            <div class="header">
                <table class="header-table">
                    <tr>
                        <td class="header-col-logo">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('build/images/logotutwuri.png'))) }}">
                        </td>

                        <td class="header-col-center">
                            @if($currentPage == 1)
                            <div class="title">BIODATA</div>
                            <div class="subtitle">{{ $item->kegiatan_name ?? '-' }}</div>
                            @if($kegiatan_date_display)
                            <div class="kegiatan-date">Tanggal: {{ $kegiatan_date_display }}</div>
                            @endif
                            <div class="print-date">Dicetak: {{ $exportDate }}</div>
                            @else
                            <div class="title">BIODATA</div>
                            <div class="subtitle">{{ $item->kegiatan_name ?? '-' }}</div>
                            @if($kegiatan_date_display)
                            <div class="kegiatan-date">Tanggal: {{ $kegiatan_date_display }}</div>
                            @endif
                            <div class="print-date">Halaman {{ $currentPage }} dari {{ $totalPages }}</div>
                            @endif
                        </td>

                        <td class="header-col-spacer"></td>
                    </tr>
                </table>
            </div>



            <!-- IDENTITAS -->
            <div class="section">
                <div class="section-title">IDENTITAS PRIBADI</div>
                <table>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $item->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>{{ $item->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>{{ $item->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $item->jenis_kelamin_formatted }}</td>
                    </tr>
                    <tr>
                        <th>Tempat / Tgl Lahir</th>
                        <td>{{ $item->tempat_lahir ?? '-' }}, {{ $item->tgl_lahir_formatted }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{ $item->agama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan</th>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- KONTAK -->
            <div class="section">
                <div class="section-title">INFORMASI KONTAK</div>
                <table>
                    <tr>
                        <th>Email</th>
                        <td>{{ $item->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. HP</th>
                        <td>{{ $item->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>NPWP</th>
                        <td>{{ $item->npwp ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- JABATAN -->
            <div class="section">
                <div class="section-title">JABATAN DAN UNIT KERJA</div>
                <table>
                    <tr>
                        <th>Jenjang Jabatan</th>
                        <td>{{ $item->jenjang_jabatan ?? '-' }}</td>
                    </tr>

                    @if($item->nama_sekolah)
                    <tr>
                        <th>Sekolah</th>
                        <td>
                            {{ $item->nama_sekolah }}
                            @if($item->npsn)<br><small>NPSN: {{ $item->npsn }}</small>@endif
                        </td>
                    </tr>
                    @else
                    <tr>
                        <th>Instansi</th>
                        <td>{{ $item->instansi ?? '-' }}</td>
                    </tr>
                    @endif

                    <tr>
                        <th>Alamat Kantor</th>
                        <td>{{ $item->alamat_kantor ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kota</th>
                        <td>{{ $item->nama_kota ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Provinsi</th>
                        <td>{{ $item->provinsi ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- BANK -->
            <div class="section">
                <div class="section-title">INFORMASI BANK</div>
                <table>
                    <tr>
                        <th>Nama Bank</th>
                        <td>{{ $item->nama_bank ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Rekening</th>
                        <td>{{ $item->no_rekening ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Atas Nama</th>
                        <td>{{ $item->atas_nama_rekening ?? '-' }}</td>
                    </tr>
                </table>
            </div>

        </div><!-- /.inner -->

        <!-- TTD -->
        <div class="signature-section">
            <div>Peserta Kegiatan,</div>

            <div class="signature-box">
                @if($item->has_signature && !empty($item->ttd_base64))
                <img src="{{ $item->ttd_base64 }}" style="max-width:160px; max-height:55px;">
                @else
                <div class="no-signature">Tanda tangan tidak tersedia</div>
                @endif
            </div>

            <div class="signature-name">{{ $item->nama ?? 'Peserta' }}</div><br>
            <div class="signature-title">NIP. {{ $item->nip ?? '-' }}</div>
        </div>

        <!-- Page number -->
        <div class="page-number">
            Halaman {{ $currentPage }} dari {{ $totalPages }}
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Dicetak oleh Sistem Informasi<br>
            © {{ date('Y') }} - All rights reserved
        </div>

    </div><!-- /.container -->

    <!-- Page break untuk setiap peserta kecuali yang terakhir -->
    @if(!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif

    @endforeach
</body>

</html>