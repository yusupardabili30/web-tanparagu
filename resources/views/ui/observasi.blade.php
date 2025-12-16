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

.form-floating > label {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #555555 !important;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select:focus ~ label,
.form-floating > .form-select:not([value=""]) ~ label {
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

    .header-info h5 {
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
        style="width:180px; height:auto; margin-left:-30px; margin-top:-100px;">

    <!-- TEXT -->
    <div class="header-info" style="line-height:1.3; margin-top:-100px;">
        <h5 class="fw-bold mb-1" style="color:#1a3f6b; font-size:26px;">
        Format Lembar Observasi (Kode 01) & Panduan Wawancara Guru (Kode 02) 
        </h5>

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
                        <i class="ri-profile-line me-2"></i> Format Lembar Observasi
                    </h5>
                </div>

                <div class="card-body">

                    <form id="profilForm">

            <form id="profilForm">

            <!-- ===================== INFORMASI UMUM ===================== -->
            <div class="big-box">
                <h4 class="fw-bold mb-2" style="color:#1a3f6b;">
                    Informasi Umum
                </h4>

                <div class="pt-0 pb-3">
                    <p class="mb-4" style="font-size:16px; font-weight:500; color:#333;">
                        Lembar observasi digunakan untuk menjaring informasi utama terkait kompetensi guru
                        dalam praktik mengajar di kelas beserta interaksinya dengan murid dan/atau rekan sejawat.
                        Informasi digunakan untuk kepentingan analisis dan tidak disebarluaskan ke luar lembaga.
                    </p>

                    <p class="mb-0" style="font-size:16px; font-weight:500; color:#333;">
                        Panduan wawancara digunakan untuk menjaring kelengkapan informasi dari informan di lapangan
                        dan bersifat komplementer, aditif atau triangulatif dengan sumber informasi lainnya dalam
                        pembelajaran dan dokumen relevan.
                    </p>
                </div>
            </div>


                <!-- ===================== PERNYATAAN KESEDIAAN ===================== -->
                <div class="big-box">
                    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">Pernyataan Kesediaan Informan</h5>
                        <p class="mb-3 mt-3" style="font-size:15px; font-weight:500; color:#333;">
                            Apakah Ibu/Bapak bersedia menjawab pertanyaan wawancara dan mengizinkan observasi kelas?
                        </p>    
                    <div class="d-flex gap-4">
                    <label class="form-check" style="font-size:15px; font-weight:500; color:#333;">
                        <input class="form-check-input" type="radio" name="kesediaan" required>
                        Bersedia
                    </label>

                    <label class="form-check" style="font-size:15px; font-weight:500; color:#333;">
                        <input class="form-check-input" type="radio" name="kesediaan">
                        Tidak Bersedia
                    </label>
                    </div>
                </div>

                <!-- ===================== IDENTITAS INFORMAN ===================== -->
                <div class="big-box">
                    <h5 class="fw-bold mb-2" style="color:#1a3f6b;">Identitas Diri Informan</h5>

                    <!-- Nama Informan (full) -->
                    <div class="form-floating mt-3 mb-2">
                        <input required class="form-control form-control-lg" placeholder=" ">
                        <label>Nama</label>
                    </div>

                    <!-- Jabatan & Pangkat/Golongan -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input required class="form-control form-control-lg" placeholder=" ">
                                <label>Jabatan</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input class="form-control form-control-lg" placeholder=" ">
                                <label>Pangkat / Golongan</label>
                            </div>
                        </div>
                    </div>

                    <!-- Masa Kerja & Mata Pelajaran -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input class="form-control form-control-lg" placeholder=" ">
                                <label>Masa Kerja</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input class="form-control form-control-lg" placeholder=" ">
                                <label>Mata Pelajaran yang Diampu</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ===================== KELAS DIOBSERVASI ===================== -->
                    <h5 class="fw-bold mb-2 mt-4" style="color:#1a3f6b;">Kelas yang Diobservasi</h5>

                    <!-- Kelas & Mata Pelajaran -->
                    <div class="row mt-3 mb-2">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input required class="form-control form-control-lg" placeholder=" ">
                                <label>Kelas</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input required class="form-control form-control-lg" placeholder=" ">
                                <label>Mata Pelajaran (Lampirkan RPP)</label>
                            </div>
                        </div>
                    </div>

<!-- ===== TANGGAL (FIX) ===== -->
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-2">
            <input
                type="text"
                id="tanggal"
                class="form-control form-control-lg"
                placeholder=" "
                required
                readonly
                autocomplete="off"
            >
            <label>Tanggal</label>
        </div>
    </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input class="form-control form-control-lg" placeholder=" ">
                                <label>Pukul</label>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="big-box">
                <h5 class="fw-bold mb-3" style="color:#1a3f6b;">
                    Pertanyaan dalam Observasi & Wawancara
                </h5>

                <p class="mb-0" style="font-size:15px; font-weight:500; color:#333;">
                        <strong>Petunjuk Khusus:</strong>
                        Gunakan lembar ini untuk menuangkan informasi yang diperoleh. Jika lembar ini tidak
                        mencukupi, gunakan lembar baliknya atau lembar lain secukupnya. Tuliskan dengan tulisan
                        tangan dan/atau rekamlah semua informasi tersebut. Cek ulang untuk konfirmasi. Setelah
                        lengkap informasi yang diperoleh, kemudian ketik dalam format word untuk analisis
                        keputusan level kompetensi.
                    </p>
                </div>

                <!-- ===================== KOMPETENSI PEDAGOGIK (PED-101) ===================== -->
                <div class="big-box">
                    <h5 class="fw-bold mb-4" style="color:#1a3f6b;">
                        Kompetensi Pedagogik
                    </h5>

                    <div class="row mb-2 mt-4">
                        <div class="col-md-6">
                            <div class="form-floating mb-2">
                                <select class="form-select" required style="font-size:16px; color:#555555 !important;">
                                    <option value="">Pilih Kode</option>
                                    <option>Ped-101</option>
                                    <option>Ped-102</option>
                                    <option>Ped-103</option>
                                    <option>Ped-104</option>
                                    <option>Ped-105</option>
                                    <option>Ped-106</option>
                                </select>
                                <label>Kode</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-2">
                                <select class="form-select" required style="font-size:16px; color:#555555 !important;">
                                    <option value="">Pilih Variabel</option>
                                    <option>Pengelolaan Lingkungan Pembelajaran yang Aman dan Nyaman</option>
                                    <option>Ped-Pembelajaran Efektif</option>
                                    <option>Asesmen Berbasis Kelas</option>
                                </select>
                                <label>Variabel</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                            <select class="form-select" required style="font-size:16px; color:#555555 !important;">
                                <option value="">Pilih Indikator & Sub-Indikator</option>
                                <option>Lingkungan pembelajaran yang aman dan nyaman bagi peserta didik - Pengelolaan perilaku peserta didik yang sulit</option>
                                <option>Lingkungan pembelajaran yang aman dan nyaman bagi peserta didik - Pengelolaan pembelajaran yang berpusat pada peserta didik</option>
                            </select>
                        <label>Indikator & Sub-Indikator</label>
                    </div>
                </div>

                <div class="big-box">
                <!-- JUDUL -->
                    <div style="font-size:17px; font-weight:700; margin-bottom:6px;  color:#1a3f6b;">
                        Level
                    </div>

                    <table width="100%" cellpadding="10" cellspacing="0" style="color:#333;">
                        <tr>

                            <!-- PAHAM -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="level" value="paham" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">Paham:</b><br>
                                            <span style="font-size:14px;">
                                                Setidaknya tahu untuk dirinya sendiri
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- PENERAPAN -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="level" value="penerapan" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">Penerapan:</b><br>
                                            <span style="font-size:14px;">
                                                Adanya tindakan yang tampak mata
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- EVALUASI -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="level" value="evaluasi" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">Evaluasi:</b><br>
                                            <span style="font-size:14px;">
                                                Nyata dilakukan dan memperbaikinya
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- KOLABORASI -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="level" value="kolaborasi" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">Kolaborasi:</b><br>
                                            <span style="font-size:14px;">
                                                Melakukan dan mengajak teman lain
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- PEMBIMBINGAN -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="level" value="pembimbingan" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">Pembimbingan:</b><br>
                                            <span style="font-size:14px;">
                                                Memberi contoh dan mengajak
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                    </table>    
                </div>
            
                <div class="big-box">
                    <!-- JUDUL -->
                    <div style="font-size:17px; font-weight:700; margin-bottom:6px; margin-top:20px; color:#1a3f6b;">
                        Bukti Perilaku Kunci
                    </div>

                    <table width="100%" cellpadding="10" cellspacing="0" style="color:#333;">
                        <tr>

                            <!-- OPSI 1 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="bukti_ped101" value="1" style="transform: scale(1.2);" required>
                                        </td>
                                        <td valign="middle">
                                            <span style="font-size:14px;">
                                                Hanya tahu strategi<br>
                                                mengelola<br>
                                                pembelajaran berpusat<br>
                                                pada murid tapi belum<br>
                                                dilakukan
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 2 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="bukti_ped101" value="2" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <span style="font-size:14px;">
                                                Ada penerapan<br>
                                                strategi/teknik<br>
                                                mengelola<br>
                                                pembelajaran berpusat<br>
                                                pada murid
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 3 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="bukti_ped101" value="3" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <span style="font-size:14px;">
                                                Ada evaluasi dan<br>
                                                perbaikan strategi<br>
                                                mengelola<br>
                                                pembelajaran berpusat<br>
                                                pada murid
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 4 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="bukti_ped101" value="4" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <span style="font-size:14px;">
                                                Ada kerja sama<br>
                                                pengelolaan<br>
                                                pembelajaran berpusat<br>
                                                pada murid
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 5 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="bukti_ped101" value="5" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <span style="font-size:14px;">
                                                Ada pembimbingan<br>
                                                rekan sejawat dalam<br>
                                                pengelolaan<br>
                                                pembelajaran ber
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                    </table>
                </div>
            

            <!-- ===================== OBSERVASI ===================== -->
            <div class="big-box">

                <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                    Pertanyaan Kunci – Observasi    
                </h5>

                <ul style="font-size:15px; color:#333; margin-top:8px;">
                    <li>Apa strategi/teknik yang efektif dalam mengelola perilaku murid yang sulit di dalam kelas?</li>
                    <li>Bagaimana mengidentifikasi penyebab perilaku sulit pada murid dan mengatasinya?</li>
                </ul>

                <!-- LABEL + TOGGLE -->
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <label class="fw-bold mt-3" style="color:#1a3f6b; font-size:16px;">
                        Data Observasi
                    </label>

                    <button
                        type="button"
                        onclick="toggleObservasi(this)"
                        style="border:none; background:none; color:#1a3f6b; font-weight:600; cursor:pointer;"
                    >
                        Perbesar
                    </button>
                </div>

                <!-- EDITABLE BOX -->
                <div
                    id="observasiBox"
                    contenteditable="true"
                    oninput="this.nextElementSibling.value=this.innerText"
                    style="
                        min-height:120px;
                        max-height:120px;
                        overflow:hidden;
                        padding:12px;
                        border:1px solid #ced4da;
                        border-radius:8px;
                        font-size:15px;
                        color:#333;
                        outline:none;
                        white-space:pre-wrap;
                        word-break:break-word;
                        background:#fff;
                        transition:max-height 0.3s ease;
                    "
                ></div>

                <!-- HIDDEN INPUT -->
                <input type="hidden" name="data_observasi">
            </div>

            <script>
            function toggleObservasi(btn) {
                const box = document.getElementById('observasiBox');

                if (box.style.maxHeight === '120px') {
                    box.style.maxHeight = '1000px';
                    box.style.overflow = 'auto';
                    btn.innerText = 'Kecilkan';
                } else {
                    box.style.maxHeight = '120px';
                    box.style.overflow = 'hidden';
                    btn.innerText = 'Perbesar';
                }
            }
            </script>


            <!-- ===================== WAWANCARA ===================== -->
            <div class="big-box">

                <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                    Pertanyaan Kunci – Wawancara & Probing
                </h5>

                <ul style="font-size:15px; color:#333; margin-top:8px;">
                    <li>Apa strategi/teknik yang digunakan?</li>
                    <li>Mengapa strategi tersebut dipilih?</li>
                    <li>Bagaimana tindakan dan solusi yang dilakukan?</li>
                    <li>Bagaimana pengalaman berkolaborasi?</li>
                    <li>Bagaimana pengalaman memberikan bantuan/bimbingan?</li>
                </ul>

                <!-- LABEL + TOGGLE -->
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <label class="fw-bold mt-3" style="color:#1a3f6b; font-size:16px;">
                        Data Wawancara
                    </label>

                    <button
                        type="button"
                        onclick="toggleWawancara(this)"
                        style="border:none; background:none; color:#1a3f6b; font-weight:600; cursor:pointer;"
                    >
                        Perbesar
                    </button>
                </div>

                <!-- EDITABLE BOX -->
                <div
                    id="wawancaraBox"
                    contenteditable="true"
                    oninput="this.nextElementSibling.value=this.innerText"
                    style="
                        min-height:120px;
                        max-height:120px;
                        overflow:hidden;
                        padding:12px;
                        border:1px solid #ced4da;
                        border-radius:8px;
                        font-size:15px;
                        color:#333;
                        outline:none;
                        white-space:pre-wrap;
                        word-break:break-word;
                        background:#fff;
                        transition:max-height 0.3s ease;
                    "
                ></div>

                <!-- HIDDEN INPUT -->
                <input type="hidden" name="data_wawancara">
            </div>

            <script>
            function toggleWawancara(btn) {
                const box = document.getElementById('wawancaraBox');

                if (box.style.maxHeight === '120px') {
                    box.style.maxHeight = '1000px';
                    box.style.overflow = 'auto';
                    btn.innerText = 'Kecilkan';
                } else {
                    box.style.maxHeight = '120px';
                    box.style.overflow = 'hidden';
                    btn.innerText = 'Perbesar';
                }
            }
            </script>


            <!-- ===================== BUKTI DUKUNG ===================== -->
            <div class="big-box">

                <h5 class="fw-bold mb-2" style="color:#1a3f6b;">
                    Bukti Dukung (Langsung & Tidak Langsung)
                </h5>

                <!-- LABEL + TOGGLE -->
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <label class="fw-bold mt-3" style="color:#1a3f6b; font-size:16px;">
                        Uraian Bukti Dukung
                    </label>

                    <button
                        type="button"
                        onclick="toggleBuktiDukung(this)"
                        style="border:none; background:none; color:#1a3f6b; font-weight:600; cursor:pointer;"
                    >
                        Perbesar
                    </button>
                </div>

                <!-- EDITABLE BOX -->
                <div
                    id="buktiDukungBox"
                    contenteditable="true"
                    oninput="this.nextElementSibling.value=this.innerText"
                    style="
                        min-height:120px;
                        max-height:120px;
                        overflow:hidden;
                        padding:12px;
                        border:1px solid #ced4da;
                        border-radius:8px;
                        font-size:15px;
                        color:#333;
                        outline:none;
                        white-space:pre-wrap;
                        word-break:break-word;
                        background:#fff;
                        transition:max-height 0.3s ease;
                    "
                ></div>

                <!-- HIDDEN INPUT -->
                <input type="hidden" name="bukti_dukung">
            </div>

            <script>
            function toggleBuktiDukung(btn) {
                const box = document.getElementById('buktiDukungBox');

                if (box.style.maxHeight === '120px') {
                    box.style.maxHeight = '1000px';
                    box.style.overflow = 'auto';
                    btn.innerText = 'Kecilkan';
                } else {
                    box.style.maxHeight = '120px';
                    box.style.overflow = 'hidden';
                    btn.innerText = 'Perbesar';
                }
            }
            </script>

                    <div class="big-box">                    
                    <!-- JUDUL -->
                    <div style="font-size:20px; font-weight:700; margin-bottom:6px  ; color:#1a3f6b;">
                        Judgement Surveyor
                    </div>

                    <table width="100%" cellpadding="10" cellspacing="0" style="color:#333;">
                        <tr>

                            <!-- OPSI 1 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="ped101" value="1" required style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">1</b>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 2 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="ped101" value="2" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">2</b>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 3 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="ped101" value="3" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">3</b>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 4 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="ped101" value="4" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">4</b>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- OPSI 5 -->
                            <td width="20%" valign="middle">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="middle" width="22">
                                            <input type="radio" name="ped101" value="5" style="transform: scale(1.2);">
                                        </td>
                                        <td valign="middle">
                                            <b style="font-size:15px;">5</b>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                    </table>
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
        <h5 class="mt-3 fw-bold" style="color:#1a3f6b;">Data Berhasil Disimpan</h5>
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
        <h5 class="mt-3 fw-bold text-danger">Form Belum Lengkap</h5>
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
document.addEventListener('DOMContentLoaded', function () {
    new Litepicker({
        element: document.getElementById('tanggal'),
        format: 'DD/MM/YYYY',
        autoApply: true,
        dropdowns: {
            minYear: 1950,
            maxYear: new Date().getFullYear(),
            months: true,
            years: true
        }
    });
});

function validateForm() {
    let valid = true;
    document.querySelectorAll("[required]").forEach(el => {
        if (!el.value.trim()) {
            el.classList.add("is-invalid");
            valid = false;
        } else {
            el.classList.remove("is-invalid");
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
