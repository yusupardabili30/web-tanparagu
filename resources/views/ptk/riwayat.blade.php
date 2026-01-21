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
            
            /* Style untuk tombol aksi */
            .btn-detail {
                background: linear-gradient(135deg, #10299c 0% );
                border: none;
                border-radius: 8px;
                padding: 8px 16px;
                color: white;
                font-weight: 600;
                font-size: 13px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            .btn-detail:hover {
                transform: translateY(-2px);
                box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
                color: white;
                background: linear-gradient(135deg, #10299c 0%);
            }

            .btn-detail i {
                margin-right: 5px;
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

                                @if($riwayat->count() == 0)
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Belum ada riwayat kegiatan.
                                    </td>
                                </tr>
                                @endif

                                @foreach($riwayat as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="text-start">
                                        {{ $item->kegiatan_name }}
                                    </td>

                                    <td>{{ $item->entity }}</td>

                                    <td>{{ date('d M Y', strtotime($item->start_date)) }}</td>
                                    <td>{{ date('d M Y', strtotime($item->end_date)) }}</td>

                                    <td>
                                        @if($item->status == 'Active')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Selesai</span>
                                        @endif
                                    </td>
  <td>
                                        <a href="{{ route('ptk.detailriwayat', [
                                            'encode_kegiatan_id' => Hashids::encode($item->kegiatan_id),
                                            'nip' => $nip
                                        ]) }}" 
                                           class="btn btn-detail">
                                            <i class="ri-eye-line"></i> Detail
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
            </div>
        </div>


    </div>

    @endsection