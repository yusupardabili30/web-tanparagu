@extends('layouts.main-user')
@section('mycontent')

<div class="container-fluid">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

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
                            <li class="breadcrumb-item active">Riwayat</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- CENTER TABLE CSS -->
        <style>
            .table th,
            .table td {
                text-align: center !important;
                vertical-align: middle !important;
            }

            .table td.text-start {
                text-align: left !important;
            }
        </style>



        <!-- =============================== -->
        <!-- CARD RIWAYAT KEGIATAN          -->
        <!-- =============================== -->

        <div class="col-12">

            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <!-- HEADER BADUY -->
                <div class="card-header baduy-bg">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        Riwayat Kegiatan
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Entity</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                // Data dummy riwayat kegiatan
                                $riwayatDummy = [
                                [
                                'nama' => 'Pelatihan Guru Nasional',
                                'token' => 'XX12AB',
                                'entity' => 'DITPSDM',
                                'tgl_mulai' => '12 Jan 2023',
                                'tgl_selesai' => '20 Jan 2023',
                                'status' => 'Selesai'
                                ],
                                [
                                'nama' => 'Workshop Kurikulum Merdeka',
                                'token' => 'KK98PL',
                                'entity' => 'Puskur',
                                'tgl_mulai' => '5 Apr 2022',
                                'tgl_selesai' => '8 Apr 2022',
                                'status' => 'Selesai'
                                ],
                                [
                                'nama' => 'Bimbingan Teknis IT',
                                'token' => 'IT445Q',
                                'entity' => 'Pusdatin',
                                'tgl_mulai' => '1 Sep 2021',
                                'tgl_selesai' => '3 Sep 2021',
                                'status' => 'Selesai'
                                ],
                                [
                                'nama' => $kegiatan->kegiatan_name,
                                'token' => $kegiatan->instrumen_token,
                                'entity' => $kegiatan->entity,
                                'tgl_mulai' => date('d M Y', strtotime($kegiatan->start_date)),
                                'tgl_selesai' => date('d M Y', strtotime($kegiatan->end_date)),
                                'status' => $kegiatan->status == 'Active' ? 'Aktif' : 'Selesai'
                                ]
                                ];
                                @endphp

                                @foreach($riwayatDummy as $index => $riwayat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-start">
                                        {{ $riwayat['nama'] }}<br>
                                        <small>Token: <code>{{ $riwayat['token'] }}</code></small>
                                    </td>
                                    <td>{{ $riwayat['entity'] }}</td>
                                    <td>{{ $riwayat['tgl_mulai'] }}</td>
                                    <td>{{ $riwayat['tgl_selesai'] }}</td>
                                    <td>
                                        @if($riwayat['status'] == 'Aktif')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>


                </div>

            </div>

        </div>



        <!-- =============================== -->
        <!-- CARD LOGOUT / SESSION INFO     -->
        <!-- =============================== -->

        <div class="alert alert-info mt-4 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="ri-user-line fs-4 me-2"></i>
                <div>
                    <h6 class="mb-1">Informasi Sesi</h6>
                    <small>
                        Mengakses riwayat kegiatan sebagai:<br>
                        <b>{{ $ptk->nama }}</b> (NIP: {{ $ptk->nip }})<br>
                        Kegiatan saat ini: <b>{{ $kegiatan->kegiatan_name }}</b>
                    </small>
                </div>
                <div class="ms-auto">
                    <a class="btn btn-outline-danger btn-sm" href="{{ route('lockscreen.logout') }}">
                        Logout
                    </a>
                </div>
            </div>
        </div>


    </div>

    @endsection