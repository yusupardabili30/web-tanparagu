<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Instrumen PTK</title>
    <style>
        /* CSS SIMPLE untuk DomPDF */
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            margin: 0;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }

        .subtitle {
            font-size: 11px;
            margin: 3px 0;
        }

        .date {
            font-size: 9px;
            color: #666;
        }

        .filter-box {
            background: #f0f0f0;
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 15px;
        }

        .filter-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .filter-row {
            margin-bottom: 3px;
            display: flex;
        }

        .filter-label {
            font-weight: bold;
            width: 120px;
            flex-shrink: 0;
        }

        .filter-value {
            flex-grow: 1;
        }

        .ptk-card {
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }

        .ptk-header {
            background: #2c3e50;
            color: white;
            padding: 5px 8px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kegiatan-badge {
            background: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        /* CSS untuk tabel info PTK */


        .info-label {
            font-weight: bold;
            width: 100px;
            padding: 1px 5px 1px 0;
            vertical-align: top;
            white-space: nowrap;
        }

        .info-value {
            padding: 1px 0;
            vertical-align: top;
        }

        /* Atau jika tetap ingin menggunakan flexbox dengan alignment yang tepat: */
        .ptk-info-flex {
            background: #f9f9f9;
            padding: 5px 8px;
            border-bottom: 1px solid #ddd;
        }

        .info-row {
            display: flex;
            margin-bottom: 2px;
            min-height: 12px;
            align-items: flex-start;
        }

        .info-label-flex {
            font-weight: bold;
            width: 100px;
            flex-shrink: 0;
            padding-right: 10px;
            text-align: right;
        }

        .info-value-flex {
            flex-grow: 1;
            line-height: 1.3;
        }

        .info-colon {
            font-weight: bold;
            padding-right: 8px;
            flex-shrink: 0;
        }

        .info-value {
            flex-grow: 1;
            line-height: 1.3;
        }

        /* Alternatif: menggunakan display: grid */
        .ptk-info-grid {
            background: #f9f9f9;
            padding: 5px 8px;
            border-bottom: 1px solid #ddd;
            display: grid;
            grid-template-columns: auto auto 1fr;
            grid-gap: 0 5px;
            align-items: start;
        }

        .info-item {
            display: contents;
            /* Membuat elemen anak langsung menjadi grid items */
        }

        .info-label-grid {
            font-weight: bold;
            text-align: right;
            grid-column: 1;
        }

        .info-colon-grid {
            font-weight: bold;
            grid-column: 2;
        }

        .info-value-grid {
            grid-column: 3;
            line-height: 1.3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        th {
            background: #34495e;
            color: white;
            padding: 4px 5px;
            border: 1px solid #2c3e50;
            text-align: left;
        }

        td {
            padding: 4px 5px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .level-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            color: white;
            text-align: center;
            min-width: 50px;
        }

        .level-1 {
            background: #A9A9A9;
        }

        .level-2 {
            background: ##A9A9A9;
        }

        .level-3 {
            background: ##A9A9A9;
        }

        .level-4 {
            background: ##A9A9A9;
        }

        .level-5 {
            background: ##A9A9A9;
        }

        .level-name {
            display: block;
            font-size: 7px;
            margin-top: 1px;
            font-weight: normal;
        }

        .summary {
            background: #f0f0f0;
            padding: 5px 8px;
            border-top: 1px solid #ccc;
        }

        .summary-row {
            margin-bottom: 2px;
            display: flex;
        }

        .summary-label {
            font-weight: bold;
            width: 100px;
            flex-shrink: 0;
        }

        .summary-value {
            flex-grow: 1;
        }

        .overall-summary {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ccc;
        }

        .overall-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 10px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 14px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 8px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        /* Style tambahan untuk informasi instansi */
        .instansi-info {
            font-size: 9px;
            color: #555;
        }

        .npsn-badge {
            display: inline-block;
            background: #95a5a6;
            color: white;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            margin-left: 5px;
        }

        .sub-indikator-name {
            font-size: 8px;
            color: #333;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="title">LAPORAN HASIL INSTRUMEN PEMETAAN KEBUTUHAN PEMBELAJARAN GURU</div>
        <div class="subtitle">Penilaian Kompetensi Profesional</div>
        <div class="date">Dicetak: {{ $tanggal }}</div>
    </div>

    <!-- Filter Info -->
    <div class="filter-box">
        <div class="filter-title">FILTER YANG DIGUNAKAN</div>
        <div class="filter-row">
            <span class="filter-label">Pencarian:</span>
            <span class="filter-value">{{ $search ?: 'Semua' }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Kegiatan:</span>
            <span class="filter-value">{{ $kegiatan_name ?: 'Semua' }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Tahap:</span>
            <span class="filter-value">{{ $tahap ? "Tahap $tahap" : 'Semua' }}</span>
        </div>
        @php
        $totalData = 0;
        foreach($groupedData as $dataGroup) {
        $totalData += count($dataGroup);
        }
        @endphp
        <div class="filter-row">
            <span class="filter-label">Jumlah Data:</span>
            <span class="filter-value text-bold">{{ $totalData }} data dari {{ count($groupedData) }} PTK</span>
        </div>
    </div>

    <!-- Data per PTK -->
    @foreach($groupedData as $nip => $data)
    @if($data->isNotEmpty())
    <div class="ptk-card">
        <!-- Header PTK dengan Kegiatan -->
        <div class="ptk-header">
            <span>{{ $data[0]->nama ?? 'Nama tidak tersedia' }}</span>
            <!-- <span class="kegiatan-badge">{{ $data[0]->kegiatan_name ?? 'Kegiatan' }}</span> -->
        </div>

        <!-- Info PTK -->
        <div class="ptk-info">
            <table class="info-table">
                <tr>
                    <td class="info-label">NIP</td>
                    <td class="info-value">{{ $nip }}</td>
                </tr>
                <tr>
                    <td class="info-label">Entity</td>
                    <td class="info-value">{{ $data[0]->entity ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Kegiatan</td>
                    <td class="info-value">{{ $data[0]->kegiatan_name ?? 'Kegiatan' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Kota</td>
                    <td class="info-value">{{ $data[0]->nama_kota ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Sekolah/Instansi</td>
                    <td class="info-value instansi-info">
                        @php
                        // Logika: jika ada sekolah, tampilkan sekolah + NPSN
                        // jika tidak ada sekolah, tampilkan instansi
                        $sekolahInfo = '';
                        if (!empty($data[0]->nama_sekolah)) {
                        $sekolahInfo = $data[0]->nama_sekolah;
                        if (!empty($data[0]->npsn)) {
                        $sekolahInfo .= ' <span class="npsn-badge">NPSN: ' . $data[0]->npsn . '</span>';
                        }
                        } elseif (!empty($data[0]->instansi)) {
                        $sekolahInfo = $data[0]->instansi;
                        } else {
                        $sekolahInfo = '-';
                        }
                        @endphp
                        {!! $sekolahInfo !!}
                    </td>
                </tr>
                @if($data[0]->pangkat)
                <tr>
                    <td class="info-label">Jabatan</td>
                    <td class="info-value">
                        {{ $data[0]->pangkat }}
                        @if($data[0]->jenjang_jabatan) - {{ $data[0]->jenjang_jabatan }} @endif
                        @if($data[0]->golongan_ruang) ({{ $data[0]->golongan_ruang }}) @endif
                    </td>
                </tr>
                @endif
                <tr>
                    <td class="info-label">Tahap</td>
                    <td class="info-value">{{ $data[0]->tahap ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="20%">SUB INDIKATOR CODE</th>
                    <th width="60%">SUB INDIKATOR NAME</th>
                    <th width="15%">LEVEL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $row)
                @php
                // Definisikan nama level berdasarkan angka
                $levelNames = [
                1 => 'Sangat Rendah',
                2 => 'Penerapan',
                3 => 'Analisis',
                4 => 'Evaluasi',
                5 => 'Pembimbingan Rekan Sejawat'
                ];
                $levelName = $levelNames[$row->level] ?? 'Tidak Diketahui';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->sub_indikator_code }}</td>
                    <td>
                        <div class="sub-indikator-name">
                            {{ $row->sub_indikator_name ?? 'Nama tidak tersedia' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="level-badge level-{{ $row->level }}">
                            Level {{ $row->level }}
                            <span class="level-name">{{ $levelName }}</span>
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary PTK -->
        <div class="summary">
            <div class="summary-row">
                <span class="summary-label">Jumlah Data:</span>
                <span class="summary-value text-bold">{{ count($data) }} indikator</span>
            </div>
        </div>
    </div>
    </div>

    <!-- Page Break -->
    @if(!$loop->last)
    <div class="page-break"></div>
    @endif
    @endif
    @endforeach

    <!-- Footer -->
    <div class="footer">
        <div style="margin-bottom: 10px;">
            <p style="margin: 2px 0;"><strong>Catatan:</strong> Dokumen untuk keperluan internal evaluasi</p>
            <p style="margin: 2px 0;">Dicetak otomatis oleh Sistem TanpaRagu</p>
        </div>

        <div style="margin-top: 20px;">
            <p style="margin: 2px 0;">© {{ date('Y') }} - Sistem TanpaRagu</p>
            <p style="margin: 2px 0;">Dicetak: {{ $tanggal }}</p>
        </div>
    </div>
</body>

</html>