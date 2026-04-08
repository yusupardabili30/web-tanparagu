<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biodata - {{ $ptk->nama }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- CSS Login (untuk style yang sama) -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <style>
        :root {
            --mm-primary: #1a4d8e;
            --mm-primary-hover: #163f74;
            --mm-primary-focus: rgba(26, 75, 184, 0.25);
            --mm-font: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: var(--mm-font) !important;
            background: #f0f2f5;
        }

        .edit-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--mm-primary) 0%, #2563eb 100%);
            color: white;
            padding: 20px 30px;
            border-bottom: none;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 700;
        }

        .card-header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--mm-primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-section-title i {
            margin-right: 8px;
        }

        /* =======================================================
           FLOATING LABEL STYLE (SAMA DENGAN MODAL LOCKSCREEN)
        ======================================================= */
        .mm-float {
            position: relative;
        }

        .mm-label {
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
            font-family: var(--mm-font) !important;
        }

        .mm-float .form-control,
        .mm-float .form-select,
        .mm-float textarea {
            position: relative !important;
            z-index: 1 !important;
            padding-top: 28px !important;
            padding-bottom: 8px !important;
            background: #ffffff !important;
            border: 1px solid #d7e2ff !important;
            border-radius: 12px !important;
            height: 58px !important;
            font-size: 15px !important;
            color: #555555 !important;
            font-weight: 600 !important;
            font-family: var(--mm-font) !important;
        }

        .mm-float textarea.form-control {
            height: auto !important;
            padding-top: 28px !important;
        }

        .mm-float .form-control:focus,
        .mm-float .form-select:focus,
        .mm-float textarea:focus {
            border-color: var(--mm-primary) !important;
            box-shadow: 0 0 0 2px rgba(26, 75, 184, .20) !important;
        }

        /* fokus / isi -> label naik */
        .mm-float:focus-within .mm-label,
        .mm-float.is-filled .mm-label {
            top: 6px;
            font-size: 12px;
            color: var(--mm-primary);
            background: #fff;
        }

        /* input date specific */
        .mm-float input[type="date"] {
            padding-top: 30px !important;
        }

        .mm-float input[type="date"]::-webkit-datetime-edit {
            color: #555555 !important;
        }

        .mm-float input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 1 !important;
            cursor: pointer;
        }

        /* select khusus */
        .mm-float select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        /* disabled/readonly style */
        .mm-float .form-control[readonly],
        .mm-float .form-control:disabled {
            background-color: #e9ecef !important;
            cursor: not-allowed;
        }

        /* Radio button style */
        .form-check-inline {
            margin-right: 20px;
        }

        .form-check-input:checked {
            background-color: var(--mm-primary);
            border-color: var(--mm-primary);
        }

        .form-check-label {
            color: #1a3f6b !important;
            font-weight: 600 !important;
        }

        /* Sekolah info box */
        .sekolah-info {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 12px;
            margin-top: 10px;
        }

        /* Buttons */
        .btn-save {
            background: var(--mm-primary);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-save:hover {
            background: var(--mm-primary-hover);
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
        }

        .btn-outline-primary {
            border-color: var(--mm-primary) !important;
            color: var(--mm-primary) !important;
            background: transparent !important;
            border-radius: 12px !important;
        }

        .btn-outline-primary:hover {
            background: var(--mm-primary) !important;
            border-color: var(--mm-primary) !important;
            color: #fff !important;
        }

        /* Input group untuk search sekolah */
        .input-group .mm-float {
            flex: 1 1 auto;
            width: 1%;
        }

        .input-group .mm-float .form-control,
        .input-group .mm-float .form-select {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        #openSearchModalBtn {
            height: 58px !important;
            border-radius: 0 12px 12px 0 !important;
            border: 1px solid #d7e2ff !important;
            border-left: none !important;
            padding: 0 18px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }

        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
        }

        /* Alert info */
        .alert-info {
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            border-radius: 12px;
        }

        /* Small text */
        .text-muted small {
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0">Menyimpan data...</p>
        </div>
    </div>

    <div class="edit-container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><i class="ri-edit-line me-2"></i> Edit Biodata PTK</h3>
                        <p>Perbarui data diri Anda. Perubahan akan disimpan ke database Sistem dan Dapodik.</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                            <i class="ri-id-card-line me-1"></i> NIP: <strong>{{ $ptk->nip }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form id="edit-form">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan_id }}">
                    <input type="hidden" name="encode_kegiatan_id" value="{{ $encode_kegiatan_id }}">
                    <input type="hidden" name="nip" value="{{ $ptk->nip }}">

<!-- Data Pribadi -->
<div class="form-section">
    <div class="form-section-title">
        <i class="ri-user-line"></i> Data Pribadi
    </div>
    <div class="row">
        <!-- NIK (Readonly - TIDAK BISA DIEDIT) -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <input type="text"
                       name="nik_readonly"
                       class="form-control"
                       value="{{ old('nik', $ptk->nik) }}"
                       readonly
                       disabled>
                <label class="mm-label">NIK *</label>
            </div>
            <small class="text-muted" style="font-size: 11px; margin-left: 14px;">NIK tidak dapat diubah</small>
            <!-- Hidden field untuk tetap mengirim NIK ke server -->
            <input type="hidden" name="nik" value="{{ old('nik', $ptk->nik) }}">
        </div>

        <!-- NIP (Bisa diedit) -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <input type="text"
                       name="nip"
                       class="form-control"
                       value="{{ old('nip', $ptk->nip) }}"
                       required
                       maxlength="18">
                <label class="mm-label">NIP *</label>
            </div>
        </div>

        <!-- Nama -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $ptk->nama) }}" required>
                <label class="mm-label">Nama Lengkap *</label>
            </div>
        </div>

        <!-- NUPTK -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $ptk->nuptk) }}" maxlength="16">
                <label class="mm-label">16 digit NUPTK</label>
            </div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="" selected hidden></option>
                    <option value="L" {{ $ptk->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                    <option value="P" {{ $ptk->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <label class="mm-label">Jenis Kelamin *</label>
            </div>
        </div>

        <!-- Tempat Lahir -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $ptk->tempat_lahir) }}" required>
                <label class="mm-label">Tempat Lahir *</label>
            </div>
        </div>

        <!-- Tanggal Lahir -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $ptk->tgl_lahir ? date('Y-m-d', strtotime($ptk->tgl_lahir)) : '') }}" required>
                <label class="mm-label">Tanggal Lahir *</label>
            </div>
        </div>

        <!-- Agama -->
        <div class="col-md-6 mb-3">
            <div class="mm-float">
                <select name="agama" class="form-control" required>
                    <option value="" selected hidden></option>
                    @foreach($agamas as $agama)
                        <option value="{{ $agama->nama_agama }}" {{ $ptk->agama == $agama->nama_agama ? 'selected' : '' }}>
                            {{ $agama->nama_agama }}
                        </option>
                    @endforeach
                </select>
                <label class="mm-label">Agama *</label>
            </div>
        </div>
    </div>
</div>
                    <!-- Data Kepegawaian -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="ri-briefcase-line"></i> Data Kepegawaian
                        </div>
                        <div class="row">
                            <!-- Jenis PTK -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="jenis_ptk_id" class="form-control" required>
                                        <option value="" selected hidden></option>
                                        @foreach($jenisPtk as $jenis)
                                            <option value="{{ $jenis->jenis_ptk_id }}" {{ $ptk->jenis_ptk_id == $jenis->jenis_ptk_id ? 'selected' : '' }}>
                                                {{ $jenis->jenis_ptk }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Jenis PTK *</label>
                                </div>
                            </div>

                            <!-- Pangkat Golongan -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="pangkat_golongan_id" class="form-control">
                                        <option value="" selected hidden></option>
                                        @foreach($pangkatGolongans as $golongan)
                                            <option value="{{ $golongan->pangkat_golongan_id }}" {{ $ptk->pangkat_golongan_id == $golongan->pangkat_golongan_id ? 'selected' : '' }}>
                                                {{ $golongan->pangkat }} ({{ $golongan->golongan }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Pangkat/Golongan</label>
                                </div>
                            </div>

                            <!-- Jenjang Jabatan -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="pangkat_jabatan_id" class="form-control" required>
                                        <option value="" selected hidden></option>
                                        @foreach($pangkatJabatans as $pangkatJabatan)
                                            <option value="{{ $pangkatJabatan->pangkat_jabatan_id }}" {{ $ptk->pangkat_jabatan_id == $pangkatJabatan->pangkat_jabatan_id ? 'selected' : '' }}>
                                                {{ $pangkatJabatan->jenjang_jabatan }}
                                                @if($pangkatJabatan->pangkat) - {{ $pangkatJabatan->pangkat }} @endif
                                                @if($pangkatJabatan->golongan_ruang) ({{ $pangkatJabatan->golongan_ruang }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Jenjang Jabatan *</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Kontak -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="ri-mail-line"></i> Data Kontak
                        </div>
                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $ptk->email) }}" required>
                                    <label class="mm-label">Email *</label>
                                </div>
                            </div>

                            <!-- No HP -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $ptk->no_hp) }}" required>
                                    <label class="mm-label">No. HP *</label>
                                </div>
                            </div>

                            <!-- Kota -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="kota_id" class="form-control">
                                        <option value="" selected hidden></option>
                                        @foreach($kotas as $kota)
                                            <option value="{{ $kota->kota_id }}" {{ $ptk->kota_id == $kota->kota_id ? 'selected' : '' }}>
                                                {{ $kota->nama_kota }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Kota</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Instansi -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="ri-building-line"></i> Data Instansi
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="mm-float">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sekolah_option" id="optionSekolah" value="sekolah" {{ $ptk->sekolah_id ? 'checked' : '' }}>
                                        <label class="form-check-label">Pilih Sekolah</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sekolah_option" id="optionManual" value="manual" {{ !$ptk->sekolah_id ? 'checked' : '' }}>
                                        <label class="form-check-label">Input Manual</label>
                                    </div>
                                </div>
                            </div>

                           <!-- Dropdown sekolah -->
<div id="sekolahDropdownSection" class="col-12 mb-3" style="{{ $ptk->sekolah_id ? 'display: block;' : 'display: none;' }}">
    <div class="input-group">
        <div class="mm-float" style="flex: 1;">
            <select name="sekolah_id" id="sekolahSelect" class="form-control">
                <option value="" selected hidden></option>
                @foreach($sekolahs as $sekolah)
                    <option value="{{ $sekolah->sekolah_id }}"
                        data-nama="{{ $sekolah->nama_sekolah }}"
                        data-alamat="{{ $sekolah->alamat }}"
                        {{ $ptk->sekolah_id == $sekolah->sekolah_id ? 'selected' : '' }}>
                        {{ $sekolah->nama_sekolah }}
                    </option>
                @endforeach
            </select>
            <label class="mm-label">Sekolah *</label>
        </div>
        <button class="btn btn-outline-primary" type="button" id="openSearchModalBtn">
            <i class="ri-search-line"></i>
        </button>
    </div>
    <div id="sekolahInfo" class="sekolah-info mt-2 {{ $ptk->sekolah_id ? '' : 'd-none' }}">
        <small>
            <i class="ri-building-2-line me-1"></i>
            <span id="selectedSekolahName">{{ $ptk->sekolah?->nama_sekolah ?? '' }}</span><br>
            <i class="ri-map-pin-line me-1"></i>
            <span id="selectedSekolahAlamat">{{ $ptk->sekolah?->alamat ?? '' }}</span>
        </small>
    </div>
</div>

<!-- Input manual -->
<div id="instansiManualSection" class="col-12 mb-3" style="{{ !$ptk->sekolah_id ? 'display: block;' : 'display: none;' }}">
    <div class="mm-float">
        <input type="text" name="instansi" id="instansiInput" class="form-control" value="{{ old('instansi', $ptk->instansi) }}">
        <label class="mm-label">Nama Instansi *</label>
    </div>
</div>

                            <!-- Jenjang Pendidikan -->
                            <div class="col-md-6 mb-3">
                                <div class="mm-float">
                                    <select name="jenjang_pendidikan_id" class="form-control" required>
                                        <option value="" selected hidden></option>
                                        @foreach($jenjangs as $jenjang)
                                            <option value="{{ $jenjang->jenjang_pendidikan_id }}" {{ $ptk->jenjang_pendidikan_id == $jenjang->jenjang_pendidikan_id ? 'selected' : '' }}>
                                                {{ $jenjang->jenjang_pendidikan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="mm-label">Jenjang Satuan Pendidikan *</label>
                                </div>
                            </div>

                            <!-- Alamat Kantor -->
                            <div class="col-12 mb-3">
                                <div class="mm-float">
                                    <textarea name="alamat_kantor" class="form-control" rows="2">{{ old('alamat_kantor', $ptk->alamat_kantor) }}</textarea>
                                    <label class="mm-label">Alamat Kantor</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-start">
                            <i class="ri-information-line me-3 mt-1"></i>
                            <div>
                                <strong>Informasi Penting!</strong><br>
                                <small>
                                    Perubahan data akan disimpan ke database Sistem.<br>
                                    Pastikan data yang diisi sudah benar.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-cancel" onclick="window.history.back()">
                            <i class="ri-arrow-left-line me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-save text-white" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Search Sekolah -->
    <div class="modal fade" id="searchSekolahModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cari Sekolah dari Dapodik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="modalSearchInput" class="form-control" placeholder="Masukkan nama sekolah, NPSN, atau alamat...">
                            <button class="btn btn-primary" type="button" id="modalSearchBtn">
                                <i class="ri-search-line"></i> Cari
                            </button>
                        </div>
                        <small class="text-muted">Masukkan minimal 2 karakter untuk pencarian</small>
                    </div>
                    <div id="modalSearchLoading" class="d-none text-center my-3">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Mencari sekolah...</p>
                    </div>
                    <div id="searchResults" class="mt-3" style="max-height: 300px; overflow-y: auto;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 & Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ============================================
        // FLOATING LABEL STATE (ISI -> LABEL NAIK)
        // ============================================
        function initFloatingLabels() {
            document.querySelectorAll('.mm-float').forEach(wrap => {
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
        // SEKOLAH OPTION TOGGLE
        // ============================================
        document.querySelectorAll('input[name="sekolah_option"]').forEach(radio => {
            radio.addEventListener('change', function(e) {
                const sekolahDropdown = document.getElementById('sekolahDropdownSection');
                const instansiManual = document.getElementById('instansiManualSection');
                const sekolahSelect = document.getElementById('sekolahSelect');
                const instansiInput = document.getElementById('instansiInput');

                if (e.target.value === 'sekolah') {
                    sekolahDropdown.style.display = 'block';
                    instansiManual.style.display = 'none';
                    instansiInput.value = '';
                    instansiInput.removeAttribute('required');
                    sekolahSelect.setAttribute('required', 'required');
                } else {
                    sekolahDropdown.style.display = 'none';
                    instansiManual.style.display = 'block';
                    sekolahSelect.value = '';
                    sekolahSelect.removeAttribute('required');
                    instansiInput.setAttribute('required', 'required');
                    document.getElementById('sekolahInfo').classList.add('d-none');
                }
                initFloatingLabels();
            });
        });

        // Update sekolah info when select changes
        document.getElementById('sekolahSelect')?.addEventListener('change', function(e) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const sekolahInfo = document.getElementById('sekolahInfo');
            const sekolahName = document.getElementById('selectedSekolahName');
            const sekolahAlamat = document.getElementById('selectedSekolahAlamat');

            if (!e.target.value) {
                sekolahInfo.classList.add('d-none');
            } else {
                const nama = selectedOption.getAttribute('data-nama');
                const alamat = selectedOption.getAttribute('data-alamat');
                sekolahName.textContent = nama;
                sekolahAlamat.textContent = alamat || 'Alamat tidak tersedia';
                sekolahInfo.classList.remove('d-none');
                document.getElementById('optionSekolah').checked = true;
                document.getElementById('optionSekolah').dispatchEvent(new Event('change'));
            }
            initFloatingLabels();
        });

// ============================================
// SEARCH SEKOLAH MODAL - FIXED VERSION
// ============================================

// Hapus semua event listener lama dengan cara replace element
const openModalBtn = document.getElementById('openSearchModalBtn');
if (openModalBtn) {
    const newOpenBtn = openModalBtn.cloneNode(true);
    openModalBtn.parentNode.replaceChild(newOpenBtn, openModalBtn);

    newOpenBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Opening search modal');

        // Reset modal
        document.getElementById('modalSearchInput').value = '';
        document.getElementById('searchResults').innerHTML = '<div class="text-center text-muted">Masukkan kata kunci untuk mencari sekolah</div>';
        document.getElementById('modalSearchLoading').classList.add('d-none');

        const modal = new bootstrap.Modal(document.getElementById('searchSekolahModal'));
        modal.show();
    });
}

// Fix untuk button search di modal
const searchBtn = document.getElementById('modalSearchBtn');
if (searchBtn) {
    const newSearchBtn = searchBtn.cloneNode(true);
    searchBtn.parentNode.replaceChild(newSearchBtn, searchBtn);

    newSearchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const keyword = document.getElementById('modalSearchInput').value.trim();
        console.log('Search button clicked, keyword:', keyword);

        if (keyword.length >= 2) {
            searchSekolahModal(keyword);
        } else {
            Swal.fire('Info', 'Masukkan minimal 2 karakter untuk pencarian', 'info');
        }
    });
}

// Fix untuk input enter
const searchInput = document.getElementById('modalSearchInput');
if (searchInput) {
    const newSearchInput = searchInput.cloneNode(true);
    searchInput.parentNode.replaceChild(newSearchInput, searchInput);

    newSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const keyword = this.value.trim();
            console.log('Enter pressed, keyword:', keyword);

            if (keyword.length >= 2) {
                searchSekolahModal(keyword);
            } else {
                Swal.fire('Info', 'Masukkan minimal 2 karakter untuk pencarian', 'info');
            }
        }
    });
}

// Fungsi search
function searchSekolahModal(keyword) {
    const searchResults = document.getElementById('searchResults');
    const loadingIndicator = document.getElementById('modalSearchLoading');

    loadingIndicator.classList.remove('d-none');
    searchResults.innerHTML = '<div class="text-center text-muted">Sedang mencari data...</div>';

    // Gunakan route lockscreen yang sudah terbukti berfungsi
    const url = `/lockscreen/api/search-sekolah-dapodik?keyword=${encodeURIComponent(keyword)}`;
    console.log('Fetching URL:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        loadingIndicator.classList.add('d-none');
        console.log('Response data:', data);

        if (data.success && data.data && data.data.length > 0) {
            let resultsHTML = '<div class="list-group">';
            data.data.forEach(sekolah => {
                resultsHTML += `
                    <div class="list-group-item sekolah-item mb-2" style="border-radius: 8px;">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <h6 class="mb-1">${escapeHtml(sekolah.nama_sekolah || 'Nama tidak tersedia')}</h6>
                            <span class="badge bg-info ms-2">Dapodik</span>
                        </div>
                        ${sekolah.npsn ? `<p class="mb-1 small mt-2"><strong>NPSN:</strong> ${escapeHtml(sekolah.npsn)}</p>` : ''}
                        ${sekolah.alamat ? `<p class="mb-1 small"><i class="ri-map-pin-line"></i> ${escapeHtml(sekolah.alamat)}</p>` : ''}
                        ${sekolah.kab_kota ? `<p class="mb-1 small"><i class="ri-building-line"></i> ${escapeHtml(sekolah.kab_kota)}</p>` : ''}
                        <button class="btn btn-sm btn-primary mt-2 pilih-sekolah-btn"
                                data-id="${sekolah.sekolah_id || ''}"
                                data-nama="${escapeHtml(sekolah.nama_sekolah || '')}"
                                data-alamat="${escapeHtml(sekolah.alamat || '')}">
                            <i class="ri-check-line"></i> Pilih Sekolah Ini
                        </button>
                    </div>`;
            });
            resultsHTML += '</div>';
            searchResults.innerHTML = resultsHTML;
        } else {
            searchResults.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="ri-school-line fs-1"></i>
                    <p class="mt-2">Sekolah dengan keyword "${escapeHtml(keyword)}" tidak ditemukan</p>
                    <small class="text-muted">Coba dengan kata kunci lain</small>
                </div>`;
        }
    })
    .catch(error => {
        console.error('Error detail:', error);
        loadingIndicator.classList.add('d-none');
        searchResults.innerHTML = `
            <div class="text-center text-danger py-4">
                <i class="ri-error-warning-line fs-1"></i>
                <p class="mt-2">Gagal mencari sekolah</p>
                <small class="text-muted">Error: ${error.message}</small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-outline-primary" onclick="retrySearch()">
                        <i class="ri-refresh-line"></i> Coba Lagi
                    </button>
                </div>
            </div>`;
    });
}

// Fungsi retry
window.retrySearch = function() {
    const keyword = document.getElementById('modalSearchInput').value.trim();
    if (keyword && keyword.length >= 2) {
        searchSekolahModal(keyword);
    }
};

// ============================================
// HELPER FUNCTIONS
// ============================================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showAlert(type, title, message) {
    Swal.fire({
        icon: type,
        title: title,
        text: message,
        confirmButtonColor: '#1a4d8e'
    });
}

// Pilih sekolah dari modal (event delegation)
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.pilih-sekolah-btn');
    if (btn) {
        e.preventDefault();
        const sekolahId = btn.getAttribute('data-id');
        const sekolahNama = btn.getAttribute('data-nama');
        const sekolahAlamat = btn.getAttribute('data-alamat');

        console.log('Pilih sekolah clicked:', { sekolahId, sekolahNama });

        if (sekolahId && sekolahNama) {
            // Pilih radio option sekolah
            const optionSekolah = document.getElementById('optionSekolah');
            if (optionSekolah) {
                optionSekolah.checked = true;
                optionSekolah.dispatchEvent(new Event('change'));
            }

            const sekolahSelect = document.getElementById('sekolahSelect');

            // Cek apakah option sudah ada
            let optionExists = false;
            for (let i = 0; i < sekolahSelect.options.length; i++) {
                if (sekolahSelect.options[i].value == sekolahId) {
                    optionExists = true;
                    sekolahSelect.selectedIndex = i;
                    break;
                }
            }

            if (!optionExists) {
                const newOption = new Option(sekolahNama, sekolahId, true, true);
                newOption.setAttribute('data-nama', sekolahNama);
                newOption.setAttribute('data-alamat', sekolahAlamat || '');
                sekolahSelect.add(newOption);
                sekolahSelect.value = sekolahId;
            }

            // Update info sekolah
            document.getElementById('selectedSekolahName').textContent = sekolahNama;
            document.getElementById('selectedSekolahAlamat').textContent = sekolahAlamat || 'Alamat tidak tersedia';
            document.getElementById('sekolahInfo').classList.remove('d-none');

            // Trigger change event
            sekolahSelect.dispatchEvent(new Event('change'));

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('searchSekolahModal'));
            if (modal) {
                modal.hide();
            }

            initFloatingLabels();
        }
    }
});
        // ============================================
        // FORM SUBMIT
        // ============================================
        document.getElementById('edit-form')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const formData = new FormData(this);

            // Validation
            let isValid = true;
            const requiredFields = ['nama', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'jenis_ptk_id', 'pangkat_jabatan_id', 'email', 'no_hp', 'agama', 'jenjang_pendidikan_id'];

            requiredFields.forEach(field => {
                const input = this.querySelector(`[name="${field}"]`);
                if (input && !input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else if (input) {
                    input.classList.remove('is-invalid');
                }
            });

            const sekolahOption = document.querySelector('input[name="sekolah_option"]:checked')?.value;
            if (sekolahOption === 'sekolah') {
                const sekolahSelect = document.getElementById('sekolahSelect');
                if (!sekolahSelect.value) {
                    isValid = false;
                    sekolahSelect.classList.add('is-invalid');
                    Swal.fire('Error', 'Pilih sekolah terlebih dahulu', 'error');
                }
            } else if (sekolahOption === 'manual') {
                const instansiInput = document.getElementById('instansiInput');
                if (!instansiInput.value.trim()) {
                    isValid = false;
                    instansiInput.classList.add('is-invalid');
                    Swal.fire('Error', 'Isi nama instansi', 'error');
                }
            }

            if (!isValid) return;

            loadingOverlay.style.display = 'flex';
            submitBtn.disabled = true;

            const updateUrl = "{{ route('ptk.edit.update', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $ptk->nip]) }}";

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loadingOverlay.style.display = 'none';
                submitBtn.disabled = false;

                if (data.success) {
                    let message = data.message;
                    if (data.api_sync && !data.api_sync.success) {
                        message += '<br><small class="text-warning">⚠️ ' + data.api_sync.message + '</small>';
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: message,
                        confirmButtonColor: '#1a4d8e'
                    }).then(() => {
                        window.location.href = "{{ route('ptk.show', ['encode_kegiatan_id' => $encode_kegiatan_id, 'nip' => $ptk->nip]) }}";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#1a4d8e'
                    });
                }
            })
            .catch(error => {
                loadingOverlay.style.display = 'none';
                submitBtn.disabled = false;
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan pada server',
                    confirmButtonColor: '#1a4d8e'
                });
            });
        });

        // Remove invalid class on input
        document.querySelectorAll('#edit-form input, #edit-form select, #edit-form textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });

        // Initialize floating labels
       document.addEventListener('DOMContentLoaded', function() {
    // Init floating labels
    initFloatingLabels();

    // ✅ FIX 1: Trigger sekolah info box jika sekolah_id sudah ada
    const sekolahSelect = document.getElementById('sekolahSelect');
    if (sekolahSelect && sekolahSelect.value) {
        const selectedOption = sekolahSelect.options[sekolahSelect.selectedIndex];
        const nama = selectedOption.getAttribute('data-nama');
        const alamat = selectedOption.getAttribute('data-alamat');

        document.getElementById('selectedSekolahName').textContent = nama || '';
        document.getElementById('selectedSekolahAlamat').textContent = alamat || 'Alamat tidak tersedia';
        document.getElementById('sekolahInfo').classList.remove('d-none');
    }

    // ✅ FIX 2: Force semua mm-float yang punya select dengan value ter-selected
    document.querySelectorAll('.mm-float select').forEach(select => {
        if (select.value && select.value !== '') {
            select.closest('.mm-float').classList.add('is-filled');
        }
    });

    // ✅ FIX 3: Force semua mm-float yang punya input dengan value
    document.querySelectorAll('.mm-float input, .mm-float textarea').forEach(field => {
        if ((field.value ?? '').toString().trim() !== '') {
            field.closest('.mm-float').classList.add('is-filled');
        }
    });
});
    </script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('sekolahSelect');
    console.log('sekolahSelect.value:', sel?.value);
    console.log('sekolah_id dari PTK (PHP):', '{{ $ptk->sekolah_id }}');
    console.log('Jumlah option:', sel?.options.length);

    // Cek apakah option dengan value sekolah_id ada
    let found = false;
    for (let i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value == '{{ $ptk->sekolah_id }}') {
            found = true;
            console.log('Option ditemukan di index:', i, sel.options[i].text);
        }
    }
    console.log('Option sekolah PTK ada di list:', found);
});
</script>
</body>

</html>
