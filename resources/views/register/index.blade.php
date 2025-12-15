@extends('layouts.main-user')

@section('mycontent')

<style>
    /* ============================================
    WARNA BIRU BADUY FORM
    ============================================ */
    .form-control::placeholder,
    .form-select::placeholder,
    textarea::placeholder {
        opacity: 1 !important;
        font-weight: 500 !important;
    }

    .form-control,
    textarea.form-control,
    .form-select {
        font-weight: 600 !important;
    }

    .form-select option {
        color: #1a3f6b !important;
    }

    /* ============================================
    FLOATING LABEL FIX
    ============================================ */
    .form-control-lg,
    .form-select-lg {
        font-size: 15px !important;
        padding-top: 18px !important;
        padding-bottom: 18px !important;
        height: 60px !important;
    }

    .form-select option[value=""] {
        color: #555555 !important;
    }

    .form-floating>label {
        font-size: 15px !important;
        font-weight: 600 !important;
        color: #555555 !important;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.form-select:focus~label,
    .form-floating>.form-select:not([value=""])~label {
        font-size: 14px !important;
        transform: translateY(-14px) scale(.9) !important;
        opacity: .85;
        color: #555555 !important;
    }

    /* ============================================
    BOX & FORM SPACING
    ============================================ */
    .big-box {
        background: #f6f8ff;
        border: 1px solid #d7e2ff;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 12px;
    }

    .mb-3 {
        margin-bottom: 10px !important;
    }

    /* ============================================
    HIDE HEADER DEFAULT
    ============================================ */
    .navbar,
    .topbar,
    footer,
    #header:not(.card-header),
    header:not(.card-header) {
        display: none !important;
    }

    .content-wrapper {
        padding-top: 5px !important;
    }

    /* ============================================
    LITEPICKER
    ============================================ */
    .litepicker {
        border-radius: 18px !important;
        padding: 20px !important;
        border: 1px solid #d4ddff !important;
        background: #ffffff !important;
        box-shadow: 0 12px 30px rgba(0, 40, 120, 0.12) !important;
    }

    /* ============================================
    RESPONSIVE MOBILE HP
    ============================================ */
    @media (max-width: 576px) {

        /* HIDE LOGO DI HP */
        .header-logo-container img {
            display: none !important;
        }

        /* HEADER LOGO + TITLE */
        .header-logo-container {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 5px !important;
            margin-top: -20px !important;
        }

        .header-logo-container img {
            width: 130px !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
        }

        .header-info {
            margin-top: -5px !important;
            margin-left: 0 !important;
        }

        .header-info h4 {
            font-size: 20px !important;
        }

        .header-info div {
            font-size: 14px !important;
        }

        .card {
            margin-top: 10px !important;
        }

        .form-control-lg,
        .form-select-lg {
            height: 55px !important;
            font-size: 15px !important;
        }

        .form-floating>label {
            font-size: 14px !important;
        }

        .big-box {
            padding: 15px !important;
            margin-bottom: 10px !important;
        }

        /* GRID AUTO STACK */
        .col-md-6,
        .col-md-4 {
            width: 100% !important;
            max-width: 100% !important;
        }

        .btn-lg {
            width: 100% !important;
            font-size: 18px !important;
        }
    }

    /* Styling untuk search sekolah button */
    .search-school-btn {
        height: 58px;
        border-radius: 0 8px 8px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
    }


    /* ============================================
   STYLING BUTTON CARI NIP (SAMA DENGAN CARI SEKOLAH)
============================================ */
    .search-nip-btn {
        height: 58px !important;
        border-radius: 0 8px 8px 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 60px !important;
        padding: 0 !important;
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
        transition: all 0.2s ease !important;
    }

    .search-nip-btn:hover {
        background-color: #0b5ed7 !important;
        border-color: #0a58ca !important;
        transform: translateY(-1px);
    }

    .search-nip-btn:active {
        transform: translateY(0);
    }

    .search-nip-btn i {
        font-size: 20px !important;
    }

    /* Styling untuk search sekolah button */
    .search-school-btn {
        height: 58px !important;
        border-radius: 0 8px 8px 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 60px !important;
        padding: 0 !important;
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
        transition: all 0.2s ease !important;
    }

    .search-school-btn:hover {
        background-color: #0b5ed7 !important;
        border-color: #0a58ca !important;
        transform: translateY(-1px);
    }

    .search-school-btn:active {
        transform: translateY(0);
    }

    .search-school-btn i {
        font-size: 20px !important;
    }

    /* Responsive untuk HP */
    @media (max-width: 576px) {

        .search-nip-btn,
        .search-school-btn {
            height: 55px !important;
            width: 50px !important;
        }

        .search-nip-btn i,
        .search-school-btn i {
            font-size: 18px !important;
        }
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid">
        <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

        <!-- ========================================================= -->
        <!-- HEADER: LOGO + JUDUL + TANGGAL + LOKASI -->
        <!-- ========================================================= -->
        <div class="d-flex align-items-center mb-3 header-logo-container" style="gap:15px; margin-top:-10px;">

            <!-- LOGO -->
            <img src="{{ asset('build/images/logotutwuri.png') }}"
                alt="Tut Wuri Handayani"
                style="width:180px; height:auto; margin-left:-30px; margin-top:-100px;">

            <!-- TEXT -->
            <div class="header-info" style="line-height:1.3; margin-top:-100px;">
                <h4 class="fw-bold mb-1" style="color:#1a3f6b; font-size:26px;">
                    {{ $kegiatan->kegiatan_name }}
                </h4>

                <div class="text-muted" style="font-size:16px;">
                    <div><i class="ri-calendar-line me-1"></i> @php
                        $start_date = Carbon\Carbon::parse($kegiatan->start_date);
                        $end_date = Carbon\Carbon::parse($kegiatan->end_date);

                        if ($start_date->format('Y-m') === $end_date->format('Y-m')) {
                        echo $start_date->format('d') . ' – ' . $end_date->format('d F Y');
                        } else {
                        echo $start_date->format('d F') . ' – ' . $end_date->format('d F Y');
                        }
                        @endphp</div>
                    <div><i class="ri-map-pin-line me-1"></i> Kota Serang, Provinsi Banten</div>
                </div>
            </div>
        </div>


        <!-- ========================================================= -->
        <!-- FORM CARD -->
        <!-- ========================================================= -->
        <div class="row" style="margin-top:15px;">
            <div class="col-xl-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-header baduy-bg">
                        <h5 class="mb-0 text-white fw-bold" style="font-size:22px;">
                            <i class="ri-profile-line me-2"></i> Biodata Peserta
                        </h5>
                    </div>

                    <div class="card-body">

                        <!-- BOX 1 -->
                        <div class="big-box mb-3">
                            <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                                <i class="ri-information-line me-1 text-primary"></i> Informasi Peserta
                            </h5>

                            <form id="profilForm" action="{{ route('register.store', $encode_kegiatan_id) }}" method="POST">
                                @csrf

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- ===================== ROW 1 ===================== -->
                                <!-- <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <div class="input-group">
                                                <input name="nip" id="nip" required
                                                    class="form-control form-control-lg"
                                                    placeholder=" "
                                                    value="{{ old('nip') }}">

                                                <button class="btn btn-primary search-nip-btn" type="button" id="btnCariNip">
                                                    <i class="ri-search-line"></i>
                                                </button>
                                            </div>
                                            <label>NIP / NI PPPK</label>
                                            @error('nip')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            {{-- tempat pesan validasi NIP --}}
                                            <div id="nipValidationMsg" class="small mt-1"></div>
                                        </div>
                                    </div> -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <!-- Gunakan div dengan kelas form-floating DI DALAM input-group -->
                                            <div class="input-group" style="position: relative;">
                                                <div class="form-floating flex-grow-1">
                                                    <input name="nip" id="nip" required
                                                        class="form-control form-control-lg"
                                                        placeholder=" "
                                                        value="{{ old('nip') }}">
                                                    <label>NIP / NI PPPK</label>
                                                </div>
                                                <button class="btn btn-primary search-nip-btn" type="button" id="btnCariNip" style="height: 58px; border-radius: 0 8px 8px 0;">
                                                    <i class="ri-search-line"></i>
                                                </button>
                                            </div>
                                            @error('nip')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div id="nipValidationMsg" class="small mt-1"></div>
                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <input name="nik" required class="form-control form-control-lg" placeholder=" " value="{{ old('nik') }}">
                                            <label>NIK</label>
                                            @error('nik')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== ROW 2 ===================== -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <input name="nama" required class="form-control form-control-lg" placeholder=" " value="{{ old('nama') }}">
                                            <label>Nama Lengkap</label>
                                            @error('nama')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <select name="pangkat_jabatan_id" class="form-select" required style="font-size:16px; color:#555555 !important;">
                                                <option value="">Pilih Jabatan</option>
                                                @foreach($pangkatJabatans as $jabatan)
                                                <option value="{{ $jabatan->pangkat_jabatan_id }}" {{ old('pangkat_jabatan_id') == $jabatan->pangkat_jabatan_id ? 'selected' : '' }}>
                                                    {{ $jabatan->jenjang_jabatan }}
                                                    <!-- - {{ $jabatan->pangkat }} -->
                                                </option>
                                                @endforeach
                                            </select>
                                            <label>Jabatan</label>
                                            @error('pangkat_jabatan_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== AGAMA + JK ===================== -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <select name="agama" class="form-select" required style="font-size:16px; color:#555555 !important;">
                                                <option value="">Pilih Agama</option>
                                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                            </select>
                                            <label>Agama</label>
                                            @error('agama')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <select name="jenis_kelamin" class="form-select" required style="font-size:16px; color:#555555 !important;">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                            <label>Jenis Kelamin</label>
                                            @error('jenis_kelamin')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== TTL ===================== -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <input name="tempat_lahir" required class="form-control form-control-lg" placeholder=" " value="{{ old('tempat_lahir') }}">
                                            <label>Tempat Lahir</label>
                                            @error('tempat_lahir')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <input id="tgl_lahir" name="tgl_lahir" required class="form-control form-control-lg" placeholder=" " value="{{ old('tgl_lahir') }}">
                                            <label>Tanggal Lahir</label>
                                            @error('tgl_lahir')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== PENDIDIKAN ===================== -->
                                <div class="form-floating mb-2">
                                    <input name="pendidikan" required class="form-control form-control-lg" placeholder=" " value="{{ old('pendidikan') }}">
                                    <label>Pendidikan Terakhir</label>
                                    @error('pendidikan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- ===================== UNIT KERJA ===================== -->
                                <div class="big-box mt-3">
                                    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                                        <i class="ri-school-line me-1 text-primary"></i> Informasi Unit Kerja
                                    </h5>

                                    <!-- Radio button untuk memilih sekolah atau unit kerja -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="unit_kerja_option" id="optionSekolah" value="sekolah" {{ old('unit_kerja_option', 'sekolah') == 'sekolah' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="optionSekolah">Sekolah</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="unit_kerja_option" id="optionUnitKerja" value="unit_kerja" {{ old('unit_kerja_option') == 'unit_kerja' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="optionUnitKerja">Unit Kerja Lainnya</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dropdown Sekolah (Awal ditampilkan) -->
                                    <div id="sekolahSection" class="mb-2 {{ old('unit_kerja_option', 'sekolah') == 'sekolah' ? '' : 'd-none' }}">
                                        <div class="form-floating mb-2">
                                            <div class="input-group">
                                                <input type="text"
                                                    id="sekolah_search"
                                                    class="form-control form-control-lg"
                                                    placeholder="Cari Sekolah (Ketik nama sekolah/NPSN)"
                                                    autocomplete="off"
                                                    value="{{ old('sekolah_search') }}">
                                                <input type="hidden" name="sekolah_id" id="sekolah_id" value="{{ old('sekolah_id') }}">
                                                <button class="btn btn-primary search-school-btn" type="button" id="btnSearchSekolah">
                                                    <i class="ri-search-line"></i>
                                                </button>
                                            </div>

                                            <small class="text-muted d-block mt-1">
                                                Opsional: Jika memilih sekolah, tidak perlu mengisi instansi.
                                            </small>
                                        </div>

                                        <!-- Info sekolah yang dipilih -->
                                        <div id="sekolahInfo" class="p-3 bg-light rounded mt-2 {{ old('sekolah_id') ? '' : 'd-none' }}">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1" id="selectedSekolahName">
                                                        {{ old('selected_sekolah_name', '') }}
                                                    </h6>
                                                    <p class="mb-1 text-muted small" id="selectedSekolahNPSN">
                                                        {{ old('selected_sekolah_npsn', '') }}
                                                    </p>
                                                    <p class="mb-0 text-muted small" id="selectedSekolahAlamat">
                                                        {{ old('selected_sekolah_alamat', '') }}
                                                    </p>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnRemoveSekolah">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Input Unit Kerja (Awal disembunyikan) -->
                                    <div id="unitKerjaSection" class="mb-2 {{ old('unit_kerja_option') == 'unit_kerja' ? '' : 'd-none' }}">
                                        <div class="form-floating mb-2">
                                            <input type="text"
                                                name="instansi"
                                                id="instansi"
                                                class="form-control form-control-lg"
                                                placeholder=" "
                                                value="{{ old('instansi') }}">
                                            <label>Nama Instansi / Unit Kerja</label>
                                            <small class="text-muted">Isi nama instansi atau unit kerja tempat bertugas</small>
                                        </div>
                                    </div>

                                    <!-- Alamat Kantor (Sama untuk kedua opsi) -->
                                    <div class="form-floating mb-2">
                                        <textarea name="alamat_kantor" class="form-control form-control-lg" placeholder=" " style="height:110px;">{{ old('alamat_kantor') }}</textarea>
                                        <label>Alamat Unit Kerja</label>
                                        @error('alamat_kantor')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-2">
                                                <select name="kota_id" id="kota_id" class="form-select" required>
                                                    <option value="">Pilih Kabupaten/Kota</option>
                                                    @foreach($kotas as $kota)
                                                    <option value="{{ $kota->kota_id }}" {{ old('kota_id') == $kota->kota_id ? 'selected' : '' }}>
                                                        {{ $kota->nama_kota }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label>Kabupaten/Kota</label>
                                                @error('kota_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-2">
                                                <input name="provinsi" class="form-control form-control-lg" placeholder=" " value="Banten" readonly>
                                                <label>Provinsi</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== KONTAK ===================== -->
                                <div class="big-box mt-3">
                                    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                                        <i class="ri-contacts-book-line me-1 text-primary"></i> Kontak & Informasi Tambahan
                                    </h5>

                                    <div class="row mb-2">


                                        <div class="col-md-14">
                                            <div class="form-floating mb-2">
                                                <input name="no_hp" required class="form-control form-control-lg" placeholder=" " value="{{ old('no_hp') }}">
                                                <label>No. Telp / HP</label>
                                                @error('no_hp')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-2">
                                        <input type="email" name="email" required class="form-control form-control-lg" placeholder=" " value="{{ old('email') }}">
                                        <label>Email</label>
                                        @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-2">
                                        <input name="npwp" class="form-control form-control-lg" placeholder=" " value="{{ old('npwp') }}">
                                        <label>NPWP</label>
                                        @error('npwp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <!-- ===================== BANK ===================== -->

                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-2">
                                                <select name="ms_bank_id" id="ms_bank_id" class="form-select form-select-lg" required style="color: #555555">
                                                    <option value="">Pilih Bank</option>
                                                    @foreach($banks as $bank)
                                                    <option value="{{ $bank->ms_bank_id }}"
                                                        {{ old('ms_bank_id') == $bank->ms_bank_id ? 'selected' : '' }}
                                                        data-nama-bank="{{ $bank->nama_bank ?? '' }}">
                                                        {{ $bank->nama_bank }}
                                                        @if($bank->kode_bank)
                                                        ({{ $bank->kode_bank }})
                                                        @endif
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label>Nama Bank</label>
                                                @error('ms_bank_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-2">
                                                <input name="no_rekening" required class="form-control form-control-lg" placeholder="" value="{{ old('no_rekening') }}">
                                                <label>No. Rekening</label>
                                                @error('no_rekening')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-2">
                                                <input name="atas_nama_rekening" required class="form-control form-control-lg" placeholder="" value="{{ old('atas_nama_rekening') }}">
                                                <label>Atas Nama Rekening</label>
                                                @error('atas_nama_rekening')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-lg mt-3">
                            <i class="ri-check-double-line me-2"></i> Simpan Data
                        </button>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Search Sekolah -->
    <div class="modal fade" id="searchSekolahModal" tabindex="-1" aria-labelledby="searchSekolahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchSekolahModalLabel">Cari Sekolah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="modalSearchInput" class="form-control"
                                placeholder="Masukkan nama sekolah, NPSN, atau alamat...">
                            <button class="btn btn-primary" type="button" id="modalSearchBtn">
                                <i class="ri-search-line"></i> Cari
                            </button>
                        </div>

                    </div>

                    <div id="modalSearchLoading" class="d-none text-center my-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Mencari sekolah...</p>
                    </div>

                    <div id="searchResults" class="mt-3" style="max-height: 300px; overflow-y: auto;">
                        <div class="text-center text-muted">
                            <i class="ri-search-line fs-4"></i>

                            <p class="mt-2">Masukkan kata kunci untuk mencari sekolah</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Autofill Konfirmasi -->
    <div class="modal fade" id="modalAutofill" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="ri-information-line text-info fs-1"></i>
                    <p class="mt-2 mb-0">Data ditemukan. Isi otomatis form?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btnOkeAutofill">Oke</button>
                </div>
            </div>
        </div>
    </div>

    @endsection


    @section('sipproja-js')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ============================================
        // 1. INITIALIZE LITEPICKER FOR TANGGAL LAHIR
        // ============================================

        // Initialize Litepicker for tanggal lahir
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date picker for tanggal lahir
            const picker = new Litepicker({
                element: document.getElementById('tgl_lahir'),
                format: 'DD-MM-YYYY',
                autoApply: true,
                maxDate: new Date(),
                dropdowns: {
                    minYear: 1950,
                    maxYear: new Date().getFullYear(),
                    months: true,
                    years: true
                },
                tooltipText: {
                    one: 'day',
                    other: 'days'
                },
                tooltipNumber: (totalDays) => {
                    return totalDays - 1;
                }
            });

            // Validasi tanggal lahir (minimal 17 tahun)
            function validateDateOfBirth() {
                const dobInput = document.getElementById('tgl_lahir');
                if (dobInput && dobInput.value) {
                    const dob = new Date(dobInput.value);
                    const today = new Date();
                    const minAgeDate = new Date(today.getFullYear() - 17, today.getMonth(), today.getDate());

                    if (dob > minAgeDate) {
                        dobInput.classList.add('is-invalid');
                        return false;
                    } else {
                        dobInput.classList.remove('is-invalid');
                        return true;
                    }
                }
                return true;
            }

            // Validate on change
            document.getElementById('tgl_lahir').addEventListener('change', function() {
                validateDateOfBirth();
            });

            // Validate before form submit
            document.getElementById('profilForm').addEventListener('submit', function(e) {
                if (!validateDateOfBirth()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Minimal usia 17 tahun',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            });

            // Format tanggal yang sudah ada (jika ada)
            const existingDate = document.getElementById('tgl_lahir').value;
            if (existingDate) {
                picker.setDate(existingDate);
            }
        });
        // ============================================
        // 2. TOGGLE SEKOLAH & UNIT KERJA
        // ============================================

        // Toggle antara sekolah dan unit kerja
        document.querySelectorAll('input[name="unit_kerja_option"]').forEach(radio => {
            radio.addEventListener('change', function(e) {
                const sekolahSection = document.getElementById('sekolahSection');
                const unitKerjaSection = document.getElementById('unitKerjaSection');
                const alamatKantorField = document.querySelector('textarea[name="alamat_kantor"]');
                const instansiField = document.getElementById('instansi');

                if (e.target.value === 'sekolah') {
                    sekolahSection.classList.remove('d-none');
                    unitKerjaSection.classList.add('d-none');
                    instansiField.value = ''; // Kosongkan instansi
                    instansiField.disabled = true; // Nonaktifkan field

                    // Jika sudah ada sekolah yang dipilih, ambil alamatnya
                    const sekolahId = document.getElementById('sekolah_id').value;
                    if (sekolahId && !alamatKantorField.value.trim()) {
                        fetchSekolahAlamat(sekolahId, alamatKantorField);
                    }
                } else {
                    sekolahSection.classList.add('d-none');
                    unitKerjaSection.classList.remove('d-none');
                    document.getElementById('sekolah_id').value = '';
                    document.getElementById('sekolah_search').value = '';
                    document.getElementById('sekolahInfo').classList.add('d-none');
                    instansiField.disabled = false; // Aktifkan field

                    // Reset alamat jika pindah ke unit kerja
                    if (!alamatKantorField.value.trim()) {
                        alamatKantorField.value = '';
                    }
                }
            });
        });

        // Initialize radio button state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const unitKerjaOption = document.querySelector('input[name="unit_kerja_option"]:checked');
            const instansiField = document.getElementById('instansi');

            if (unitKerjaOption && unitKerjaOption.value === 'sekolah') {
                instansiField.disabled = true;
            } else {
                instansiField.disabled = false;
            }

            // Jika ada sekolah_id dari old data, fetch alamat
            const sekolahId = document.getElementById('sekolah_id').value;
            const alamatKantorField = document.querySelector('textarea[name="alamat_kantor"]');
            if (sekolahId && !alamatKantorField.value.trim()) {
                fetchSekolahAlamat(sekolahId, alamatKantorField);
            }
        });

        // ============================================
        // 3. FUNCTIONS
        // ============================================

        // Function untuk fetch alamat sekolah
        function fetchSekolahAlamat(sekolahId, alamatField) {
            if (!sekolahId) return;

            fetch(`/api/sekolah/${sekolahId}/alamat`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.alamat) {
                        // Hanya set alamat jika field masih kosong
                        if (!alamatField.value.trim()) {
                            alamatField.value = data.alamat;

                            // Trigger change event untuk validasi
                            const event = new Event('change', {
                                bubbles: true
                            });
                            alamatField.dispatchEvent(event);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching sekolah alamat:', error);
                });
        }

        // Function untuk search sekolah
        function searchSekolah() {
            const keyword = document.getElementById('modalSearchInput').value.trim();
            if (keyword.length < 2) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pencarian',
                    text: 'Masukkan minimal 2 karakter',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const searchResults = document.getElementById('searchResults');
            const loadingIndicator = document.getElementById('modalSearchLoading');

            loadingIndicator.classList.remove('d-none');
            searchResults.innerHTML = '';

            fetch(`/search/sekolah?q=${encodeURIComponent(keyword)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    loadingIndicator.classList.add('d-none');

                    if (data.data && data.data.length > 0) {
                        let resultsHTML = '';
                        data.data.forEach(sekolah => {
                            resultsHTML += `
                    <div class="card mb-2 sekolah-item">
                        <div class="card-body">
                            <h6 class="card-title mb-1">${sekolah.nama_sekolah}</h6>
                            ${sekolah.npsn ? `<p class="card-text mb-1"><small><strong>NPSN:</strong> ${sekolah.npsn}</small></p>` : ''}
                            ${sekolah.alamat ? `<p class="card-text mb-2"><small><i class="ri-map-pin-line"></i> ${sekolah.alamat}</small></p>` : ''}
                            <button class="btn btn-sm btn-primary mt-1 pilih-sekolah-btn" 
                                    data-id="${sekolah.id}"
                                    data-nama="${sekolah.nama_sekolah}"
                                    data-npsn="${sekolah.npsn || ''}"
                                    data-alamat="${sekolah.alamat || ''}"
                                    data-kota="${sekolah.kab_kota || ''}">
                                <i class="ri-check-line"></i> Pilih Sekolah Ini
                            </button>
                        </div>
                    </div>`;
                        });
                        searchResults.innerHTML = resultsHTML;
                    } else {
                        searchResults.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="ri-search-line fs-4"></i>
                    <p class="mt-2">Sekolah tidak ditemukan</p>
                </div>`;
                    }
                })
                .catch(error => {
                    console.error('Error searching sekolah:', error);
                    loadingIndicator.classList.add('d-none');
                    searchResults.innerHTML = `
            <div class="text-center text-danger py-4">
                <i class="ri-error-warning-line fs-4"></i>
                <p class="mt-2">Gagal mencari sekolah. Silakan coba lagi.</p>
            </div>`;
                });
        }

        // Function untuk validasi form
        function validateForm() {
            const unitKerjaOption = document.querySelector('input[name="unit_kerja_option"]:checked');
            let isValid = true;
            let errorMessage = '';

            // Validasi berdasarkan pilihan unit kerja
            if (unitKerjaOption.value === 'unit_kerja') {
                const instansi = document.getElementById('instansi').value.trim();
                if (!instansi) {
                    errorMessage = 'Silakan isi nama unit kerja';
                    isValid = false;
                }
            }

            // Validasi alamat kantor
            const alamatKantor = document.querySelector('textarea[name="alamat_kantor"]').value.trim();
            if (!alamatKantor) {
                errorMessage = errorMessage ? errorMessage + '\nAlamat unit kerja harus diisi' : 'Alamat unit kerja harus diisi';
                isValid = false;
            }

            // Validasi kota
            const kotaSelect = document.getElementById('kota_id');
            if (!kotaSelect.value) {
                errorMessage = errorMessage ? errorMessage + '\nPilih kabupaten/kota' : 'Pilih kabupaten/kota';
                isValid = false;
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            }

            return isValid;
        }

        // ============================================
        // 4. EVENT HANDLERS
        // ============================================

        // Open search sekolah modal
        document.getElementById('btnSearchSekolah')?.addEventListener('click', function() {
            const searchModal = new bootstrap.Modal(document.getElementById('searchSekolahModal'));
            searchModal.show();
        });

        // Focus ke search input saat modal dibuka
        document.getElementById('searchSekolahModal')?.addEventListener('shown.bs.modal', function() {
            document.getElementById('modalSearchInput').focus();
        });

        // Search sekolah ketika Enter ditekan di modal
        document.getElementById('modalSearchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchSekolah();
            }
        });

        // Search sekolah ketika button diklik di modal
        document.getElementById('modalSearchBtn')?.addEventListener('click', function() {
            searchSekolah();
        });

        // Event ketika sekolah dipilih dari modal
        document.getElementById('searchResults')?.addEventListener('click', function(e) {
            if (e.target.classList.contains('pilih-sekolah-btn') ||
                e.target.closest('.pilih-sekolah-btn')) {

                const button = e.target.classList.contains('pilih-sekolah-btn') ?
                    e.target : e.target.closest('.pilih-sekolah-btn');

                const sekolahId = button.getAttribute('data-id');
                const sekolahNama = button.getAttribute('data-nama');
                const sekolahNpsn = button.getAttribute('data-npsn');
                const sekolahAlamat = button.getAttribute('data-alamat');
                const sekolahKota = button.getAttribute('data-kota');

                // Update form
                document.getElementById('sekolah_id').value = sekolahId;
                document.getElementById('sekolah_search').value = sekolahNama + (sekolahNpsn ? ` (NPSN: ${sekolahNpsn})` : '');

                // Update info sekolah
                document.getElementById('selectedSekolahName').textContent = sekolahNama;
                if (sekolahNpsn) {
                    document.getElementById('selectedSekolahNPSN').textContent = `NPSN: ${sekolahNpsn}`;
                } else {
                    document.getElementById('selectedSekolahNPSN').textContent = '';
                }
                document.getElementById('selectedSekolahAlamat').textContent = sekolahAlamat || 'Alamat tidak tersedia';
                document.getElementById('sekolahInfo').classList.remove('d-none');

                // Set alamat kantor otomatis
                const alamatKantorField = document.querySelector('textarea[name="alamat_kantor"]');
                if (sekolahAlamat && !alamatKantorField.value.trim()) {
                    alamatKantorField.value = sekolahAlamat;

                    // Trigger change event
                    const event = new Event('change', {
                        bubbles: true
                    });
                    alamatKantorField.dispatchEvent(event);
                }

                // Coba set kota otomatis jika ada data kota
                if (sekolahKota) {
                    const kotaSelect = document.getElementById('kota_id');
                    for (let i = 0; i < kotaSelect.options.length; i++) {
                        const option = kotaSelect.options[i];
                        if (option.text.toLowerCase().includes(sekolahKota.toLowerCase())) {
                            kotaSelect.value = option.value;
                            const changeEvent = new Event('change', {
                                bubbles: true
                            });
                            kotaSelect.dispatchEvent(changeEvent);
                            break;
                        }
                    }
                }

                // Pastikan radio sekolah dipilih
                document.getElementById('optionSekolah').checked = true;
                document.getElementById('optionSekolah').dispatchEvent(new Event('change'));

                // Kosongkan dan nonaktifkan field instansi
                const instansiField = document.getElementById('instansi');
                instansiField.value = '';
                instansiField.disabled = true;

                // Tutup modal
                const searchModal = bootstrap.Modal.getInstance(document.getElementById('searchSekolahModal'));
                searchModal.hide();
                document.getElementById('modalSearchInput').value = '';
            }
        });

        // Remove sekolah yang dipilih
        document.getElementById('btnRemoveSekolah')?.addEventListener('click', function() {
            const sekolahId = document.getElementById('sekolah_id').value;
            document.getElementById('sekolah_id').value = '';
            document.getElementById('sekolah_search').value = '';
            document.getElementById('sekolahInfo').classList.add('d-none');

            // Hapus alamat kantor jika berasal dari sekolah yang dihapus
            const alamatKantorField = document.querySelector('textarea[name="alamat_kantor"]');
            if (sekolahId && alamatKantorField.value.trim()) {
                fetch(`/api/sekolah/${sekolahId}/alamat`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.alamat && alamatKantorField.value.trim() === data.alamat.trim()) {
                            alamatKantorField.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

        // Reset modal saat dibuka
        document.getElementById('searchSekolahModal')?.addEventListener('show.bs.modal', function() {
            document.getElementById('modalSearchInput').value = '';
            document.getElementById('searchResults').innerHTML = `
        <div class="text-center text-muted">
            <i class="ri-search-line fs-4"></i>
            <p class="mt-2">Masukkan kata kunci untuk mencari sekolah</p>
        </div>`;
        });

        // Saat form submit
        document.getElementById('profilForm').addEventListener('submit', function(e) {
            const unitKerjaOption = document.querySelector('input[name="unit_kerja_option"]:checked');
            const instansiField = document.getElementById('instansi');

            // Handle field instansi berdasarkan pilihan
            if (unitKerjaOption.value === 'sekolah') {
                // Jika memilih sekolah, pastikan instansi kosong
                instansiField.value = '';
                instansiField.disabled = true;

                // Validasi: jika sekolah dipilih tapi tidak memilih sekolah, tidak apa-apa
                // sekolah_id bisa null
            } else {
                // Jika memilih unit kerja, pastikan field aktif
                instansiField.disabled = false;

                // Validasi: instansi harus diisi
                const instansi = instansiField.value.trim();
                if (!instansi) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Silakan isi nama unit kerja',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            }

            // Validasi form lainnya
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }

            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i> MENYIMPAN...';
            submitBtn.disabled = true;

            // Allow form to submit
            return true;
        });

        // Reset submit button jika form validation fails
        document.getElementById('profilForm').addEventListener('invalid', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="ri-check-double-line me-2"></i> Simpan Data';
            submitBtn.disabled = false;
        }, true);

        // ============================================
        // 5. AUTOCOMPLETE FOR SEKOLAH SEARCH
        // ============================================

        let timeoutId;
        document.getElementById('sekolah_search')?.addEventListener('input', function(e) {
            const keyword = e.target.value.trim();

            // Clear previous timeout
            clearTimeout(timeoutId);

            // Set new timeout for debouncing
            timeoutId = setTimeout(() => {
                if (keyword.length >= 2) {
                    searchSekolahForAutoComplete(keyword);
                } else {
                    // Hide any existing dropdown
                    const existingDropdown = document.getElementById('sekolahAutocompleteDropdown');
                    if (existingDropdown) {
                        existingDropdown.remove();
                    }
                }
            }, 300);
        });

        // Function untuk autocomplete search
        function searchSekolahForAutoComplete(keyword) {
            fetch(`/search/sekolah?q=${encodeURIComponent(keyword)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data && data.data.length > 0) {
                        showAutocompleteDropdown(data.data);
                    } else {
                        // Remove existing dropdown
                        const existingDropdown = document.getElementById('sekolahAutocompleteDropdown');
                        if (existingDropdown) {
                            existingDropdown.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error autocomplete:', error);
                });
        }

        // Function untuk menampilkan dropdown autocomplete
        function showAutocompleteDropdown(sekolahs) {
            // Remove existing dropdown
            const existingDropdown = document.getElementById('sekolahAutocompleteDropdown');
            if (existingDropdown) {
                existingDropdown.remove();
            }

            // Create dropdown
            const dropdown = document.createElement('div');
            dropdown.id = 'sekolahAutocompleteDropdown';
            dropdown.className = 'dropdown-menu show w-100';
            dropdown.style.position = 'absolute';
            dropdown.style.zIndex = '1050';
            dropdown.style.maxHeight = '300px';
            dropdown.style.overflowY = 'auto';
            dropdown.style.border = '1px solid #ddd';
            dropdown.style.borderRadius = '8px';
            dropdown.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';

            // Add items to dropdown
            sekolahs.forEach(sekolah => {
                const item = document.createElement('a');
                item.className = 'dropdown-item py-2';
                item.href = '#';
                item.style.cursor = 'pointer';
                item.innerHTML = `
                <div>
                    <strong>${sekolah.nama_sekolah}</strong>
                    ${sekolah.npsn ? `<small class="text-muted d-block">NPSN: ${sekolah.npsn}</small>` : ''}
                    ${sekolah.alamat ? `<small class="text-muted d-block text-truncate">${sekolah.alamat.substring(0, 50)}...</small>` : ''}
                </div>
            `;

                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Select the school
                    document.getElementById('sekolah_id').value = sekolah.id;
                    document.getElementById('sekolah_search').value = sekolah.nama_sekolah + (sekolah.npsn ? ` (NPSN: ${sekolah.npsn})` : '');

                    // Update info sekolah
                    document.getElementById('selectedSekolahName').textContent = sekolah.nama_sekolah;
                    if (sekolah.npsn) {
                        document.getElementById('selectedSekolahNPSN').textContent = `NPSN: ${sekolah.npsn}`;
                    } else {
                        document.getElementById('selectedSekolahNPSN').textContent = '';
                    }
                    document.getElementById('selectedSekolahAlamat').textContent = sekolah.alamat || 'Alamat tidak tersedia';
                    document.getElementById('sekolahInfo').classList.remove('d-none');

                    // Set alamat kantor otomatis
                    const alamatKantorField = document.querySelector('textarea[name="alamat_kantor"]');
                    if (sekolah.alamat && !alamatKantorField.value.trim()) {
                        alamatKantorField.value = sekolah.alamat;

                        // Trigger change event
                        const event = new Event('change', {
                            bubbles: true
                        });
                        alamatKantorField.dispatchEvent(event);
                    }

                    // Coba set kota otomatis jika ada data kota
                    if (sekolah.kab_kota) {
                        const kotaSelect = document.getElementById('kota_id');
                        for (let i = 0; i < kotaSelect.options.length; i++) {
                            const option = kotaSelect.options[i];
                            if (option.text.toLowerCase().includes(sekolah.kab_kota.toLowerCase())) {
                                kotaSelect.value = option.value;
                                const changeEvent = new Event('change', {
                                    bubbles: true
                                });
                                kotaSelect.dispatchEvent(changeEvent);
                                break;
                            }
                        }
                    }

                    // Pastikan radio sekolah dipilih
                    document.getElementById('optionSekolah').checked = true;
                    document.getElementById('optionSekolah').dispatchEvent(new Event('change'));

                    // Kosongkan dan nonaktifkan field instansi
                    const instansiField = document.getElementById('instansi');
                    instansiField.value = '';
                    instansiField.disabled = true;

                    // Remove dropdown
                    dropdown.remove();
                });

                dropdown.appendChild(item);
            });

            // Position dropdown below search input
            const searchInput = document.getElementById('sekolah_search');
            const rect = searchInput.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + window.scrollY) + 'px';
            dropdown.style.left = (rect.left + window.scrollX) + 'px';
            dropdown.style.width = rect.width + 'px';

            // Add to document
            document.body.appendChild(dropdown);

            // Close dropdown when clicking outside
            document.addEventListener('click', function closeDropdown(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.remove();
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }

        // ============================================
        // 6. FORM VALIDATION STYLING
        // ============================================

        // Add validation styling on blur
        document.querySelectorAll('#profilForm input[required], #profilForm select[required], #profilForm textarea[required]').forEach(element => {
            element.addEventListener('blur', function() {
                if (!this.value.trim() && this.hasAttribute('required')) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Clear validation on input
        document.querySelectorAll('#profilForm input, #profilForm select, #profilForm textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // ============================================
        // 7. HELPER FUNCTIONS
        // ============================================

        // Format NIK input (hanya angka, maks 16 digit)
        document.querySelector('input[name="nik"]')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) {
                value = value.substring(0, 16);
            }
            e.target.value = value;
        });

        // Format NIP input (hanya angka, maks 19 digit)
        document.querySelector('input[name="nip"]')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 19) {
                value = value.substring(0, 19);
            }
            e.target.value = value;
        });

        // Format No HP input (hanya angka)
        document.querySelector('input[name="no_hp"]')?.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Format NPWP input (hanya angka dan strip)
        document.querySelector('input[name="npwp"]')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d-]/g, '');
            e.target.value = value;
        });

        // Format No Rekening input (hanya angka)
        document.querySelector('input[name="no_rekening"]')?.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // ============================================
        // 10. NIK VALIDATION
        // ============================================

        // Validasi NIK real-time
        let nikTimeout;
        document.querySelector('input[name="nik"]')?.addEventListener('input', function(e) {
            const nik = e.target.value.trim();

            // Clear previous timeout
            clearTimeout(nikTimeout);

            // Set new timeout for debouncing
            nikTimeout = setTimeout(() => {
                if (nik.length === 16) {
                    validateNik(nik);
                } else if (nik.length > 16) {
                    showNikValidation('NIK maksimal 16 digit', 'warning');
                }
            }, 500);
        });

        function validateNik(nik) {
            // Format hanya angka
            if (!/^\d+$/.test(nik)) {
                showNikValidation('Hanya angka yang diperbolehkan', 'error');
                return;
            }

            // Cek panjang NIK (harus 16 digit)
            if (nik.length !== 16) {
                showNikValidation('NIK harus 16 digit', 'warning');
                return;
            }

            // Validasi struktur NIK (opsional)
            // Struktur: 6 digit kode wilayah + 6 digit tanggal lahir + 4 digit nomor urut
            const kodeWilayah = nik.substring(0, 6);
            const tanggalLahir = nik.substring(6, 12);

            // Cek apakah tanggal lahir valid (opsional)
            const hari = parseInt(tanggalLahir.substring(0, 2));
            const bulan = parseInt(tanggalLahir.substring(2, 4));
            const tahun = parseInt(tanggalLahir.substring(4, 6));

            if (hari < 1 || hari > 31 || bulan < 1 || bulan > 12) {
                showNikValidation('Format tanggal lahir dalam NIK tidak valid', 'warning');
            } else {
                showNikValidation('NIK valid', 'success');
            }
        }

        function showNikValidation(message, type) {
            // Hapus pesan validasi sebelumnya
            const existingFeedback = document.querySelector('.nik-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }

            const nikInput = document.querySelector('input[name="nik"]');
            const parentDiv = nikInput.closest('.form-floating');

            // Buat elemen feedback baru
            const feedback = document.createElement('div');
            feedback.className = `nik-feedback small mt-1`;

            if (type === 'error') {
                feedback.className += ' text-danger';
                nikInput.classList.add('is-invalid');
                nikInput.classList.remove('is-valid');
            } else if (type === 'success') {
                feedback.className += ' text-success';
                nikInput.classList.add('is-valid');
                nikInput.classList.remove('is-invalid');
            } else if (type === 'warning') {
                feedback.className += ' text-warning';
                nikInput.classList.remove('is-invalid');
                nikInput.classList.remove('is-valid');
            }

            feedback.innerHTML = `<i class="ri-information-line me-1"></i> ${message}`;
            parentDiv.appendChild(feedback);
        }


        // ===== AUTOFILL & DOUBLE REGISTRASI =====
        const btnCariNip = document.getElementById('btnCariNip');
        const nipInput = document.getElementById('nip');
        let modalAf = null;
        let afData = null;

        // Inisialisasi modal setelah DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('modalAutofill');
            if (modalElement) {
                modalAf = new bootstrap.Modal(modalElement);
            }
        });

        // Fungsi untuk mengecek apakah modal sudah diinisialisasi
        function getModalAf() {
            if (!modalAf) {
                const modalElement = document.getElementById('modalAutofill');
                if (modalElement) {
                    modalAf = new bootstrap.Modal(modalElement);
                }
            }
            return modalAf;
        }

        btnCariNip.addEventListener('click', function() {
            const nip = nipInput.value.trim();
            if (!nip) {
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: 'Masukkan NIP terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validasi format NIP
            if (!/^\d+$/.test(nip)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Format Salah',
                    text: 'NIP hanya boleh berisi angka',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (nip.length !== 18 && nip.length !== 19) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Format Salah',
                    text: 'NIP harus 18 atau 19 digit',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Tampilkan loading
            const originalBtnContent = btnCariNip.innerHTML;
            btnCariNip.innerHTML = '<i class="ri-loader-4-line spinner"></i>';
            btnCariNip.disabled = true;

            fetch(`/register/{{ $encode_kegiatan_id }}/cek-nip?nip=${nip}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => {
                    if (!r.ok) {
                        throw new Error(`HTTP error! status: ${r.status}`);
                    }
                    return r.json();
                })
                .then(res => {
                    console.log('Response from server:', res); // Debug log

                    // Reset button
                    btnCariNip.innerHTML = originalBtnContent;
                    btnCariNip.disabled = false;

                    if (res.sudah_terdaftar) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sudah Terdaftar',
                            text: 'Anda sudah terdaftar di kegiatan ini',
                            confirmButtonText: 'OK'
                        });

                        // Nonaktifkan tombol simpan
                        const submitBtn = document.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="ri-close-line me-2"></i> Sudah Terdaftar';
                        }
                        return;
                    }

                    if (res.data) {
                        afData = res.data;
                        const modalAf = getModalAf();

                        if (modalAf) {
                            // Update modal content berdasarkan source
                            const modalBody = document.querySelector('#modalAutofill .modal-body');
                            if (modalBody) {
                                let icon = 'ri-information-line';
                                let iconColor = 'text-info';
                                let title = 'Data Ditemukan';
                                let message = 'Data dapat diisi otomatis. Lanjutkan?';

                                if (res.source === 'ptk') {
                                    icon = 'ri-database-line';
                                    iconColor = 'text-primary';
                                    title = 'Data Ditemukan dari Database';
                                    message = 'Data ditemukan dari database utama. Isi otomatis form?';
                                } else if (res.source === 'peserta') {
                                    icon = 'ri-calendar-event-line';
                                    iconColor = 'text-warning';
                                    title = 'Data Ditemukan dari Kegiatan Lain';
                                    message = 'Data ditemukan dari peserta kegiatan lain. Isi otomatis form?';
                                }

                                modalBody.innerHTML = `
                    <div class="text-center">
                        <i class="${icon} ${iconColor} fs-1 mb-3"></i>
                        <h6 class="fw-bold mb-2">${title}</h6>
                        <p class="mb-0">${message}</p>
                    </div>
                `;
                            }

                            modalAf.show();
                        }
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Data Tidak Ditemukan',
                            text: 'Data NIP tidak ditemukan. Silakan isi manual',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset button
                    btnCariNip.innerHTML = originalBtnContent;
                    btnCariNip.disabled = false;

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memeriksa NIP. Silakan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                });
        });

        // tombol Oke di modal autofill - DIPERBAIKI UNTUK BANK
        document.getElementById('btnOkeAutofill').addEventListener('click', () => {
            if (!afData) return;

            console.log('Autofill data received:', afData); // Debug log

            // List semua field yang perlu di-autofill
            const fieldsToAutofill = [
                // Text fields
                {
                    name: 'nama',
                    value: afData.nama
                },
                {
                    name: 'nik',
                    value: afData.nik
                },
                {
                    name: 'tempat_lahir',
                    value: afData.tempat_lahir
                },
                {
                    name: 'tgl_lahir',
                    value: afData.tgl_lahir
                },
                {
                    name: 'pendidikan',
                    value: afData.pendidikan
                },
                {
                    name: 'alamat_kantor',
                    value: afData.alamat_kantor
                },
                {
                    name: 'no_hp',
                    value: afData.no_hp
                },
                {
                    name: 'email',
                    value: afData.email
                },
                {
                    name: 'npwp',
                    value: afData.npwp
                },
                {
                    name: 'no_rekening',
                    value: afData.no_rekening
                },
                {
                    name: 'atas_nama_rekening',
                    value: afData.atas_nama_rekening
                },

                // Select fields
                {
                    name: 'pangkat_jabatan_id',
                    value: afData.pangkat_jabatan_id,
                    type: 'select'
                },
                {
                    name: 'agama',
                    value: afData.agama,
                    type: 'select'
                },
                {
                    name: 'jenis_kelamin',
                    value: afData.jenis_kelamin,
                    type: 'select'
                },
                {
                    name: 'kota_id',
                    value: afData.kota_id,
                    type: 'select'
                },
                {
                    name: 'ms_bank_id',
                    value: afData.ms_bank_id,
                    type: 'select'
                }
            ];

            // Proses autofill untuk setiap field
            fieldsToAutofill.forEach(field => {
                const el = document.querySelector(`[name="${field.name}"]`);
                if (!el) {
                    console.warn(`Field ${field.name} tidak ditemukan`);
                    return;
                }

                if (field.value && !el.value.trim()) {
                    if (field.type === 'select') {
                        // Untuk select element
                        console.log(`Setting select ${field.name} to value:`, field.value);

                        // Coba set langsung
                        el.value = field.value;

                        // Cek apakah value berhasil diset
                        if (el.value != field.value) {
                            // Jika tidak berhasil, coba cari option
                            console.log(`Value tidak langsung match, mencari option...`);

                            // Cara 1: Cari exact match by value
                            let found = false;
                            for (let i = 0; i < el.options.length; i++) {
                                if (el.options[i].value == field.value) {
                                    el.options[i].selected = true;
                                    found = true;
                                    console.log(`Found exact match for ${field.name}: value=${field.value}`);
                                    break;
                                }
                            }

                            // Cara 2: Jika tidak ditemukan exact value, coba cari by text
                            if (!found && field.name === 'ms_bank_id') {
                                // Untuk bank, kita bisa coba cari nama bank dari data
                                const bankName = getBankNameFromData(field.value);
                                if (bankName) {
                                    for (let i = 0; i < el.options.length; i++) {
                                        if (el.options[i].text.toLowerCase().includes(bankName.toLowerCase())) {
                                            el.options[i].selected = true;
                                            found = true;
                                            console.log(`Found bank by name: ${bankName}`);
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        // Trigger events
                        el.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        el.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));

                    } else {
                        // Untuk input text
                        el.value = field.value;
                        el.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        el.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                }
            });

            // Handle sekolah_id & unit kerja
            handleUnitKerjaAutofill(afData);

            const modalAf = getModalAf();
            if (modalAf) {
                modalAf.hide();
            }

            // Beri konfirmasi sukses
            Swal.fire({
                icon: 'success',
                title: 'Autofill Berhasil',
                text: 'Data telah diisi otomatis. Silakan periksa dan lengkapi jika perlu.',
                timer: 1500,
                showConfirmButton: false
            });
        });

        // Function untuk mendapatkan nama bank berdasarkan ms_bank_id
        function getBankNameFromData(bankId) {
            // Ini contoh, Anda bisa sesuaikan dengan data bank yang ada
            const bankMap = {
                1: 'Bank BRI',
                2: 'Bank BNI',
                3: 'Bank Mandiri',
                4: 'Bank BCA',
                5: 'Bank BTN',
                6: 'Bank Syariah Indonesia',
                7: 'Bank Jateng',
                8: 'Bank DKI',
                // Tambahkan mapping lainnya sesuai database Anda
            };

            return bankMap[bankId] || null;
        }

        function handleUnitKerjaAutofill(data) {
            const sekolahId = data.sekolah_id;
            const instansi = data.instansi;
            const sekolahRadio = document.getElementById('optionSekolah');
            const unitKerjaRadio = document.getElementById('optionUnitKerja');

            if (sekolahId) {
                // Jika ada sekolah_id, set radio button ke sekolah
                if (sekolahRadio) {
                    sekolahRadio.checked = true;
                    sekolahRadio.dispatchEvent(new Event('change'));
                }

                // Fetch info sekolah dan isi
                fetch(`/api/sekolah/${sekolahId}/info`)
                    .then(r => {
                        if (!r.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return r.json();
                    })
                    .then(res => {
                        if (res.success) {
                            // Set sekolah_id hidden field
                            const sekolahIdField = document.querySelector('input[name="sekolah_id"]');
                            if (sekolahIdField) {
                                sekolahIdField.value = sekolahId;
                            }

                            // Set sekolah search field
                            const sekolahSearch = document.getElementById('sekolah_search');
                            if (sekolahSearch) {
                                sekolahSearch.value = res.nama_sekolah || 'Sekolah ditemukan';
                            }

                            // Update info sekolah display
                            const sekolahInfo = document.getElementById('sekolahInfo');
                            const selectedName = document.getElementById('selectedSekolahName');
                            const selectedNPSN = document.getElementById('selectedSekolahNPSN');
                            const selectedAlamat = document.getElementById('selectedSekolahAlamat');

                            if (selectedName) selectedName.textContent = res.nama_sekolah || 'Sekolah terpilih';
                            if (selectedNPSN) selectedNPSN.textContent = res.npsn ? `NPSN: ${res.npsn}` : '';
                            if (selectedAlamat) selectedAlamat.textContent = res.alamat || 'Alamat tidak tersedia';
                            if (sekolahInfo) sekolahInfo.classList.remove('d-none');

                            // Set alamat kantor otomatis jika kosong
                            const alamatKantorField = document.querySelector('textarea[name="alamat_kantor"]');
                            if (res.alamat && alamatKantorField && !alamatKantorField.value.trim()) {
                                alamatKantorField.value = res.alamat;
                                alamatKantorField.dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                            }

                            // Coba set kota otomatis jika ada data kota
                            if (res.kota) {
                                setKotaByNama(res.kota);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching sekolah:', error);
                        // Tetap set sekolah_id meski fetch gagal
                        const sekolahIdField = document.querySelector('input[name="sekolah_id"]');
                        if (sekolahIdField) {
                            sekolahIdField.value = sekolahId;
                        }
                    });

            } else if (instansi && instansi.trim() !== '') {
                // Jika ada instansi dan tidak kosong, set radio button ke unit kerja
                if (unitKerjaRadio) {
                    unitKerjaRadio.checked = true;
                    unitKerjaRadio.dispatchEvent(new Event('change'));
                }

                // Isi field instansi
                const instansiField = document.getElementById('instansi');
                if (instansiField) {
                    instansiField.value = instansi;
                    instansiField.disabled = false;
                }

                // Kosongkan sekolah
                const sekolahIdField = document.querySelector('input[name="sekolah_id"]');
                if (sekolahIdField) sekolahIdField.value = '';

                const sekolahSearch = document.getElementById('sekolah_search');
                if (sekolahSearch) sekolahSearch.value = '';

                const sekolahInfo = document.getElementById('sekolahInfo');
                if (sekolahInfo) sekolahInfo.classList.add('d-none');
            } else {
                // Jika tidak ada sekolah_id dan instansi kosong/null, default ke sekolah
                if (sekolahRadio) {
                    sekolahRadio.checked = true;
                    sekolahRadio.dispatchEvent(new Event('change'));
                }
            }
        }

        function setKotaByNama(kotaNama) {
            const kotaSelect = document.getElementById('kota_id');
            if (!kotaSelect) return false;

            // Cari option yang mengandung nama kota
            for (let i = 0; i < kotaSelect.options.length; i++) {
                const option = kotaSelect.options[i];
                if (option.text.toLowerCase().includes(kotaNama.toLowerCase())) {
                    kotaSelect.value = option.value;
                    kotaSelect.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                    return true;
                }
            }
            return false;
        }

        // ============================================
        // REAL-TIME NIP VALIDATION (TAMBAHAN)
        // ============================================
        let nipValidationTimeout;
        const nipField = document.getElementById('nip');

        if (nipField) {
            nipField.addEventListener('input', function(e) {
                const nip = e.target.value.trim();
                const submitBtn = document.querySelector('button[type="submit"]');

                // Clear previous timeout
                clearTimeout(nipValidationTimeout);

                // Reset tombol ke kondisi normal
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ri-check-double-line me-2"></i> Simpan Data';
                }

                // Validasi format dasar
                if (nip.length > 0 && !/^\d+$/.test(nip)) {
                    showNipValidationMessage('Hanya angka yang diperbolehkan', 'error');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="ri-close-line me-2"></i> Format Salah';
                    }
                    return;
                }

                if (nip.length > 0 && (nip.length !== 18 && nip.length !== 19)) {
                    showNipValidationMessage('NIP harus 18 atau 19 digit', 'warning');
                    return;
                }

                // Set timeout untuk validasi server
                nipValidationTimeout = setTimeout(() => {
                    if (nip.length >= 18) {
                        validateNipOnServer(nip);
                    }
                }, 800);
            });
        }

        function validateNipOnServer(nip) {
            const submitBtn = document.querySelector('button[type="submit"]');

            fetch(`/register/{{ $encode_kegiatan_id }}/cek-nip?nip=${nip}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.sudah_terdaftar) {
                        showNipValidationMessage('NIP sudah terdaftar di kegiatan ini', 'error');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="ri-close-line me-2"></i> Sudah Terdaftar';
                        }
                    } else {
                        showNipValidationMessage('NIP dapat digunakan', 'success');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="ri-check-double-line me-2"></i> Simpan Data';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error validating NIP:', error);
                    showNipValidationMessage('Gagal validasi NIP', 'error');
                });
        }

        function showNipValidationMessage(message, type) {
            // Cari atau buat div untuk pesan validasi
            let validationDiv = document.getElementById('nipValidationMsg');

            if (!validationDiv) {
                validationDiv = document.createElement('div');
                validationDiv.id = 'nipValidationMsg';
                validationDiv.className = 'small mt-1';

                // Tempatkan setelah input NIP
                const nipContainer = nipField.closest('.form-floating');
                if (nipContainer) {
                    nipContainer.appendChild(validationDiv);
                }
            }

            // Update kelas dan pesan
            validationDiv.className = 'small mt-1';
            if (type === 'error') {
                validationDiv.classList.add('text-danger');
            } else if (type === 'success') {
                validationDiv.classList.add('text-success');
            } else if (type === 'warning') {
                validationDiv.classList.add('text-warning');
            }

            validationDiv.innerHTML = `<i class="ri-information-line me-1"></i> ${message}`;
        }

        // ============================================
        // STYLING UNTUK SPINNER (TAMBAHAN KE CSS)
        // ============================================
        const style = document.createElement('style');
        style.textContent = `
    .spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    #nipValidationMsg {
        font-size: 12px;
        margin-top: 4px;
        padding: 2px 6px;
        border-radius: 4px;
        background-color: rgba(0,0,0,0.02);
    }
    
    #nipValidationMsg.text-danger {
        color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.05);
    }
    
    #nipValidationMsg.text-success {
        color: #198754 !important;
        background-color: rgba(25, 135, 84, 0.05);
    }
    
    #nipValidationMsg.text-warning {
        color: #ffc107 !important;
        background-color: rgba(255, 193, 7, 0.05);
    }
    
    .search-nip-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
`;
        document.head.appendChild(style);
    </script>

    @endsection