<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Biodata {{ $data->nama ?? 'Peserta' }}</title>

    <style>
        /* ================================
        PAGE PDF (A4 1 LEMBAR)
        ================================ */
        @page {
            size: A4;
            margin: 8mm 10mm 8mm 10mm; /* margin halaman */
        }

        html, body { margin: 0; padding: 0; }

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

            /* padding kiri-kanan kita pindah ke .inner biar rapi & simetris */
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

        .header-col-logo { text-align: left; }
        .header-col-spacer { text-align: right; }
        .header-col-center { text-align: center; }

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

        .print-date {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ================================
        SECTION
        ================================ */
        .section { margin-bottom: 10px; }

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

        th, td {
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

        td { background: #ffffff; color: #111827; }

        tr:nth-child(even) td { background: #fbfdff; }

        small { font-size: 10px; color: #334155; }

        /* ================================
        SIGNATURE & FOOTER (FIXED BAWAH)
        ================================ */
        .signature-section {
            position: fixed;
            right: 14mm; /* selaras dengan konten (10mm + 4mm) */
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

        .signature-title { font-size: 10px; color: #475569; }

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
            left: 14mm;   /* selaras dengan konten (10mm + 4mm) */
            right: 14mm;  /* selaras dengan konten (10mm + 4mm) */
            bottom: 8mm;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            font-size: 10px;
            color: #475569;
            text-align: center;
        }
    </style>
</head>

<body>
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
                            <div class="title">BIODATA PESERTA</div>
                            <div class="subtitle">{{ $data->kegiatan_name ?? 'Kegiatan' }}</div>
                            <div class="print-date">Dicetak: {{ date('d-m-Y H:i:s') }}</div>
                        </td>

                        <td class="header-col-spacer"></td>
                    </tr>
                </table>
            </div>

            <!-- IDENTITAS -->
            <div class="section">
                <div class="section-title">IDENTITAS PRIBADI</div>
                <table>
                    <tr><th>Nama Lengkap</th><td>{{ $data->nama ?? '-' }}</td></tr>
                    <tr><th>NIP</th><td>{{ $data->nip ?? '-' }}</td></tr>
                    <tr><th>NIK</th><td>{{ $data->nik ?? '-' }}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>{{ $data->jenis_kelamin_formatted ?? ($data->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') }}</td></tr>
                    <tr><th>Tempat / Tgl Lahir</th><td>{{ $data->tempat_lahir ?? '-' }}, {{ isset($data->tgl_lahir) ? date('d-m-Y', strtotime($data->tgl_lahir)) : '-' }}</td></tr>
                    <tr><th>Agama</th><td>{{ $data->agama ?? '-' }}</td></tr>
                    <tr><th>Pendidikan</th><td>{{ $data->pendidikan ?? '-' }}</td></tr>
                </table>
            </div>

            <!-- KONTAK -->
            <div class="section">
                <div class="section-title">INFORMASI KONTAK</div>
                <table>
                    <tr><th>Email</th><td>{{ $data->email ?? '-' }}</td></tr>
                    <tr><th>No. HP</th><td>{{ $data->no_hp ?? '-' }}</td></tr>
                    <tr><th>NPWP</th><td>{{ $data->npwp ?? '-' }}</td></tr>
                </table>
            </div>

            <!-- JABATAN -->
            <div class="section">
                <div class="section-title">JABATAN DAN UNIT KERJA</div>
                <table>
                    <tr><th>Jenjang Jabatan</th><td>{{ $data->jenjang_jabatan ?? '-' }}</td></tr>

                    @if($data->nama_sekolah)
                    <tr>
                        <th>Sekolah</th>
                        <td>
                            {{ $data->nama_sekolah }}
                            @if($data->npsn)<br><small>NPSN: {{ $data->npsn }}</small>@endif
                        </td>
                    </tr>
                    @else
                    <tr><th>Instansi</th><td>{{ $data->instansi ?? '-' }}</td></tr>
                    @endif

                    <tr><th>Alamat Kantor</th><td>{{ $data->alamat_kantor ?? '-' }}</td></tr>
                    <tr><th>Kota</th><td>{{ $data->nama_kota ?? '-' }}</td></tr>
                    <tr><th>Provinsi</th><td>{{ $data->provinsi ?? '-' }}</td></tr>
                </table>
            </div>

            <!-- BANK -->
            <div class="section">
                <div class="section-title">INFORMASI BANK</div>
                <table>
                    <tr><th>Nama Bank</th><td>{{ $data->nama_bank ?? '-' }}</td></tr>
                    @if($data->kode_bank)
                    <tr><th>Kode Bank</th><td>{{ $data->kode_bank }}</td></tr>
                    @endif
                    <tr><th>No. Rekening</th><td>{{ $data->no_rekening ?? '-' }}</td></tr>
                    <tr><th>Atas Nama</th><td>{{ $data->atas_nama_rekening ?? '-' }}</td></tr>
                </table>
            </div>

            <!-- KEGIATAN -->
            <div class="section">
                <div class="section-title">KEGIATAN</div>
                <table>
                    <tr><th>Nama Kegiatan</th><td>{{ $data->kegiatan_name ?? '-' }}</td></tr>
                    @if($data->start_date && $data->end_date)
                    <tr><th>Periode</th><td>{{ date('d-m-Y', strtotime($data->start_date)) }} s/d {{ date('d-m-Y', strtotime($data->end_date)) }}</td></tr>
                    @endif
                    <tr><th>Terakhir Update</th><td>{{ isset($data->last_update) ? date('d-m-Y H:i:s', strtotime($data->last_update)) : '-' }}</td></tr>
                </table>
            </div>

        </div><!-- /.inner -->

        <!-- TTD -->
        <div class="signature-section">
            <div>Peserta Kegiatan,</div>

            <div class="signature-box">
                @if($data->has_signature && !empty($data->ttd_base64))
                <img src="{{ $data->ttd_base64 }}" style="max-width:160px; max-height:55px;">
                @else
                <div class="no-signature">Tanda tangan tidak tersedia</div>
                @endif
            </div>

            <div class="signature-name">{{ $data->nama ?? 'Peserta' }}</div><br>
            <div class="signature-title">NIP. {{ $data->nip ?? '-' }}</div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Dokumen ini dicetak otomatis oleh sistem<br>
            © {{ date('Y') }} - Sistem TanpaRagu
        </div>

    </div><!-- /.container -->
</body>
</html>
