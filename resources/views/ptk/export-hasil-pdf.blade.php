<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Instrumen - {{ $ptk->nama }}</title>
    <style>
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
        
        .ptk-info {
            background: #f9f9f9;
            padding: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
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
        
        .kegiatan-info {
            background: #eef7ff;
            padding: 8px;
            border: 1px solid #cce5ff;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-top: 10px;
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
        
        .level-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }
        
        .badge-level-2 { background: #17a2b8; color: white; }
        .badge-level-3 { background: #007bff; color: white; }
        .badge-level-4 { background: #ffc107; color: white; }
        .badge-level-5 { background: #28a745; color: white; }
        
        .rekomen-gap {
            margin-top: 2px;
            font-size: 8px;
            color: #666;
        }
        
        .gap-item {
            margin-bottom: 2px;
            padding-left: 8px;
            border-left: 2px solid #dc3545;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">HASIL INSTRUMEN PENILAIAN KOMPETENSI</div>
        <div class="subtitle">{{ $kegiatan->kegiatan_name }}</div>
        <div class="date">Dicetak: {{ $tanggal }}</div>
    </div>
    
    <div class="ptk-info">
        <table class="info-table">
            <tr>
                <td class="info-label">Nama:</td>
                <td>{{ $ptk->nama }}</td>
                <td class="info-label">NIP:</td>
                <td>{{ $ptk->nip }}</td>
            </tr>
            <tr>
                <td class="info-label">Jenjang:</td>
                <td>{{ $ptk->pangkatJabatan->jenjang_jabatan ?? '-' }}</td>
                <td class="info-label">Instansi:</td>
                <td>{{ $ptk->sekolah->nama_sekolah ?? $ptk->instansi }}</td>
            </tr>
        </table>
    </div>
    
    <div class="kegiatan-info">
        <strong>Kegiatan:</strong> {{ $kegiatan->kegiatan_name }}<br>
        <strong>Entity:</strong> {{ $kegiatan->entity }}<br>
        <strong>Periode:</strong> {{ date('d F Y', strtotime($kegiatan->start_date)) }} - {{ date('d F Y', strtotime($kegiatan->end_date)) }}
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Sub Indikator</th>
                <th width="15%">Kode</th>
                <th width="10%">Level Dicapai</th>
                <th width="15%">Level Harus</th>
                <th width="30%">Rekomendasi GAP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
                @php
                    $info = $row->rekomendasi_info ?? [];
                    $levelJawaban = (int)($row->level_jawaban ?? 0);
                    $levelMin = (int)($info['level_min'] ?? 0);
                    $levelMax = (int)($info['level_max'] ?? 0);
                    $rekomendasiGap = $info['rekomendasi_gap'] ?? [];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->sub_indikator_name }}</td>
                    <td>{{ $row->sub_indikator_code }}</td>
                    <td class="text-center">
                        @if($levelJawaban > 0)
                            <span class="level-badge badge-level-{{ $levelJawaban }}">
                                Level {{ $levelJawaban }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @for($i = $levelMin; $i <= $levelMax; $i++)
                            <span class="level-badge badge-level-{{ $i }}" style="margin-right: 2px;">
                                Lv {{ $i }}
                            </span>
                        @endfor
                    </td>
                    <td>
                        @if(count($rekomendasiGap) > 0)
                            <div class="rekomen-gap">
                                @foreach($rekomendasiGap as $rek)
                                    <div class="gap-item">
                                        <strong>Gap Level {{ $rek['level'] }}:</strong> {{ Str::limit($rek['rekomendasi'], 100) }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span style="color: #28a745;">✓ Memenuhi standar kompetensi</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Total Sub Indikator: {{ $totalIndikator }} | Total Skor: {{ $totalSkor }}</p>
        <p>© {{ date('Y') }} - Sistem TanpaRagu</p>
    </div>
</body>
</html>