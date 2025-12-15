<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Daftar Biodata PTK</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            background: #fff;
            max-width: 1000px;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #4a90e2;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }

        .subtitle {
            font-size: 11px;
            color: #777;
            margin-top: 3px;
        }

        .filter-info {
            background: #eef5ff;
            border-left: 4px solid #4a90e2;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: #4a90e2;
            color: white;
            padding: 6px;
            text-align: left;
            font-weight: 600;
        }

        .table td {
            padding: 6px;
            border-bottom: 1px solid #eee;
        }

        .table tr:nth-child(even) td {
            background: #f9f9f9;
        }

        .total-info {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <div class="title">DAFTAR BIODATA PESERTA</div>
            <div class="subtitle">Dicetak pada: {{ $exportDate }}</div>
        </div>

        @if(!empty($filterInfo))
        <div class="filter-info">
            <strong>Filter yang diterapkan:</strong>
            @if(isset($filterInfo['search']))
            <br>Pencarian: "{{ $filterInfo['search'] }}"
            @endif
            @if(isset($filterInfo['kegiatan']))
            <br>Kegiatan: {{ $filterInfo['kegiatan'] }}
            @endif
        </div>
        @endif

        <div class="total-info">Total Data: {{ $totalData }} peserta</div>

        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="100">NIP</th>
                    <th>Nama</th>
                    <th width="80">Gender</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th width="80">No. HP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $item)
                @if($loop->iteration % 25 == 0 && !$loop->first)
            </tbody>
        </table>

        <div class="page-break"></div>

        <div class="header">
            <div class="title">DAFTAR BIODATA PESERTA (Lanjutan)</div>
            <div class="subtitle">Halaman {{ ceil($loop->iteration / 25) + 1 }}</div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="100">NIP</th>
                    <th>Nama</th>
                    <th width="80">Gender</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th width="80">No. HP</th>
                </tr>
            </thead>
            <tbody>
                @endif
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nip ?? '-' }}</td>
                    <td>{{ $item->nama ?? '-' }}</td>
                    <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $item->jenjang_jabatan ?? '-' }}</td>
                    <td>
                        @if($item->nama_sekolah)
                        {{ $item->nama_sekolah }}
                        @else
                        {{ $item->instansi ?? '-' }}
                        @endif
                    </td>
                    <td>{{ $item->no_hp ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Dicetak oleh Sistem Informasi<br>
            © {{ date('Y') }} - All rights reserved
        </div>

    </div>
    <div style="margin-top:40px; text-align:right; font-size:13px; line-height:1.4;">
        <div>Mengetahui,</div>
        <div style="margin-top:60px; font-weight:600;">Apriana Anggraini, M.Pd.</div>
    </div>
</body>

</html>