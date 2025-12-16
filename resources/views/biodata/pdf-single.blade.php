<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Biodata {{ $data->nama ?? 'Peserta' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 3px solid #4a90e2;
            margin-bottom: 25px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
        }

        .subtitle {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }

        .print-date {
            font-size: 12px;
            color: #999;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            padding: 8px 12px;
            background: #4a90e2;
            color: white;
            border-radius: 5px;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th,
        td {
            border: 1px solid #e6e6e6;
            padding: 10px 12px;
            vertical-align: top;
        }

        th {
            width: 32%;
            background: #f7fbff;
            font-weight: 600;
        }

        tr:nth-child(even) td {
            background: #fafafa;
        }

        /* ===================== */
        /* BAGIAN TANDA TANGAN */
        /* ===================== */
        .signature-section {
            margin-top: 30px;
            text-align: right;
        }

        .signature-box {
            min-height: 70px;
            margin-bottom: 5px;
        }

        .signature-container {
            text-align: right;
        }

        .signature-name {
            font-weight: 600;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 4px;
            margin-top: 10px;
            display: inline-block;
        }

        .signature-title {
            font-size: 12px;
            color: #666;
        }

        .no-signature {
            color: #999;
            font-style: italic;
            padding: 10px;
            border: 1px dashed #ccc;
            display: inline-block;
        }

        .footer {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            font-size: 12px;
        }

        .watermark {
            opacity: 0.1;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 60px;
            color: #ccc;
            z-index: -1;
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark">
        BIODATA PESERTA
    </div>

    <div class="container">
        <div class="header">
            <div class="title">BIODATA PESERTA</div>
            <div class="subtitle">{{ $data->kegiatan_name ?? 'Kegiatan' }}</div>
            <div class="print-date">Dicetak: {{ date('d-m-Y H:i:s') }}</div>
        </div>

        <div class="section">
            <div class="section-title">IDENTITAS PRIBADI</div>
            <table>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>{{ $data->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td>{{ $data->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $data->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $data->jenis_kelamin_formatted ?? ($data->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') }}</td>
                </tr>
                <tr>
                    <th>Tempat/Tgl Lahir</th>
                    <td>{{ $data->tempat_lahir ?? '-' }}, {{ $data->tgl_lahir_formatted ?? (isset($data->tgl_lahir) ? date('d-m-Y', strtotime($data->tgl_lahir)) : '-') }}</td>
                </tr>
                <tr>
                    <th>Agama</th>
                    <td>{{ $data->agama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Pendidikan</th>
                    <td>{{ $data->pendidikan ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">INFORMASI KONTAK</div>
            <table>
                <tr>
                    <th>Email</th>
                    <td>{{ $data->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th>No. HP</th>
                    <td>{{ $data->no_hp ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NPWP</th>
                    <td>{{ $data->npwp ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">JABATAN DAN UNIT KERJA</div>
            <table>
                <tr>
                    <th>Jenjang Jabatan</th>
                    <td>{{ $data->jenjang_jabatan ?? '-' }}</td>
                </tr>
                @if($data->nama_sekolah)
                <tr>
                    <th>Sekolah</th>
                    <td>
                        {{ $data->nama_sekolah ?? '-' }}<br>
                        @if($data->npsn)
                        <span>NPSN: {{ $data->npsn }}</span>
                        @endif
                    </td>
                </tr>
                @else
                <tr>
                    <th>Instansi</th>
                    <td>{{ $data->instansi ?? '-' }}</td>
                </tr>
                @endif
                <tr>
                    <th>Alamat Kantor</th>
                    <td>{{ $data->alamat_kantor ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kota</th>
                    <td>{{ $data->nama_kota ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Provinsi</th>
                    <td>{{ $data->provinsi ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">INFORMASI BANK</div>
            <table>
                <tr>
                    <th>Nama Bank</th>
                    <td>{{ $data->nama_bank ?? '-' }}</td>
                </tr>
                @if($data->kode_bank)
                <tr>
                    <th>Kode Bank</th>
                    <td>{{ $data->kode_bank }}</td>
                </tr>
                @endif
                <tr>
                    <th>No. Rekening</th>
                    <td>{{ $data->no_rekening ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Atas Nama</th>
                    <td>{{ $data->atas_nama_rekening ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">KEGIATAN</div>
            <table>
                <tr>
                    <th>Nama Kegiatan</th>
                    <td>{{ $data->kegiatan_name ?? '-' }}</td>
                </tr>
                @if($data->start_date && $data->end_date)
                <tr>
                    <th>Periode</th>
                    <td>{{ $data->start_date_formatted ?? date('d-m-Y', strtotime($data->start_date)) }} s/d {{ $data->end_date_formatted ?? date('d-m-Y', strtotime($data->end_date)) }}</td>
                </tr>
                @endif
                <tr>
                    <th>Terakhir Update</th>
                    <td>{{ $data->last_update_formatted ?? (isset($data->last_update) ? date('d-m-Y H:i:s', strtotime($data->last_update)) : '-') }}</td>
                </tr>
            </table>
        </div>


        <!-- TTD -->
        <div class="signature-section">
            @php
            $signatureName = $data->nama ?? 'Peserta';
            @endphp

            <div>Peserta Kegiatan,</div>

            <div class="signature-box">
                @if($data->has_signature && !empty($data->ttd_base64))
                <img src="{{ $data->ttd_base64 }}" style="max-width:200px; max-height:70px;">
                @else
                <div class="no-signature">Tanda tangan tidak tersedia</div>
                @endif
            </div>

            <div class="signature-container">
                <div class="signature-name">{{ $signatureName }}</div><br>
                <div class="signature-title">NIP. {{ $data->nip ?? '-' }}</div>
            </div>
        </div>


        <div class="footer">
            Dokumen ini dicetak otomatis oleh sistem<br>
            © {{ date('Y') }} - Sistem TanpaRagu
        </div>
    </div>
</body>

</html>