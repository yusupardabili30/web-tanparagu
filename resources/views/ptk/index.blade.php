@extends('layouts.main-user')
@section('mycontent')

<div class="container-fluid">

    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <!-- PAGE TITLE -->
    <div class="row mb-1 pt-0" style="margin-top:-50px;">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0" style="font-size:15px; font-weight:400;">
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



    <!-- ========================================== -->
    <!--  PROFIL + DETAIL -->
    <!-- ========================================== -->
    <div class="row mb-1 pt-0">

        <!-- ========================= -->
        <!--  PROFIL -->
        <!-- ========================= -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header baduy-bg">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        Profil PTK
                    </h5>
                </div>

                <div class="card-body p-4 profile-card-body">

                    <!-- Nama -->
                    <h4 class="fw-bold mb-0 mt-3 text-center profile-title">
                        {{ $ptk->nama }}
                    </h4>

                    <!-- Jabatan -->
                    <h5 class="text-muted d-block mb-3 text-center profile-role">
                        @if($ptk->pangkatJabatan)
                        {{ $ptk->pangkatJabatan->jenjang_jabatan }}
                        @if($ptk->pangkatJabatan->pangkat)
                        - {{ $ptk->pangkatJabatan->pangkat }}
                        @endif
                        @if($ptk->pangkatJabatan->golongan_ruang)
                        ({{ $ptk->pangkatJabatan->golongan_ruang }})
                        @endif
                        @endif
                    </h5>

                    <!-- Badge -->
                    <div class="d-flex justify-content-center gap-2 mb-4 profile-badges">

                        <div class="px-3 py-2"
                            style="background:#eef2ff; border-radius:10px; display:flex; align-items:center;">
                            <i class="ri-user-line me-1" style="font-size:18px;"></i>
                            <span>{{ $ptk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>

                        <div class="px-3 py-2"
                            style="background:#d6f5ef; border-radius:10px; display:flex; align-items:center;">
                            <i class="ri-calendar-event-line me-1" style="font-size:18px;"></i>
                            <span>{{ $ptk->tgl_lahir_formatted }}</span>
                        </div>

                    </div>

                    <!-- Tabel Profil -->
                    <table class="table table-borderless mb-0 text-start profile-table">
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
                            @if($ptk->sekolah)
                            <tr>
                                <td class="fw-semibold">Sekolah</td>
                                <td>:
                                    {{ $ptk->sekolah->nama_sekolah }}
                                    @if($ptk->sekolah->npsn)
                                    ({{ $ptk->sekolah->npsn }})
                                    @endif
                                </td>
                            </tr>
                            @endif

                            @if($ptk->instansi && !$ptk->sekolah)
                            <tr>
                                <td class="fw-semibold">Instansi</td>
                                <td>: {{ $ptk->instansi }}</td>
                            </tr>
                            @elseif($ptk->instansi && $ptk->sekolah)
                            <tr>
                                <td class="fw-semibold">Instansi Lain</td>
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
                            class="btn btn-primary btn-lg" style="border-radius:10px;">
                            <i class="ri-book-open-line me-1"></i> Lihat Riwayat Kegiatan
                        </a>
                    </div>

                </div>
            </div>
        </div>


        <!-- ========================= -->
        <!--  DETAIL KEGIATAN -->
        <!-- ========================= -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header baduy-bg">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        Detail Kegiatan
                    </h5>
                </div>

                <div class="card-body p-4 detail-card-body">

                    <table class="table table-borderless text-start detail-table">
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
                                <div class="card-body text-center detail-stat-box">
                                    <h6 class="card-title">Durasi</h6>
                                    <h3 class="text-primary">{{ $duration }}</h3>
                                    <small>Hari</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center detail-stat-box">
                                    <h6 class="card-title">Sisa Waktu</h6>
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
                            class="btn btn-primary btn-lg px-5" style="border-radius:10px;">
                            <i class="ri-play-line me-2"></i> Mulai Quiz
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>



    <!-- SESSION INFO -->
    <div class="alert alert-info mt-3 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="ri-user-line fs-4 me-2"></i>
            <div>
                <h6 class="mb-1">Informasi Sesi</h6>
                <small>
                    Anda sedang mengakses kegiatan sebagai: <b>{{ $ptk->nama }}</b> (NIP: {{ $ptk->nip }})<br>
                    Kegiatan: <b>{{ $kegiatan->kegiatan_name }}</b> |
                    Token: <code>{{ $kegiatan->instrumen_token }}</code><br>
                    Instansi: <b>{{ $ptk->sekolah ? $ptk->sekolah->nama_sekolah : $ptk->instansi }}</b>
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

<!-- Edit Modal (Opsional) -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data PTK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ $ptk->nama }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $ptk->email }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ $ptk->no_hp }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat Rumah</label>
                            <textarea name="alamat_rumah" class="form-control" rows="2">{{ $ptk->alamat_rumah }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota</label>
                            <select name="kota_id" class="form-control">
                                <option value="">Pilih Kota</option>
                                @foreach($kotas as $kota)
                                <option value="{{ $kota->kota_id }}" {{ $ptk->kota_id == $kota->kota_id ? 'selected' : '' }}>
                                    {{ $kota->nama_kota }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sekolah/Instansi</label>
                            <select name="sekolah_id" class="form-control">
                                <option value="">Pilih Sekolah</option>
                                @foreach($sekolahs as $sekolah)
                                <option value="{{ $sekolah->sekolah_id }}" {{ $ptk->sekolah_id == $sekolah->sekolah_id ? 'selected' : '' }}>
                                    {{ $sekolah->nama_sekolah }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Jika tidak ada, isi manual di bawah</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Instansi Manual</label>
                            <input type="text" name="instansi" class="form-control" value="{{ $ptk->instansi }}"
                                placeholder="Nama instansi/lembaga (jika sekolah tidak ada dalam daftar)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pangkat/Jabatan</label>
                            <select name="pangkat_jabatan_id" class="form-control">
                                <option value="">Pilih Pangkat/Jabatan</option>
                                @foreach($pangkatJabatans as $pangkatJabatan)
                                <option value="{{ $pangkatJabatan->pangkat_jabatan_id }}" {{ $ptk->pangkat_jabatan_id == $pangkatJabatan->pangkat_jabatan_id ? 'selected' : '' }}>
                                    {{ $pangkatJabatan->jenjang_jabatan }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Edit modal functionality
    document.getElementById('saveEdit')?.addEventListener('click', function() {
        const form = document.getElementById('editForm');
        const formData = new FormData(form);

        fetch(`/ptk/update/{{ $current_encode_kegiatan_id }}/{{ $current_nip }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil diperbarui',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2c7be5',
                        willClose: () => {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan saat memperbarui data',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#dc3545'
                });
            });
    });
</script>




<!-- ========================================== -->
<!--   RESPONSIVE FIX TOTAL – NO CENTER MOBILE -->
<!-- ========================================== -->
<style>
    /* ===================================== */
    /*    PROFIL PTK — MOBILE FIX            */
    /* ===================================== */
    @media (max-width: 768px) {

        .profile-card-body {
            text-align: left !important;
        }

        .profile-title,
        .profile-role {
            text-align: left !important;
            margin-left: 0 !important;
        }

        .profile-badges {
            justify-content: flex-start !important;
        }

        .profile-table {
            width: 100% !important;
            margin: 0 !important;
        }

        .profile-table td {
            text-align: left !important;
            padding-left: 0 !important;
        }

        .profile-table td:first-child {
            width: 150px !important;
        }
    }


    /* ===================================== */
    /*   DETAIL KEGIATAN — MOBILE FIX        */
    /* ===================================== */
    @media (max-width: 768px) {

        .detail-card-body {
            text-align: left !important;
        }

        .detail-table {
            width: 100% !important;
            margin: 0 !important;
        }

        .detail-table td {
            text-align: left !important;
            padding-left: 0 !important;
        }

        .detail-table td:first-child {
            width: 150px !important;
        }

        .detail-stat-box {
            text-align: center !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }
</style>


@endsection