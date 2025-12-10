@extends('layouts.main-user')

@section('mycontent')

<style>
    /* Warna placeholder jadi biru Baduy */
.form-control::placeholder,
.form-select::placeholder,
textarea::placeholder {
    color: #1a3f6b !important;       /* biru baduy */
    opacity: 1 !important;
    font-weight: 500 !important;
}

/* Warna teks input */
.form-control,
textarea.form-control {
    color: #1a3f6b !important;
    font-weight: 600 !important;
}

/* Warna teks dalam select */
.form-select {
    color: #1a3f6b !important;
    font-weight: 600 !important;
}

/* Saat user ngeklik select, tetap warna biru */
.form-select option {
    color: #1a3f6b !important;
}

/* Sembunyikan header utama layout, TAPI JANGAN card-header */
.navbar,
.topbar,
footer,
#header:not(.card-header),
header:not(.card-header) {
    display: none !important;
}

.content-wrapper { padding-top: 5px !important; }
.page-title-box { margin-top: -100px !important; }

/* ============================================
   KALENDER SUPER PREMIUM ELEGAN
   ============================================ */

.litepicker {
    border-radius: 18px !important;
    padding: 20px !important;
    border: 1px solid #d4ddff !important;
    background: #ffffff !important;
    box-shadow: 0 12px 30px rgba(0,40,120,0.12) !important;
}

/* header bulan */
.litepicker .month-item-header {
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #1a3f6b !important;
}

/* arrow */
.litepicker .button-previous,
.litepicker .button-next {
    background: #ebf0ff !important;
    border-radius: 10px !important;
    padding: 6px 10px !important;
    transition: 0.2s ease-in-out;
}
.litepicker .button-previous:hover,
.litepicker .button-next:hover {
    background: #cfdcff !important;
}

/* nama hari */
.litepicker .container__days-of-week > div {
    font-weight: 600 !important;
    color: #55639a !important;
}

/* hari */
.litepicker .day-item {
    border-radius: 10px !important;
    padding: 7px 0 !important;
    margin: 2px !important;
    color: #2d3a55 !important;
    font-weight: 500 !important;
    transition: 0.15s;
}

/* hover */
.litepicker .day-item:hover {
    background: #edf3ff !important;
    color: #1a3f6b !important;
    transform: translateY(-2px);
}

/* today */
.litepicker .day-item.is-today {
    background: #dfe8ff !important;
    border: 1px solid #b7c7ff !important;
    color: #1a3f6b !important;
    font-weight: 700 !important;
}

/* selected */
.litepicker .day-item.is-start-date,
.litepicker .day-item.is-end-date {
    background: linear-gradient(135deg, #4169ff, #2040cc) !important;
    color: #fff !important;
    font-weight: 700 !important;
    box-shadow: 0 3px 10px rgba(60, 90, 255, 0.4);
}

/* range */
.litepicker .day-item.is-in-range {
    background: #e7edff !important;
    color: #1a3f6b !important;
}
/* HILANGKAN BORDER / KOTAK PADA DROPDOWN BULAN & TAHUN */
.litepicker .month-item-header select {
    border: none !important;
    background: transparent !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #1a3f6b !important;
    padding: 2px 4px !important;
    outline: none !important;
    box-shadow: none !important;
    appearance: none !important;
    -webkit-appearance: none !important;
}

/* Hilangkan outline saat klik */
.litepicker .month-item-header select:focus {
    outline: none !important;
    box-shadow: none !important;
}

/* Hapus border kotak bulan/tahun */
.litepicker .month-item-header .month,
.litepicker .month-item-header .year {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
}

/* Hilangkan background putih saat open */
.litepicker select option {
    background: white !important;
    color: #1a3f6b !important;
}

/* FORM */
.big-box {
    background: #f6f8ff;
    border: 1px solid #d7e2ff;
    border-radius: 12px;
    padding: 22px;
    margin-bottom: 20px;
}
.form-label.required::after {
    content: " *";
    color: red;
    font-weight: bold;
}
.is-invalid {
    border-color: red !important;
    background: #ffecec !important;
}

/* FLOATING MODAL */
.floating-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999999;
}
.floating-modal.show { display: flex; }

.floating-modal-content {
    background: #ffffff;
    padding: 30px 34px;
    border-radius: 16px;
    width: 90%;
    max-width: 420px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    animation: fadeInUp .35s ease;
}

@keyframes fadeInUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>


<div class="content-wrapper">
<div class="container-fluid">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

<!-- PAGE TITLE -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box bg-galaxy-transparent">
            <h4 class="fw-bold mb-0" style="font-size:32px; color:#1a3f6b;">Form Data Diri</h4>
        </div>
    </div>
</div>

<!-- FORM CARD -->
<div class="row">
<div class="col-xl-12">

<div class="card border-0 shadow-sm">

    <div class="card-header baduy-bg">
        <h5 class="mb-0 text-white fw-bold" style="font-size:22px;">
            <i class="ri-profile-line me-2"></i> Form Data Diri
        </h5>
    </div>

    <div class="card-body">

        <div class="big-box mb-4">
            <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                <i class="ri-information-line me-1 text-primary"></i> Biodata Peserta
            </h5>

            <form id="profilForm">

                <!-- Nama & NIK -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <input required class="form-control form-control-lg" placeholder="Nama lengkap">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input required class="form-control form-control-lg" placeholder="Masukkan NIK">
                    </div>
                </div>

                <!-- Jabatan -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <input required class="form-control form-control-lg" placeholder="Masukkan NIP / NI PPPK">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input required class="form-control form-control-lg" placeholder="Masukkan Jabatan">
                    </div>
                </div>

                <!-- Agama & Jenis Kelamin -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3"> 
                        <select required class="form-select form-select-lg" placeholder="Pilih agama">
                            <option value="">-- Pilih Agama --</option>
                            <option>Islam</option>
                            <option>Kristen</option>
                            <option>Katolik</option>
                            <option>Hindu</option>
                            <option>Buddha</option>
                            <option>Konghucu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <select required class="form-select form-select-lg" placeholder="Jenis Kelamin">
                            <option value="">-- Pilih Jenis Kelamin--</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>
                    </div>
                </div>

                <!-- Tempat & Tanggal Lahir -->
                <div class="row mb-3 align-items-end">
                    <div class="col-md-6 mb-3">
                        <input required class="form-control form-control-lg" placeholder="Tempat Lahir">
                    </div>

                    <div class="col-md-6 mb-3">
                        <input id="tanggal_lahir" required autocomplete="off" class="form-control form-control-lg" placeholder="Pilih Tanggal Lahir">
                    </div>
                </div>

                <input type="hidden" id="umur">

                <!-- Pendidikan -->
                <div class="mb-3">
                    <input required class="form-control form-control-lg" placeholder="Pendidikan Terakhir">
                </div>


                <!-- ================= UNIT KERJA ================= -->
                <div class="big-box mt-4">
                    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                        <i class="ri-school-line me-1 text-primary"></i> Informasi Unit Kerja
                    </h5>

                    <input required class="form-control form-control-lg mb-3" placeholder="Unit Kerja">

                    <textarea required class="form-control form-control-lg mb-3" placeholder= "Alamat Unit Kerja"></textarea>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input required class="form-control form-control-lg" placeholder= "Kabupaten/Kota">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input required class="form-control form-control-lg" placeholder= "Provinsi">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input required class="form-control form-control-lg" placeholder= "Kode Pos">
                        </div>
                    </div>
                </div>

                <!-- ================= ALAMAT RUMAH ================= -->
                <div class="big-box mt-4">
                    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                        <i class="ri-map-pin-line me-1 text-primary"></i> Alamat Rumah
                    </h5>

                    <textarea required class="form-control form-control-lg mb-3" placeholder= "Alamat Rumah"></textarea>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input required class="form-control form-control-lg" placeholder= "Kabupaten/Kota">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input required class="form-control form-control-lg" placeholder= "Provinsi">
                        </div>
                    </div>
                </div>

                <!-- ================= KONTAK ================= -->
                <div class="big-box mt-4">
                    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                        <i class="ri-contacts-book-line me-1 text-primary"></i>
                        Kontak & Informasi Tambahan
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <input required class="form-control form-control-lg" placeholder= "Website/Email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input required class="form-control form-control-lg" placeholder= "No. Telp / HP">
                        </div>
                    </div>

                    <input type="email" required class="form-control form-control-lg mb-3"placeholder= "Email">

                    <input required class="form-control form-control-lg"placeholder= "NPWP">
                </div>

                <!-- BUTTON -->
                <button type="button" onclick="validateForm()" class="btn btn-primary btn-lg mt-4">
                    <i class="ri-check-double-line me-2"></i> Simpan Data
                </button>

            </form>
        </div>

    </div>

</div>
</div>
</div>
</div>


<!-- ======================== MODALS ======================== -->

<!-- Warning Form Kosong -->
<div id="warningModal" class="floating-modal">
    <div class="floating-modal-content">
        <i class="ri-error-warning-fill text-warning" style="font-size:48px;"></i>
        <h4 class="fw-bold mb-2" style="color:#b8860b;">Form Belum Lengkap!</h4>
        <p>Harap lengkapi semua kolom wajib.</p>
        <button class="btn btn-warning closeModalBtn">Oke</button>
    </div>
</div>

<!-- Warning Umur -->
<div id="warningAge" class="floating-modal">
    <div class="floating-modal-content">
        <i class="ri-error-warning-fill text-danger" style="font-size:48px;"></i>
        <h4 class="fw-bold mb-2" style="color:#c0392b;">Umur Tidak Memenuhi</h4>
        <p>Pendaftar harus berusia minimal 18 tahun.</p>
        <button class="btn btn-danger closeModalBtn">Oke</button>
    </div>
</div>

<!-- Success -->
<div id="successModal" class="floating-modal">
    <div class="floating-modal-content">
        <i class="ri-checkbox-circle-fill text-success" style="font-size:48px;"></i>
        <h4 class="fw-bold mb-2" style="color:#1a3f6b;">Data Berhasil Disimpan</h4>
        <p>Form berhasil diproses.</p>
        <button class="btn btn-primary closeModalBtn">Oke</button>
    </div>
</div>

@endsection

@section('sipproja-js')

<!-- LITEPICKER -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

<script>
/* INIT KALENDER */
const picker = new Litepicker({
    element: document.getElementById('tanggal_lahir'),
    format: 'DD/MM/YYYY',
    autoApply: true,
    numberOfMonths: 1,
    numberOfColumns: 1,
    dropdowns: {
        minYear: 1950,
        maxYear: new Date().getFullYear(),
        months: true,
        years: true
    },
    resetButton: false
});

/* HITUNG UMUR */
picker.on('selected', (date) => {
    let today = new Date();
    let birth = new Date(date.format('YYYY-MM-DD'));
    let age = today.getFullYear() - birth.getFullYear();
    let mdiff = today.getMonth() - birth.getMonth();

    if (mdiff < 0 || (mdiff === 0 && today.getDate() < birth.getDate()))
        age--;

    document.getElementById('umur').value = age;

    if (age < 18)
        showWarningAge();
});

/* VALIDASI FORM */
function validateForm() {
    let form = document.getElementById("profilForm");
    let required = form.querySelectorAll("[required]");
    let valid = true;

    required.forEach(f => {
        if (!f.value.trim()) {
            f.classList.add("is-invalid");
            valid = false;
        } else {
            f.classList.remove("is-invalid");
        }
    });

    if (!valid) return showWarningModal();

    let umur = parseInt(document.getElementById("umur").value);
    if (umur < 18 || isNaN(umur)) return showWarningAge();

    showSuccessModal();
}

/* MODAL CONTROLS */
function showWarningModal() { document.getElementById("warningModal").classList.add("show"); }
function showWarningAge() { document.getElementById("warningAge").classList.add("show"); }
function showSuccessModal() { document.getElementById("successModal").classList.add("show"); }
function closeModal() { document.querySelectorAll(".floating-modal").forEach(m => m.classList.remove("show")); }

document.querySelectorAll(".closeModalBtn").forEach(btn =>
    btn.addEventListener("click", closeModal)
);
</script>

@endsection
