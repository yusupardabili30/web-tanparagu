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
    color: #888 !important;
}

.form-floating > label {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #888 !important;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select:focus ~ label,
.form-floating > .form-select:not([value=""]) ~ label {
    font-size: 14px !important;
    transform: translateY(-14px) scale(.9) !important;
    opacity: .85;
    color: #6e6e6e !important;
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

.content-wrapper { padding-top: 5px !important; }

/* ============================================
LITEPICKER
============================================ */
.litepicker {
    border-radius: 18px !important;
    padding: 20px !important;
    border: 1px solid #d4ddff !important;
    background: #ffffff !important;
    box-shadow: 0 12px 30px rgba(0,40,120,0.12) !important;
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

    .form-floating > label {
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
        style="width:180px; height:auto; margin-left:-30px; margin-top:-40px;">

    <!-- TEXT -->
    <div class="header-info" style="line-height:1.3; margin-top:-40px;">
        <h4 class="fw-bold mb-1" style="color:#1a3f6b; font-size:26px;">
            Pelatihan Pembelajaran Mendalam (PM) Provinsi Banten Batch 1
        </h4>

        <div class="text-muted" style="font-size:16px;">
            <div><i class="ri-calendar-line me-1"></i> 12 – 14 Februari 2025</div>
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

        <form id="profilForm">

<!-- ===================== ROW 1 ===================== -->
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>Nama Lengkap</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>NIK</label>
        </div>
    </div>
</div>

<!-- ===================== ROW 2 ===================== -->
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>NIP / NI PPPK</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>Jabatan</label>
        </div>
    </div>
</div>

<!-- ===================== AGAMA + JK ===================== -->
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <select class="form-select" required style="font-size:16px; color:#6e6e6e !important;">
                <option value="">Pilih Agama</option>
                <option>Islam</option>
                <option>Kristen</option>
                <option>Katolik</option>
                <option>Hindu</option>
                <option>Buddha</option>
                <option>Konghucu</option>
            </select>
            <label>Agama</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-2">
            <select class="form-select" required style="font-size:16px; color:#6e6e6e !important;">
                <option value="">Pilih Jenis Kelamin</option>
                <option>Laki-laki</option>
                <option>Perempuan</option>
            </select>
            <label>Jenis Kelamin</label>
        </div>
    </div>
</div>

<!-- ===================== TTL ===================== -->
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>Tempat Lahir</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input id="tanggal_lahir" required class="form-control form-control-lg" placeholder=" ">
            <label>Tanggal Lahir</label>
        </div>
    </div>
</div>

<input type="hidden" id="umur">

<!-- ===================== PENDIDIKAN ===================== -->
<div class="form-floating mb-2">
    <input required class="form-control form-control-lg" placeholder=" ">
    <label>Pendidikan Terakhir</label>
</div>

<!-- ===================== UNIT KERJA ===================== -->
<div class="big-box mt-3">
    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
        <i class="ri-school-line me-1 text-primary"></i> Informasi Unit Kerja
    </h5>

<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <select class="form-select" required>
                <option value="">Pilih Sekolah</option>
                <option>SD Negeri 1</option>
                <option>SD Negeri 2</option>
                <option>SMP Negeri 1</option>
                <option>SMP Negeri 2</option>
                <option>SMA Negeri 1</option>
                <option>SMA Negeri 2</option>
                <option>SMK Negeri 1</option>
                <option>SMK Negeri 2</option>
            </select>
            <label>Nama Sekolah</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>Unit Kerja</label>
        </div>
    </div>
</div>

<div class="form-floating mb-2">
    <textarea class="form-control form-control-lg" placeholder=" " style="height:110px;"></textarea>
    <label>Alamat Unit Kerja</label>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input class="form-control form-control-lg" placeholder=" ">
            <label>Kabupaten/Kota</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input class="form-control form-control-lg" placeholder=" ">
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
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>Website / Email</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder=" ">
            <label>No. Telp / HP</label>
        </div>
    </div>
</div>

<div class="form-floating mb-2">
    <input type="email" required class="form-control form-control-lg" placeholder=" ">
    <label>Email</label>
</div>

<div class="form-floating mb-2">
    <input required class="form-control form-control-lg" placeholder=" ">
    <label>NPWP</label>
</div>

<!-- ===================== BANK ===================== -->
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-floating mb-2">
            <select class="form-select" required>
                <option value="">Pilih Bank</option>
                <option>Bank BRI</option>
                <option>Bank BCA</option>
                <option>Bank BNI</option>
                <option>Bank Mandiri</option>
            </select>
            <label>Nama Bank</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder="">
            <label>No. Rekening</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-floating mb-2">
            <input required class="form-control form-control-lg" placeholder="">
            <label>Atas Nama Rekening</label>
        </div>
    </div>
</div>

</div>

<button type="button" onclick="validateForm()" class="btn btn-primary btn-lg mt-3">
    <i class="ri-check-double-line me-2"></i> Simpan Data
</button>

</form>
</div>

</div>
</div>
</div>
</div>

<!-- ===================== MODAL SUKSES ===================== -->
<div class="modal fade" id="modalSukses" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:18px;">
      <div class="modal-body text-center p-4">
        <i class="ri-checkbox-circle-line text-success" style="font-size:60px;"></i>
        <h4 class="mt-3 fw-bold" style="color:#1a3f6b;">Data Berhasil Disimpan</h4>
        <p class="text-muted mt-2">Semua data sudah tersimpan dengan aman.</p>
        <button class="btn btn-primary mt-3 px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- ===================== MODAL GAGAL ===================== -->
<div class="modal fade" id="modalGagal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:18px;">
      <div class="modal-body text-center p-4">
        <i class="ri-error-warning-line text-danger" style="font-size:60px;"></i>
        <h4 class="mt-3 fw-bold text-danger">Form Belum Lengkap</h4>
        <p class="text-muted mt-2">Mohon lengkapi semua kolom wajib terlebih dahulu.</p>
        <button class="btn btn-danger mt-3 px-4" data-bs-dismiss="modal">Oke</button>
      </div>
    </div>
  </div>
</div>

@endsection


@section('sipproja-js')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

<script>
const picker = new Litepicker({
    element: document.getElementById('tanggal_lahir'),
    format: 'DD/MM/YYYY',
    autoApply: true
});

function validateForm() {
    let q = document.querySelectorAll("[required]");
    let valid = true;

    q.forEach(f => {
        if (!f.value.trim()) {
            f.classList.add("is-invalid");
            valid = false;
        } else {
            f.classList.remove("is-invalid");
        }
    });

    if (!valid) {
        new bootstrap.Modal(document.getElementById('modalGagal')).show();
        return;
    }

    new bootstrap.Modal(document.getElementById('modalSukses')).show();
}
</script>

@endsection
