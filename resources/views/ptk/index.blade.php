@extends('layouts.main-user')
@section('mycontent')
<link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
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
                            <a href="javascript:history.back();" class="text-primary fw-bold">
                                <i class="ri-arrow-left-line me-1"></i> Detail
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Kegiatan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- PROFIL + INFO KEGIATAN -->
    <div class="row mb-1 pt-0">
        <!-- PROFIL DI KIRI -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header baduy-bg">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        Profil PTK
                    </h5>
                </div>

                <div class="card-body p-4 text-center">

                    <h4 class="fw-bold mb-0 mt-3">{{ $ptk->nama }}</h4>
                    <h5 class="text-muted d-block mb-3">{{ $ptk->jabatan }}</h5>

                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <div class="px-3 py-2"
                            style="background:#eef2ff; border-radius:10px; display:flex; align-items:center;">
                            <i class="ri-user-line me-1" style="font-size:18px;"></i>
                            <span>{{ $ptk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>

                        <div class="px-3 py-2"
                            style="background:#d6f5ef; border-radius:10px; display:flex; align-items:center;">
                            <i class="ri-calendar-event-line me-1" style="font-size:18px;"></i>
                            <span>{{ date('d/m/Y', strtotime($ptk->tgl_lahir)) }}</span>
                        </div>
                    </div>

                    <table class="table table-borderless mb-0 text-start">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" width="160">NIP</td>
                                <td>: {{ $ptk->nip }}</td>
                            </tr>
                            @if($ptk->nik)
                            <tr>
                                <td class="fw-semibold">NIK</td>
                                <td>: {{ $ptk->nik }}</td>
                            </tr>
                            @endif
                            @if($ptk->nuptk)
                            <tr>
                                <td class="fw-semibold">NUPTK</td>
                                <td>: {{ $ptk->nuptk }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="fw-semibold">Email</td>
                                <td>: {{ $ptk->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">No. HP</td>
                                <td>: {{ $ptk->no_hp }}</td>
                            </tr>
                            @if($ptk->npwp)
                            <tr>
                                <td class="fw-semibold">NPWP</td>
                                <td>: {{ $ptk->npwp }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="fw-semibold">Tempat Lahir</td>
                                <td>: {{ $ptk->tempat_lahir }}</td>
                            </tr>
                            @if($ptk->agama)
                            <tr>
                                <td class="fw-semibold">Agama</td>
                                <td>: {{ $ptk->agama }}</td>
                            </tr>
                            @endif
                            @if($ptk->pendidikan)
                            <tr>
                                <td class="fw-semibold">Pendidikan</td>
                                <td>: {{ $ptk->pendidikan }}</td>
                            </tr>
                            @endif
                            @if($ptk->instansi)
                            <tr>
                                <td class="fw-semibold">Instansi</td>
                                <td>: {{ $ptk->instansi }}</td>
                            </tr>
                            @endif
                            @if($ptk->alamat_rumah)
                            <tr>
                                <td class="fw-semibold">Alamat</td>
                                <td>: {{ $ptk->alamat_rumah }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <!-- BUTTON LIHAT RIWAYAT -->
                    <div class="text-end mt-4">
                        <a href="{{ route('ptk.riwayat', [
        'encode_kegiatan_id' => $current_encode_kegiatan_id,
        'nip' => $current_nip
    ]) }}"
                            class="btn btn-outline-primary btn-lg">
                            <i class="ri-book-open-line me-1"></i> Lihat Riwayat Kegiatan
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- INFO KEGIATAN DI KANAN -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header baduy-bg">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">Detail Kegiatan</h5>
                </div>

                <div class="card-body p-4">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" width="160">Nama Kegiatan</td>
                                <td>: {{ $kegiatan->kegiatan_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Entity</td>
                                <td>: {{ $kegiatan->entity }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Mulai</td>
                                <td>: {{ $start_date }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Selesai</td>
                                <td>: {{ $end_date }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Token Kegiatan</td>
                                <td>: <code>{{ $kegiatan->instrumen_token }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status</td>
                                <td>:
                                    @if($kegiatan->status == 'Active')
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Durasi</h6>
                                    @php
                                    $start = \Carbon\Carbon::parse($kegiatan->start_date);
                                    $end = \Carbon\Carbon::parse($kegiatan->end_date);
                                    $duration = $start->diffInDays($end) + 1;
                                    @endphp
                                    <h3 class="text-primary">{{ $duration }}</h3>
                                    <small>Hari</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Sisa Waktu</h6>
                                    @php
                                    $now = \Carbon\Carbon::now();
                                    $remaining = $now->diffInDays($end, false);
                                    @endphp
                                    @if($remaining > 0)
                                    <h3 class="text-success">{{ $remaining }}</h3>
                                    <small>Hari lagi</small>
                                    @elseif($remaining == 0)
                                    <h3 class="text-warning">0</h3>
                                    <small>Berakhir hari ini</small>
                                    @else
                                    <h3 class="text-danger">0</h3>
                                    <small>Sudah berakhir</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        @php
                        $encoded_sub_indikator_id = Hashids::encode(1);
                        $encoded_no_urut = Hashids::encode(1);
                        @endphp

                        <a href="{{ route('quiz.show', [
                            'encoded_kegiatan_id' => $current_encode_kegiatan_id,
                            'nip' => $current_nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => $encoded_no_urut
                        ]) }}"
                            class="btn btn-primary btn-lg px-5">
                            <i class="ri-play-line me-2"></i> Mulai Quiz
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- INFO SESSION -->
    <div class="alert alert-info mt-3 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="ri-user-line fs-4 me-2"></i>
            <div>
                <h6 class="mb-1">Informasi Sesi</h6>
                <small>
                    Anda sedang mengakses kegiatan sebagai: <b>{{ $ptk->nama }}</b> (NIP: {{ $ptk->nip }})<br>
                    Kegiatan: <b>{{ $kegiatan->kegiatan_name }}</b> |
                    Token: <code>{{ $kegiatan->instrumen_token }}</code>
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