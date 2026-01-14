<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Instrumen PTK - Rekomendasi dengan Gap Analysis</title>
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
            margin-bottom: 25px;
            border: 1px solid #ccc;
            page-break-inside: avoid;
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

        .ptk-info {
            background: #f9f9f9;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .info-table td {
            padding: 3px 5px;
            border: none;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            width: 100px;
            white-space: nowrap;
        }

        .info-value {
            padding-left: 10px;
        }

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

        /* Tabel utama */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-top: 5px;
        }

        .data-table th {
            background: #34495e;
            color: white;
            padding: 4px 5px;
            border: 1px solid #2c3e50;
            text-align: left;
        }

        .data-table td {
            padding: 4px 5px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* Badge Level */
        .level-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            min-width: 60px;
        }

        .badge-level-2 {
            background: #17a2b8;
            color: white;
        }

        .badge-level-3 {
            background: #007bff;
            color: white;
        }

        .badge-level-4 {
            background: #ffc107;
            color: white;
        }

        .badge-level-5 {
            background: #28a745;
            color: white;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        /* Badge Status */
        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-success {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .badge-warning {
            background: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
        }

        .badge-danger {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        /* Rekomendasi Gap */
        .rekomendasi-gap {
            margin-top: 3px;
        }

        .gap-item {
            padding: 3px;
            margin-bottom: 3px;
            border-left: 3px solid #dc3545;
            background: #f8f9fa;
        }

        .gap-level {
            font-weight: bold;
            color: #dc3545;
            font-size: 8px;
        }

        .gap-text {
            font-size: 8px;
            line-height: 1.2;
            margin-top: 2px;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-muted {
            color: #6c757d;
            font-size: 7px;
        }

        .summary {
            background: #f0f0f0;
            padding: 5px 8px;
            border-top: 1px solid #ccc;
            font-size: 9px;
        }

        .summary-row {
            margin-bottom: 2px;
            display: flex;
        }

        .summary-label {
            font-weight: bold;
            width: 120px;
            flex-shrink: 0;
        }

        .summary-value {
            flex-grow: 1;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        /* Sub Indikator */
        .sub-indikator-name {
            font-size: 8px;
            color: #333;
            margin-top: 2px;
        }

        .sub-indikator-code {
            font-family: monospace;
            font-size: 8px;
            color: #495057;
        }

        /* Level harus */
        .level-harus-container {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .level-harus-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            text-align: center;
            width: 60px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="title">LAPORAN HASIL INSTRUMEN PTK DENGAN REKOMENDASI GAP ANALYSIS</div>
        <div class="subtitle">Penilaian Kompetensi Profesional Berbasis Level Kompetensi</div>
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
        $totalData = count($groupedData);
        @endphp
        <div class="filter-row">
            <span class="filter-label">Jumlah PTK:</span>
            <span class="filter-value text-bold">{{ $totalData }} PTK</span>
        </div>
    </div>

    <!-- Data per PTK -->
    @foreach($groupedData as $nip => $dataRows)
    @if($dataRows->isNotEmpty())
    @php
    $firstRow = $dataRows->first();
    
    // Proses rekomendasi untuk setiap row
    $processedRows = [];
    foreach($dataRows as $row) {
        // Simulasi fungsi getRekomendasiWithGap dari controller
        $jenjangJabatan = $row->jenjang_jabatan ?? 'Guru Pertama';
        $levelJawaban = $row->level_jawaban ?? $row->level ?? 0;
        $subIndikatorId = $row->sub_indikator_id;
        $tahap = $row->tahap;
        $entity = $row->entity;
        $subIndikatorCode = $row->sub_indikator_code;
        
        // Tentukan rentang level berdasarkan jenjang jabatan
        $levelRanges = [
            'Guru Pertama' => ['min' => 2, 'max' => 2],
            'Guru Muda'    => ['min' => 2, 'max' => 3],
            'Guru Madya'   => ['min' => 2, 'max' => 4],
            'Guru Utama'   => ['min' => 2, 'max' => 5]
        ];
        
        $range = $levelRanges[$jenjangJabatan] ?? $levelRanges['Guru Pertama'];
        $levelMin = $range['min'];
        $levelMax = $range['max'];
        
        // Simulasi ambil rekomendasi dari database (dalam real case, ini query)
        // Untuk contoh, kita buat dummy rekomendasi
        $rekomendasiSemua = [];
        for($i = $levelMin; $i <= $levelMax; $i++) {
            $rekomendasiSemua[] = (object)[
                'level' => $i,
                'rekomendasi' => "Rekomendasi untuk Level $i: Pengembangan kompetensi pada level ini meliputi strategi lanjutan untuk meningkatkan kemampuan mengajar."
            ];
        }
        
        // Pisahkan rekomendasi yang dicapai vs gap
        $rekomendasiDicapai = [];
        $rekomendasiGap = [];
        
        foreach($rekomendasiSemua as $rek) {
            if($rek->level <= $levelJawaban) {
                $rekomendasiDicapai[] = [
                    'level' => $rek->level,
                    'rekomendasi' => $rek->rekomendasi
                ];
            } else {
                $rekomendasiGap[] = [
                    'level' => $rek->level,
                    'rekomendasi' => $rek->rekomendasi
                ];
            }
        }
        
        // Tentukan status
        $levelGapCount = count($rekomendasiGap);
        if($levelGapCount == 0) {
            $status = 'Mencapai Semua Level';
            $statusClass = 'success';
        } elseif($levelGapCount == 1 && $levelMax - $levelJawaban == 1) {
            $status = 'Mendekati Target';
            $statusClass = 'warning';
        } else {
            $status = 'Perlu Peningkatan';
            $statusClass = 'danger';
        }
        
        $row->rekomendasi_info = [
            'jenjang' => $jenjangJabatan,
            'level_jawaban' => $levelJawaban,
            'level_min' => $levelMin,
            'level_max' => $levelMax,
            'rekomendasi_dicapai' => $rekomendasiDicapai,
            'rekomendasi_gap' => $rekomendasiGap,
            'status' => $status,
            'status_class' => $statusClass
        ];
        
        $processedRows[] = $row;
    }
    @endphp

    <div class="ptk-card">
        <!-- Header PTK -->
        <div class="ptk-header">
            <span>{{ $firstRow->nama ?? 'Nama tidak tersedia' }}</span>
            <span class="kegiatan-badge">{{ $firstRow->kegiatan_name ?? 'Kegiatan' }}</span>
        </div>

        <!-- Info PTK -->
        <div class="ptk-info">
            <table class="info-table">
                <tr>
                    <td class="info-label">NIP:</td>
                    <td class="info-value">{{ $nip }}</td>
                    <td class="info-label">Jenjang:</td>
                    <td class="info-value">{{ $firstRow->jenjang_jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Entity:</td>
                    <td class="info-value">{{ $firstRow->entity ?? '-' }}</td>
                    <td class="info-label">Tahap:</td>
                    <td class="info-value">{{ $firstRow->tahap ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Instansi:</td>
                    <td class="info-value instansi-info" colspan="3">
                        @if(!empty($firstRow->nama_sekolah))
                            {{ $firstRow->nama_sekolah }}
                            @if(!empty($firstRow->npsn))
                                <span class="npsn-badge">NPSN: {{ $firstRow->npsn }}</span>
                            @endif
                        @elseif(!empty($firstRow->instansi))
                            {{ $firstRow->instansi }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Kota:</td>
                    <td class="info-value">{{ $firstRow->nama_kota ?? '-' }}</td>
                    <td class="info-label">Pangkat:</td>
                    <td class="info-value">{{ $firstRow->pangkat ?? '-' }} {{ $firstRow->golongan_ruang ? '('.$firstRow->golongan_ruang.')' : '' }}</td>
                </tr>
            </table>
        </div>

        <!-- Tabel Data -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="15%">SUB INDIKATOR</th>
                    <th width="25%">DESKRIPSI</th>
                    <th width="10%">LEVEL DICAPAI</th>
                    <th width="10%">LEVEL HARUS</th>
                    <th width="10%">STATUS</th>
                    <th width="25%">REKOMENDASI (GAP)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processedRows as $index => $row)
                @php
                $info = $row->rekomendasi_info;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="sub-indikator-code">{{ $row->sub_indikator_code }}</div>
                        <div class="sub-indikator-name">{{ Str::limit($row->sub_indikator_name ?? '-', 30) }}</div>
                    </td>
                    <td>{{ Str::limit($row->sub_indikator_name ?? '-', 50) }}</td>
                    <td class="text-center">
                        @if($info['level_jawaban'] > 0)
                        <span class="level-badge badge-level-{{ $info['level_jawaban'] }}">
                            Level {{ $info['level_jawaban'] }}
                        </span>
                        @else
                        <span class="level-badge badge-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="level-harus-container">
                            @for($i = $info['level_min']; $i <= $info['level_max']; $i++)
                            <span class="level-harus-badge badge-level-{{ $i }}">
                                Level {{ $i }}
                            </span>
                            @endfor
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge-status badge-{{ $info['status_class'] }}">
                            {{ $info['status'] }}
                        </span>
                    </td>
                    <td>
                        @if(count($info['rekomendasi_gap']) > 0)
                        <div class="rekomendasi-gap">
                            @foreach($info['rekomendasi_gap'] as $rek)
                            <div class="gap-item">
                                <div class="gap-level">Gap Level {{ $rek['level'] }}</div>
                                <div class="gap-text">{{ Str::limit($rek['rekomendasi'], 80) }}</div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center text-success">
                            <span class="badge-status badge-success">
                                Sudah mencapai semua level
                            </span>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary PTK -->
        <div class="summary">
            <div class="summary-row">
                <span class="summary-label">Jumlah Indikator:</span>
                <span class="summary-value text-bold">{{ count($processedRows) }} indikator</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Status Keseluruhan:</span>
                <span class="summary-value">
                    @php
                    $statusCounts = ['Mencapai Semua Level' => 0, 'Mendekati Target' => 0, 'Perlu Peningkatan' => 0];
                    foreach($processedRows as $row) {
                        $statusCounts[$row->rekomendasi_info['status']]++;
                    }
                    @endphp
                    @foreach($statusCounts as $status => $count)
                    @if($count > 0)
                    <span class="badge-status badge-{{ 
                        $status == 'Mencapai Semua Level' ? 'success' : 
                        ($status == 'Mendekati Target' ? 'warning' : 'danger') 
                    }}" style="margin-right: 5px;">
                        {{ $status }}: {{ $count }}
                    </span>
                    @endif
                    @endforeach
                </span>
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
            <p style="margin: 2px 0;">Laporan ini menunjukkan gap antara level kompetensi yang dicapai dengan level yang harus dicapai berdasarkan jenjang jabatan</p>
        </div>

        <div style="margin-top: 20px;">
            <p style="margin: 2px 0;">© {{ date('Y') }} - Sistem TanpaRagu</p>
            <p style="margin: 2px 0;">Dicetak: {{ $tanggal }}</p>
        </div>
    </div>
</body>

</html>