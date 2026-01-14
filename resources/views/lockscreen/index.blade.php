<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock Screen - {{ $kegiatan->kegiatan_name }}</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- CSS LOGIN + PROFIL (INI YG BAWA .baduy-bg ASLI) -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <style>
        /* ===== SEKOLAH SELECT + SEARCH BUTTON NYAMBUNG ===== */
        #registerModal .mm-float.input-group{
            width: 100%;
            align-items: stretch;
        }

        /* selectnya jadi sisi kiri, radius kanan dimatiin biar nempel sama tombol */
        #registerModal .mm-float.input-group > .form-control{
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* tombol search nempel + tinggi sama */
        #registerModal #openSearchModalBtn{
            height: 58px !important;
            border-radius: 0 12px 12px 0 !important;
            border: 1px solid #d7e2ff !important;
            border-left: none !important;
            padding: 0 18px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        #registerModal #openSearchModalBtn i{
            font-size: 20px;
        }

        /* =======================================================
           REGISTER MODAL - INPUT STYLE (NGIKUT BIODATA PAGE)
           ======================================================= */

        #registerModal .form-control,
        #registerModal select,
        #registerModal textarea {
            background: #ffffff !important;
            border: 1px solid #d7e2ff !important;
            border-radius: 12px !important;
            padding-top: 20px !important;
            padding-bottom: 12px !important;
            height: 58px !important;
            font-size: 15px !important;
            color: #555555 !important;
            font-weight: 600 !important;
        }

        #registerModal .form-control::placeholder,
        #registerModal select::placeholder,
        #registerModal textarea::placeholder {
            color: #555555 !important;
            font-weight: 500 !important;
        }

        #registerModal .form-control:focus,
        #registerModal select:focus,
        #registerModal textarea:focus {
            border-color: #1a5bb8 !important;
            box-shadow: 0 0 0 2px rgba(26, 91, 184, 0.2) !important;
        }

        #registerModal label.form-label {
            display: none !important;
        }

        #registerModal .col-md-6,
        #registerModal .col-12 {
            margin-bottom: 16px !important;
        }

        #registerModal textarea.form-control {
            height: auto !important;
            padding-top: 14px !important;
        }

        #registerModal .form-check-label {
            color: #1a3f6b !important;
            font-weight: 600 !important;
        }

        /* Tombol di register modal (sama kayak biodata) */
        #registerModal .btn-primary {
            background-color: #133d78 !important;
            border-color: #133d78 !important;
            color: #fff !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
        }

        #registerModal .btn-secondary {
            border-radius: 10px !important;
        }

        /* =======================================================
           GLOBAL MODAL WRAPPER
           ======================================================= */

        /* Hilangkan rounded di layout lockscreen */
        .no-radius,
        .no-radius * {
            border-radius: 0 !important;
        }

        .custom-modal .modal-content {
            border-radius: 10px !important;
            border: none !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        .custom-modal .modal-header:not(.baduy-bg) {
            background: #dff1ff !important;
            padding: 18px 22px !important;
            border-bottom: 1px solid #e7edf3 !important;
        }

        .custom-modal .modal-header h5,
        .custom-modal-title {
            font-size: 20px !important;
            font-weight: 700 !important;
            color: #444 !important;
            font-family: 'Inter', sans-serif;
        }

        #registerModal .modal-header.baduy-bg {
            border-radius: 14px 14px 0 0 !important;
        }

        #registerModal .modal-header.baduy-bg * {
            position: relative;
            z-index: 2;
            color: #fff !important;
            font-weight: 700 !important;
        }

        #registerModal .modal-header .btn-close {
            filter: brightness(10) !important;
        }

        .custom-modal .btn-close {
            filter: invert(0.4);
            transform: scale(1.2);
        }

        .custom-modal .modal-body {
            padding: 28px 26px !important;
        }

        .custom-modal .form-label {
            font-weight: 600 !important;
            color: #555 !important;
            font-size: 14px !important;
            margin-bottom: 6px !important;
        }

        .custom-modal .form-control,
        .custom-modal select,
        .custom-modal textarea {
            background: #ffffff !important;
            border: 1px solid #d6d6d6 !important;
            border-radius: 6px !important;
            font-size: 15px !important;
            padding: 10px 12px !important;
            color: #333 !important;
            box-shadow: none !important;
        }

        .custom-modal .form-control:focus,
        .custom-modal select:focus,
        .custom-modal textarea:focus {
            border-color: #8bb5ff !important;
            box-shadow: 0 0 0 2px rgba(120, 160, 255, 0.25) !important;
        }

        .custom-modal .input-group-text {
            background: #fff !important;
            border-left: none !important;
            border-radius: 0 6px 6px 0 !important;
            cursor: pointer;
            border: 1px solid #d6d6d6 !important;
            border-left: none !important;
        }

        .custom-modal .input-group .form-control {
            border-right: none !important;
        }

        .custom-modal .modal-footer {
            padding: 18px 26px !important;
            border-top: 1px solid #eaeaea !important;
        }

        .custom-modal .btn-secondary {
            background: #f3f3f3;
            color: #444;
            border: none;
            padding: 9px 20px;
            font-size: 15px;
            border-radius: 8px;
        }

        .custom-modal .btn-primary {
            background: #1db5a6 !important;
            border: none !important;
            padding: 9px 22px !important;
            font-size: 15px !important;
            border-radius: 8px !important;
        }

        /* =======================================================
           FLOATING LABEL (PASTI MUNCUL) - REGISTER MODAL
           ======================================================= */

        #registerModal .mm-float {
            position: relative;
        }

        /* pastiin label tampil walau ada css global yang nyembunyiin label */
        #registerModal .mm-label {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        #registerModal .mm-float .mm-label {
            position: absolute;
            left: 14px;
            top: 18px;
            font-size: 15px;
            font-weight: 700;
            color: #6b7280;
            transition: all .15s ease;
            pointer-events: none;
            padding: 0 6px;
            z-index: 9;
            background: transparent;
        }

        /* biar input gak nutup label */
        #registerModal .mm-float .form-control,
        #registerModal .mm-float select,
        #registerModal .mm-float textarea {
            position: relative !important;
            z-index: 1 !important;
            padding-top: 28px !important;
        }

        /* fokus / isi -> label naik */
        #registerModal .mm-float:focus-within .mm-label,
        #registerModal .mm-float.is-filled .mm-label {
            top: 6px;
            font-size: 12px;
            color: #133d78;
            background: #fff;
        }

        /* kalau mm-float dipakai di input-group */
        #registerModal .input-group .mm-float {
            flex: 1 1 auto;
            width: 1%;
        }

        /* kecilin tinggi select dikit biar enak */
        #registerModal .mm-float select.form-control {
            height: 58px !important;
        }
    </style>
</head>

<body>
    <div class="auth-container no-radius">
        <!-- Bagian Kiri: Carousel + BG Baduy -->
        <div class="info-section baduy-bg no-radius">
            <div class="carousel-container" style="position: relative; z-index: 1;">
                <div class="carousel-content">

                    <h1 class="system-name">
                        <img src="{{ asset('build/images/logobgtkPutih.png') }}"
                            class="system-logo"
                            style="width: 250px; height: auto; margin-bottom: 10px;">
                    </h1>

                    <div id="infoCarousel" class="carousel slide" data-bs-ride="carousel" style="margin-bottom: 77px;">

                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="2"></button>
                        </div>

                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/1.jpg') }}" class="carousel-image">
                                <div class="carousel-caption">
                                    <h5>Kolaborasi Tim</h5>
                                    <p>Bekerja sama untuk mencapai tujuan bersama</p>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="{{ asset('images/2.jpg') }}" class="carousel-image">
                                <div class="carousel-caption">
                                    <h5>Teknologi Modern</h5>
                                    <p>Menggunakan teknologi terkini untuk solusi terbaik</p>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="{{ asset('images/3.jpg') }}" class="carousel-image">
                                <div class="carousel-caption">
                                    <h5>Diskusi Produktif</h5>
                                    <p>Berbagi ide untuk inovasi yang lebih baik</p>
                                </div>
                            </div>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#infoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#infoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <!-- Bagian Kanan - Form Login -->
        <div class="login-section" style="margin-top:40px;">
            <div class="login-form-container">

                <div class="login-logo">
                    <h2 class="login-title">
                        <img src="{{ asset('build/images/tanparagu2.png') }}" class="login-title-icon">
                    </h2>
                </div>

                <!-- Info Kegiatan Aktif -->
                <div class="kegiatan-info mb-3">
                    <h6 class="mb-1 text-primary">
                        <i class="ri-calendar-event-line me-1"></i> Kegiatan Berlangsung:
                    </h6>
                    <h5 class="mb-2">{{ $kegiatan->kegiatan_name }}</h5>
                    <small class="text-muted">
                        <i class="ri-calendar-2-line me-1"></i>
                        Periode: {{ date('d/m/Y', strtotime($kegiatan->start_date)) }}
                        -
                        {{ date('d/m/Y', strtotime($kegiatan->end_date)) }}
                    </small>
                </div>

                <!-- Form Login -->
                <form id="login-form" method="POST">
                    @csrf
                    <input type="hidden" id="kegiatan_id" name="kegiatan_id" value="{{ $kegiatan_id }}">

                    <!-- Field NIP -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i></i> Nomor Induk Pegawai (NIP)
                        </label>
                        <input type="text" name="nip" id="nip" class="form-control"
                            value="{{ old('nip', session('preserve_nip') ?? '') }}"
                            placeholder="Masukkan 18 digit NIP Anda"
                            required
                            autofocus
                            maxlength="18"
                            pattern="\d{18}"
                            title="Masukkan 18 digit NIP">
                        <small class="form-text text-muted mt-1">Contoh: 197010021990092010</small>
                    </div>

                    <!-- Field Token -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i></i> Masukkan Token Kegiatan
                        </label>
                        <div class="input-group">
                            <input type="password" name="token" id="tokenInput"
                                class="form-control"
                                value="{{ old('token', '') }}"
                                placeholder="Masukkan token yang diberikan"
                                required>
                            <span class="input-group-text password-toggle" onclick="togglePassword()">
                                <i class="ri-eye-fill" id="passwordIcon"></i>
                            </span>
                        </div>
                        <small class="form-text text-muted mt-1">Token diberikan oleh administrator kegiatan</small>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-login text-white py-2" id="submitBtn" style="font-size:15px;">
                            <i class="ri-login-box-line me-1"></i> Masuk ke Sistem
                        </button>
                    </div>
                </form>

                <!-- Logo Partner -->
                <div class="photo-gallery mt-4">
                    <img src="{{ asset('images/logoPendidikan.png') }}" class="gallery-photo" alt="Kemdikbud">
                    <img src="{{ asset('images/logoRamah.png') }}" class="gallery-photo" alt="Ramah Anak">
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================
         MODAL REGISTRASI
         ========================== -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel"
        aria-hidden="true" data-bs-backdrop="static">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content custom-modal">

                <div class="modal-header baduy-bg">
                    <h5 class="modal-title">Form Biodata</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-information-line fs-4 me-3"></i>
                            <div>
                                <h6 class="mb-1">NIP Belum Terdaftar</h6>
                                <p class="mb-0" id="modal-info-text"></p>
                            </div>
                        </div>
                    </div>

                    <form id="register-form">
                        @csrf

                        <input type="hidden" id="reg_nip" name="nip">
                        <input type="hidden" id="reg_kegiatan_id" name="kegiatan_id">
                        <input type="hidden" id="reg_token" name="token">

                        <div class="row">

                            <!-- NUPTK -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="text" name="nuptk" class="form-control" placeholder=" " maxlength="16">
                                    <label class="mm-label">16 digit NUPTK</label>
                                </div>
                            </div>

                            <!-- Nama -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="text" name="nama" class="form-control" placeholder=" " required>
                                    <label class="mm-label">Nama Lengkap *</label>
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="jenis_kelamin" class="form-control" required>
                                        <option value="" selected hidden></option>
                                        <option value="L">Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <label class="mm-label">Jenis Kelamin *</label>
                                </div>
                            </div>

                            <!-- Tempat Lahir -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="text" name="tempat_lahir" class="form-control" placeholder=" " required>
                                    <label class="mm-label">Tempat Lahir *</label>
                                </div>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="date" name="tgl_lahir" class="form-control" required>
                                    <label class="mm-label">Tanggal Lahir *</label>
                                </div>
                            </div>


                            <!-- Jabatan PTK (tambahkan setelah jabatan pangkat) -->
<div class="col-md-6 mb-3">
    <div class="mm-float">
        <select name="id_jabatan" class="form-control">
            <option value="" selected hidden></option>
            @foreach($ptkJabatans as $jabatan)
                <option value="{{ $jabatan->id_jabatan }}">
                    {{ $jabatan->nama_jabatan }}
                </option>
            @endforeach
        </select>
        <label class="mm-label">Jenis PTK *</label>
    </div>
</div>
                            <!-- Jenjang Jabatan -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="pangkat_jabatan_id" class="form-control" required>
                                        <option value="" selected hidden></option>
                                        @foreach($pangkatJabatans as $pangkatJabatan)
                                        <option value="{{ $pangkatJabatan->pangkat_jabatan_id }}">
                                            {{ $pangkatJabatan->jenjang_jabatan }}
                                            @if($pangkatJabatan->pangkat) - {{ $pangkatJabatan->pangkat }} @endif
                                            @if($pangkatJabatan->golongan_ruang) ({{ $pangkatJabatan->golongan_ruang }}) @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Jenjang Jabatan *</label>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="email" name="email" class="form-control" placeholder=" " required>
                                    <label class="mm-label">Email *</label>
                                </div>
                            </div>

                            <!-- No HP -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="text" name="no_hp" class="form-control" placeholder=" " required>
                                    <label class="mm-label">No. HP *</label>
                                </div>
                            </div>

                            <!-- Agama -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="agama" class="form-control" required>
                                        <option value="" selected hidden></option>
                                        @foreach($agamas as $agama)
                                        <option value="{{ $agama->nama_agama }}">
                                            {{ $agama->nama_agama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Agama *</label>
                                </div>
                            </div>

                            <!-- Kota -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="kota_id" id="kotaSelect" class="form-control" required>
                                        <option value="" selected hidden></option>
                                        @foreach($kotas as $kota)
                                        <option value="{{ $kota->kota_id }}">{{ $kota->nama_kota }}</option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Kota *</label>
                                </div>
                            </div>

                            <!-- SEKOLAH / INSTANSI -->
                            <div class="col-md-6 mb-3">

                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="sekolah_option" id="optionSekolah"
                                            value="sekolah" checked>
                                        <label class="form-check-label">Pilih Sekolah</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="sekolah_option" id="optionManual"
                                            value="manual">
                                        <label class="form-check-label">Input Manual</label>
                                    </div>
                                </div>

<!-- Dropdown sekolah -->
<div id="sekolahDropdownSection">
    <div class="mm-float input-group">
        <select name="sekolah_id" class="form-control" id="sekolahSelect">
            <option value="" selected hidden></option>
            @foreach($sekolahs as $sekolah)
                <option value="{{ $sekolah->sekolah_id }}"
                        data-nama="{{ $sekolah->nama_sekolah }}"
                        data-alamat="{{ $sekolah->alamat }}">
                    {{ $sekolah->nama_sekolah }}
                </option>
            @endforeach
        </select>
        <label class="mm-label">Sekolah *</label>

        <button class="btn btn-outline-primary" type="button" id="openSearchModalBtn">
            <i class="ri-search-line"></i>
        </button>
    </div>

    <div id="sekolahInfo" class="mt-2 p-2 bg-light rounded d-none">
        <small>
            <i class="ri-building-2-line me-1"></i>
            <span id="selectedSekolahName"></span><br>
            <i class="ri-map-pin-line me-1"></i>
            <span id="selectedSekolahAlamat"></span>
        </small>
    </div>
</div>


                                <!-- Input manual -->
                                <div id="instansiManualSection" class="d-none">
                                    <div class="mm-float">
                                        <input type="text" name="instansi" id="instansiInput" class="form-control" placeholder=" ">
                                        <label class="mm-label">Nama Instansi *</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat Kantor -->
                            <div class="col-12 mb-3">
                                <div class="mm-float">
                                    <textarea name="alamat_kantor" class="form-control" rows="2" placeholder=" "></textarea>
                                    <label class="mm-label">Alamat Kantor</label>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="ri-alert-line me-3"></i>
                                <div>
                                    <h6 class="mb-1">Perhatian!</h6>
                                    <p class="mb-0">
                                        Data bertanda * wajib diisi. Pastikan data benar.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="submit-register">
                        Simpan Data
                    </button>
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
                        <small class="text-muted">Masukkan minimal 2 karakter untuk pencarian</small>
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

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ============================================
        // 1. FUNGSI UTAMA
        // ============================================

        function togglePassword() {
            const input = document.getElementById('tokenInput');
            const icon = document.getElementById('passwordIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ri-eye-fill', 'ri-eye-off-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('ri-eye-off-fill', 'ri-eye-fill');
            }
        }

        document.getElementById('nip')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 18) value = value.substring(0, 18);
            e.target.value = value;
        });

        document.querySelector('input[name="no_hp"]')?.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        const showAlert = (type, title, message) => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        };

        // ============================================
        // 1.5 FLOATING LABEL STATE (ISI -> LABEL NAIK)
        // ============================================
        function initRegisterFloatingLabels() {
            document.querySelectorAll('#registerModal .mm-float').forEach(wrap => {
                const field = wrap.querySelector('input, select, textarea');
                if (!field) return;

                const sync = () => {
                    const v = (field.value ?? '').toString().trim();
                    wrap.classList.toggle('is-filled', v !== '');
                };

                sync();
                field.addEventListener('input', sync);
                field.addEventListener('change', sync);
            });
        }

        // ============================================
        // 2. FUNGSI SEKOLAH
        // ============================================

        document.querySelectorAll('input[name="sekolah_option"]').forEach(radio => {
            radio.addEventListener('change', function(e) {
                const sekolahDropdownSection = document.getElementById('sekolahDropdownSection');
                const instansiManualSection = document.getElementById('instansiManualSection');
                const sekolahSelect = document.getElementById('sekolahSelect');
                const instansiInput = document.getElementById('instansiInput');

                sekolahSelect.classList.remove('is-invalid');
                instansiInput.classList.remove('is-invalid');

                if (e.target.value === 'sekolah') {
                    sekolahDropdownSection.classList.remove('d-none');
                    instansiManualSection.classList.add('d-none');
                    instansiInput.value = '';
                } else {
                    sekolahDropdownSection.classList.add('d-none');
                    instansiManualSection.classList.remove('d-none');
                    sekolahSelect.value = '';
                    document.getElementById('sekolahInfo').classList.add('d-none');
                }

                initRegisterFloatingLabels();
            });
        });

        document.getElementById('sekolahSelect')?.addEventListener('change', function(e) {
            const sekolahId = e.target.value;
            const selectedOption = e.target.options[e.target.selectedIndex];
            const sekolahInfo = document.getElementById('sekolahInfo');
            const sekolahName = document.getElementById('selectedSekolahName');
            const sekolahAlamat = document.getElementById('selectedSekolahAlamat');

            this.classList.remove('is-invalid');

            if (!sekolahId) {
                sekolahInfo.classList.add('d-none');
            } else {
                const sekolahNama = selectedOption.getAttribute('data-nama');
                const sekolahAlamatText = selectedOption.getAttribute('data-alamat');

                sekolahName.textContent = sekolahNama;
                sekolahAlamat.textContent = sekolahAlamatText || 'Alamat tidak tersedia';
                sekolahInfo.classList.remove('d-none');

                document.getElementById('optionSekolah').checked = true;
                document.getElementById('optionSekolah').dispatchEvent(new Event('change'));
            }

            initRegisterFloatingLabels();
        });

        function handlePilihSekolah(sekolahId, sekolahNama, sekolahAlamat) {
            const sekolahOption = document.getElementById('optionSekolah');
            sekolahOption.checked = true;
            sekolahOption.dispatchEvent(new Event('change'));

            const sekolahSelect = document.getElementById('sekolahSelect');

            let optionExists = false;
            for (let i = 0; i < sekolahSelect.options.length; i++) {
                if (sekolahSelect.options[i].value === sekolahId) {
                    optionExists = true;
                    break;
                }
            }

            if (!optionExists) {
                const newOption = new Option(`${sekolahNama}`, sekolahId, true, true);
                newOption.setAttribute('data-nama', sekolahNama);
                newOption.setAttribute('data-alamat', sekolahAlamat || '');
                sekolahSelect.add(newOption);
            }

            sekolahSelect.value = sekolahId;

            document.getElementById('selectedSekolahName').textContent = sekolahNama;
            document.getElementById('selectedSekolahAlamat').textContent = sekolahAlamat || 'Alamat tidak tersedia';
            document.getElementById('sekolahInfo').classList.remove('d-none');

            sekolahSelect.dispatchEvent(new Event('change'));
            initRegisterFloatingLabels();
        }

        // ============================================
        // 3. MODAL SEARCH SEKOLAH
        // ============================================

        document.getElementById('openSearchModalBtn')?.addEventListener('click', function() {
            const searchModal = new bootstrap.Modal(document.getElementById('searchSekolahModal'));
            searchModal.show();
        });

        document.getElementById('modalSearchBtn')?.addEventListener('click', function() {
            const keyword = document.getElementById('modalSearchInput').value.trim();
            if (keyword.length >= 2) {
                searchSekolahModal(keyword);
            } else {
                showAlert('info', 'Pencarian', 'Masukkan minimal 2 karakter');
            }
        });

        document.getElementById('modalSearchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const keyword = this.value.trim();
                if (keyword.length >= 2) {
                    searchSekolahModal(keyword);
                } else {
                    showAlert('info', 'Pencarian', 'Masukkan minimal 2 karakter');
                }
            }
        });

        function searchSekolahModal(keyword) {
            const searchResults = document.getElementById('searchResults');
            const loadingIndicator = document.getElementById('modalSearchLoading');

            loadingIndicator.classList.remove('d-none');
            searchResults.innerHTML = '';

            fetch(`/api/search-sekolah?keyword=${encodeURIComponent(keyword)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loadingIndicator.classList.add('d-none');

                    if (data.success && data.data && data.data.length > 0) {
                        let resultsHTML = '';
                        data.data.forEach(sekolah => {
                            resultsHTML += `
                                <div class="card mb-2 sekolah-item">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">${sekolah.nama_sekolah}</h6>
                                        ${sekolah.npsn ? `<p class="card-text mb-1"><small><strong>NPSN:</strong> ${sekolah.npsn}</small></p>` : ''}
                                        ${sekolah.alamat ? `<p class="card-text mb-1"><small><i class="ri-map-pin-line"></i> ${sekolah.alamat}</small></p>` : ''}
                                        <button class="btn btn-sm btn-primary mt-2 pilih-sekolah-btn" 
                                                data-id="${sekolah.sekolah_id}"
                                                data-nama="${sekolah.nama_sekolah}"
                                                data-alamat="${sekolah.alamat || ''}">
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

        document.getElementById('searchResults')?.addEventListener('click', function(e) {
            if (e.target.classList.contains('pilih-sekolah-btn') || e.target.closest('.pilih-sekolah-btn')) {
                const button = e.target.classList.contains('pilih-sekolah-btn') ? e.target : e.target.closest('.pilih-sekolah-btn');
                const sekolahId = button.getAttribute('data-id');
                const sekolahNama = button.getAttribute('data-nama');
                const sekolahAlamat = button.getAttribute('data-alamat');

                handlePilihSekolah(sekolahId, sekolahNama, sekolahAlamat);

                const searchModal = bootstrap.Modal.getInstance(document.getElementById('searchSekolahModal'));
                searchModal.hide();

                document.getElementById('modalSearchInput').value = '';
            }
        });

        document.getElementById('searchSekolahModal')?.addEventListener('show.bs.modal', function() {
            document.getElementById('modalSearchInput').value = '';
            document.getElementById('searchResults').innerHTML = `
                <div class="text-center text-muted">
                    <i class="ri-search-line fs-4"></i>
                    <p class="mt-2">Masukkan kata kunci untuk mencari sekolah</p>
                </div>`;
        });

        // ============================================
        // 4. LOGIN FORM
        // ============================================

        document.getElementById('login-form')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const nip = document.getElementById('nip').value.trim();
            const token = document.getElementById('tokenInput').value.trim();
            const kegiatan_id = document.getElementById('kegiatan_id').value;

            if (nip.length !== 18) {
                showAlert('error', 'Kesalahan', 'NIP harus terdiri dari 18 digit angka!');
                return false;
            }

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i> MEMPROSES...';
            submitBtn.disabled = true;

            fetch("{{ route('lockscreen.authenticate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nip: nip,
                        token: token,
                        kegiatan_id: kegiatan_id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Login berhasil, mengarahkan ke sistem...',
                            showConfirmButton: false,
                            timer: 1500,
                            willClose: () => {
                                window.location.href = data.redirect_url;
                            }
                        });
                    } else {
                        if (data.show_register_modal) {
                            document.getElementById('modal-info-text').innerHTML =
                                `NIP <strong>${data.nip}</strong> belum terdaftar dalam sistem. Silakan lengkapi data diri Anda.`;

                            document.getElementById('reg_nip').value = data.nip;
                            document.getElementById('reg_kegiatan_id').value = data.kegiatan_id;
                            document.getElementById('reg_token').value = data.token;

                            const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                            registerModal.show();

                            setTimeout(() => {
                                document.getElementById('optionSekolah').checked = true;
                                document.getElementById('optionSekolah').dispatchEvent(new Event('change'));
                                document.getElementById('sekolahSelect').value = '';
                                document.getElementById('instansiInput').value = '';
                                document.getElementById('sekolahInfo').classList.add('d-none');
                                document.getElementById('kotaSelect').value = '';

                                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                                initRegisterFloatingLabels();
                            }, 80);
                        } else {
                            showAlert('error', 'Kesalahan', data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Kesalahan', 'Terjadi kesalahan pada server. Silakan coba lagi.');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        // ============================================
        // 5. REGISTER FORM
        // ============================================

        document.getElementById('submit-register')?.addEventListener('click', function() {
            const form = document.getElementById('register-form');
            const formData = new FormData(form);

            const requiredFields = ['nama', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'pangkat_jabatan_id', 'email', 'no_hp'];
            let isValid = true;
            let errorMessages = [];

            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            requiredFields.forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    let fieldName = field.replace('_', ' ');
                    fieldName = fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
                    errorMessages.push(`${fieldName} wajib diisi`);
                }
            });

            const kotaSelect = document.getElementById('kotaSelect');
            if (!kotaSelect.value) {
                isValid = false;
                errorMessages.push('Pilih kota');
                kotaSelect.classList.add('is-invalid');
            }

            const sekolahOption = document.querySelector('input[name="sekolah_option"]:checked');
            if (!sekolahOption) {
                isValid = false;
                errorMessages.push('Pilih opsi sekolah atau input manual');
            } else if (sekolahOption.value === 'sekolah') {
                const sekolahSelect = document.getElementById('sekolahSelect');
                if (!sekolahSelect.value) {
                    isValid = false;
                    errorMessages.push('Pilih sekolah dari daftar');
                    sekolahSelect.classList.add('is-invalid');
                } else {
                    formData.set('sekolah_id', sekolahSelect.value);
                    formData.delete('instansi');
                }
            } else if (sekolahOption.value === 'manual') {
                const instansiInput = document.getElementById('instansiInput');
                if (!instansiInput.value.trim()) {
                    isValid = false;
                    errorMessages.push('Isi nama instansi/lembaga');
                    instansiInput.classList.add('is-invalid');
                } else {
                    formData.set('instansi', instansiInput.value);
                    formData.delete('sekolah_id');
                }
            }

            const emailInput = form.querySelector('[name="email"]');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailInput.value.trim() && !emailRegex.test(emailInput.value.trim())) {
                isValid = false;
                emailInput.classList.add('is-invalid');
                errorMessages.push('Format email tidak valid');
            }

            const phoneInput = form.querySelector('[name="no_hp"]');
            const phoneValue = phoneInput.value.replace(/\D/g, '');
            if (phoneInput.value.trim() && phoneValue.length < 10) {
                isValid = false;
                phoneInput.classList.add('is-invalid');
                errorMessages.push('Nomor HP minimal 10 digit');
            }

            const dobInput = form.querySelector('[name="tgl_lahir"]');
            if (dobInput.value) {
                const dob = new Date(dobInput.value);
                const today = new Date();
                const minAgeDate = new Date(today.getFullYear() - 17, today.getMonth(), today.getDate());
                if (dob > minAgeDate) {
                    isValid = false;
                    dobInput.classList.add('is-invalid');
                    errorMessages.push('Minimal usia 17 tahun');
                }
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    html: 'Harap lengkapi data berikut:<br><br>' + errorMessages.join('<br>'),
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#2c7be5'
                });
                initRegisterFloatingLabels();
                return;
            }

            Swal.fire({
                title: 'Menyimpan Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('lockscreen.register') }}", {
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
                        const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                        registerModal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Registrasi Berhasil!',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2c7be5',
                            willClose: () => {
                                document.getElementById('nip').value = data.nip;
                                document.getElementById('nip').focus();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registrasi Gagal',
                            text: data.message || 'Terjadi kesalahan saat menyimpan data',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Server',
                        text: 'Terjadi kesalahan pada server',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#dc3545'
                    });
                })
                .finally(() => {
                    initRegisterFloatingLabels();
                });
        });

        document.querySelectorAll('#register-form input, #register-form select, #register-form textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) this.classList.remove('is-invalid');
            });
            input.addEventListener('change', function() {
                if (this.classList.contains('is-invalid')) this.classList.remove('is-invalid');
            });
        });

        // ============================================
        // 6. INITIALIZATION
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            const nipField = document.getElementById('nip');
            if (nipField) nipField.focus();
            initRegisterFloatingLabels();
        });

        document.getElementById('registerModal')?.addEventListener('shown.bs.modal', function() {
            initRegisterFloatingLabels();
        });
    </script>
</body>

</html>
