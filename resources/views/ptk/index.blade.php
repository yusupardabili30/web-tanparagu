@extends('layouts.main-user')
@section('mycontent')

<div class="container-fluid">
    <!-- Jika belum ada Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <!--  PROFIL (LEBIH KECIL) -->
        <!-- ========================= -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-header baduy-bg py-2">
                    <h5 class="mb-0 text-white" style="font-size:18px; font-weight:700;">
                        <i class="fa-solid fa-address-card"></i> Profil PTK
                    </h5>
                </div>

                <div class="card-body p-3 profile-card-body">

                    <!-- Nama -->
                    <h4 class="fw-bold mb-0 mt-2 text-center profile-title" style="font-size:20px;">
                        {{ $ptk->nama }}
                    </h4>

                    <!-- Jabatan -->
                    <h5 class="text-muted d-block mb-3 text-center profile-role" style="font-size:14px;">
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
                    <div class="d-flex justify-content-center gap-2 mb-3 profile-badges">
                        <div class="px-2 py-1" style="background:#eef2ff; border-radius:8px; display:flex; align-items:center;">
                            <i class="ri-user-line me-1" style="font-size:14px;"></i>
                            <span style="font-size:13px;">{{ $ptk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="px-2 py-1" style="background:#d6f5ef; border-radius:8px; display:flex; align-items:center;">
                            <i class="ri-calendar-event-line me-1" style="font-size:14px;"></i>
                            <span style="font-size:13px;">{{ $ptk->tgl_lahir_formatted }}</span>
                        </div>
                    </div>

                    <!-- Tabel Profil -->
                    <table class="table table-borderless mb-0 text-start profile-table" style="font-size:13px;">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" width="110">NIP</td>
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
                                <td>: {{ $ptk->sekolah->nama_sekolah }}
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
                    <div class="mt-3">
                        <a href="{{ route('ptk.riwayat', [
                        'encode_kegiatan_id' => $current_encode_kegiatan_id,
                        'nip' => $current_nip
                    ]) }}"
                            class="btn btn-primary btn-sm w-100 d-block text-center"
                            style="border-radius:8px; font-size:14px; padding:8px;">
                            <i class="ri-book-open-line me-1"></i> Lihat Riwayat Kegiatan
                        </a>
                    </div>

                </div>
            </div>
        </div>


        <!-- ========================= -->
        <!--  DETAIL KEGIATAN (LEBIH BESAR) -->
        <!-- ========================= -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm" style="border-radius:14px; height:100%;">

                <div class="card-header baduy-bg py-2">
                    <div class="text-white">
                        {{-- Nama Kegiatan (lebih besar) --}}
                        <div class="d-flex align-items-center mb-1" style="font-size: 18px; font-weight: 700;">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ $kegiatan->kegiatan_name }}
                        </div>

                        {{-- Entity (lebih kecil, di bawahnya) --}}
                        <div class="d-flex align-items-center text-white" style="font-size: 14px; font-weight: 500;">
                            <i class="fa-solid fa-user me-2"></i>
                            {{ $kegiatan->entity }}
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 detail-card-body">

                    <!-- PEDOMAN APLIKASI -->
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary mb-2" style="font-size:16px;">
                            <i class="fas fa-book me-2"></i>Pedoman Aplikasi
                        </h6>
                        <div class="ps-3">
                            <ol class="mb-0" style="line-height: 1.7; font-size:14px;">
                                <li>Jawablah semua pertanyaan dengan <strong>jujur dan sesuai kondisi sebenarnya</strong></li>
                                <li>Setiap pertanyaan hanya memiliki <strong>satu jawaban yang paling tepat</strong></li>
                                <li>Durasi pengerjaan adalah <strong>2 jam</strong> sejak dimulai</li>
                                <li>Pastikan koneksi internet stabil selama mengerjakan</li>
                                <li>Hasil ini akan digunakan untuk pemetaan kebutuhan belajar</li>
                            </ol>
                        </div>
                    </div>

                    <!-- TUJUAN APLIKASI -->
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary mb-2" style="font-size:16px;">
                            <i class="fas fa-bullseye me-2"></i>Tujuan Aplikasi
                        </h6>
                        <div class="ps-3">
                            <ul class="mb-0" style="line-height: 1.7; font-size:14px;">
                                <li>Memetakan <strong>tingkat kompetensi</strong> Kepala Sekolah</li>
                                <li>Mengidentifikasi <strong>kebutuhan pengembangan profesional</strong></li>
                                <li>Memberikan <strong>rekomendasi program penguatan kapasitas</strong></li>
                                <li>Mendukung <strong>perencanaan pengembangan karir</strong> yang terarah</li>
                                <li>Sebagai <strong>dasar evaluasi</strong> kinerja dan potensi</li>
                            </ul>
                        </div>
                    </div>

                    <!-- DURASI PENGERJAAN -->
                    <div class="mb-3 px-3 py-2" style="background:#f4f6ff; border-radius:10px; border:1px solid #e0e6ff;">
                        <div class="d-flex align-items-center">
                            <i class="ri-timer-line me-3" style="font-size:30px; color:#1a4cbc;"></i>
                            <div>
                                <div class="fw-semibold" style="font-size:16px;">Durasi Pengerjaan</div>
                                <div class="text-primary fw-bold" style="font-size:16px; margin-top:-2px;">
                                    2 Jam (120 Menit)
                                </div>
                                <small class="text-muted" style="font-size:13px;">Waktu dimulai saat Anda klik tombol "Mulai"</small>
                            </div>
                        </div>
                    </div>

                    <!-- CHECKBOX KONFIRMASI -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="konfirmasiCheckbox"
                                style="width: 18px; height: 18px; cursor: pointer;">
                            <label class="form-check-label ms-2" for="konfirmasiCheckbox"
                                style="cursor: pointer; font-size: 14px; line-height: 1.4;">
                                <strong>Saya telah membaca dan memahami pedoman di atas.</strong><br>
                                <small class="text-muted">Dengan mencentang ini, saya menyatakan siap mengikuti
                                    kegiatan ini dengan penuh tanggung jawab.</small>
                            </label>
                        </div>
                    </div>

                    <!-- TOMBOL MULAI (DINONAKTIFKAN AWALNYA) -->
                    <div class="text-center mt-4">
                        @php
                        $encoded_no_urut = Hashids::encode(1);
                        @endphp

                        @switch($kegiatan->tahap)
                        @case(1)
                        @php
                        $encoded_indikator_id = Hashids::encode($data->indikator_id);
                        $encoded_no_urut = Hashids::encode(1);
                        @endphp
                        <a href="{{ route('quiz1.show', [
                            'tahap' => $kegiatan->tahap,
                            'encoded_kegiatan_id' => $current_encode_kegiatan_id,
                            'nip' => $current_nip,
                            'encoded_indikator_id' => $encoded_indikator_id,
                            'encoded_no_urut' => $encoded_no_urut
                        ]) }}"
                            id="btnMulai"
                            class="btn btn-primary btn-lg px-5 disabled"
                            style="border-radius:10px; opacity: 0.6; pointer-events: none; font-size:16px; padding:10px 30px;">
                            <i class="ri-play-line me-2"></i> Mulai
                        </a>
                        @break

                        @case(2)
                        @php
                        //$encoded_sub_indikator_id = Hashids::encode(1);
                        $encoded_no_urut = Hashids::encode(1);
                        @endphp
                        <a href="{{ route('quiz2.show', [
                            'tahap' => $kegiatan->tahap,
                            'encoded_kegiatan_id' => $current_encode_kegiatan_id,
                            'nip' => $current_nip,
                            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
                            'encoded_no_urut' => $encoded_no_urut
                        ]) }}"
                            id="btnMulai"
                            class="btn btn-primary btn-lg px-5 w-100 d-block text-center disabled"
                            style="border-radius:10px; opacity: 0.6; pointer-events: none; font-size:16px; padding:10px;">
                            <i class="ri-play-line me-2"></i> Mulai
                        </a>
                        @break

                        @default
                        @endswitch
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

<style>
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

    /* Styling untuk checkbox area */
    .form-check-input:checked {
        background-color: #2c7be5;
        border-color: #2c7be5;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(44, 123, 229, 0.25);
        border-color: #2c7be5;
    }

    /* Smooth transition untuk tombol */
    #btnMulai {
        transition: all 0.3s ease;
    }

    #btnMulai:not(.disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 123, 229, 0.3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('konfirmasiCheckbox');
        const btnMulai = document.getElementById('btnMulai');

        if (checkbox && btnMulai) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    btnMulai.classList.remove('disabled');
                    btnMulai.style.opacity = '1';
                    btnMulai.style.pointerEvents = 'auto';
                } else {
                    btnMulai.classList.add('disabled');
                    btnMulai.style.opacity = '0.6';
                    btnMulai.style.pointerEvents = 'none';
                }
            });
        }
    });
</script>

@endsection