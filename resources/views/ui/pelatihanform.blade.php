@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <div class="row justify-content-center mt-5">
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-header baduy-bg text-center" style="border-radius:14px 14px 0 0;">
                    <h5 class="mb-0 text-white" style="font-size:20px; font-weight:700;">
                        <i class="ri-file-list-3-line me-2"></i> Form Pelatihan Yang Diinginkan
                    </h5>
                </div>

                <div class="card-body p-4">

                    <div class="alert alert-warning" style="border-radius:12px;">
                        <i class="ri-alert-line me-2"></i>
                        <b>Form ini WAJIB diisi.</b>
                    </div>

                    <!-- PILIHAN (BISA MULTI) -->
                    <div class="mb-4">
                        <label class="form-label" style="font-weight:700; color:#1f2937;">
                            Pilih pelatihan yang diinginkan (boleh lebih dari satu):
                        </label>

                        <div class="mt-2" style="display:flex; flex-direction:column; gap:10px;">

                            <label class="d-flex align-items-center gap-2 p-3 border rounded-3" style="cursor:pointer;">
                                <input class="form-check-input m-0" type="checkbox" name="pelatihan_pilihan[]" value="Pelatihan Model Pembelajaran">
                                <span style="font-weight:600;">Pelatihan Model Pembelajaran</span>
                            </label>

                            <label class="d-flex align-items-center gap-2 p-3 border rounded-3" style="cursor:pointer;">
                                <input class="form-check-input m-0" type="checkbox" name="pelatihan_pilihan[]" value="Pelatihan Subtansi Materi Pembelajaran">
                                <span style="font-weight:600;">Pelatihan Subtansi Materi Pembelajaran</span>
                            </label>

                            <label class="d-flex align-items-center gap-2 p-3 border rounded-3" style="cursor:pointer;">
                                <input class="form-check-input m-0" type="checkbox" name="pelatihan_pilihan[]" value="Pelatihan Penilaian Pembelajaran">
                                <span style="font-weight:600;">Pelatihan Penilaian Pembelajaran</span>
                            </label>

                            <label class="d-flex align-items-center gap-2 p-3 border rounded-3" style="cursor:pointer;">
                                <input class="form-check-input m-0" type="checkbox" name="pelatihan_pilihan[]" value="Pelatihan Pengelolaan Kelas">
                                <span style="font-weight:600;">Pelatihan Pengelolaan Kelas</span>
                            </label>

                            <label class="d-flex align-items-center gap-2 p-3 border rounded-3" style="cursor:pointer;">
                                <input class="form-check-input m-0" type="checkbox" name="pelatihan_pilihan[]" value="Pelatihan Pemanfaatan Teknologi">
                                <span style="font-weight:600;">Pelatihan Pemanfaatan Teknologi</span>
                            </label>

                        </div>

                        <div class="form-text mt-2" style="color:#6b7280;">
                            Centang minimal 1 pilihan.
                        </div>
                    </div>

                    <!-- FREE TEXT: PELATIHAN LAINNYA -->
                    <div class="mb-3">
                        <label class="form-label" style="font-weight:700; color:#1f2937;">
                            Pelatihan lainnya (jika ada):
                        </label>
                        <textarea class="form-control" name="pelatihan_lainnya" rows="4"
                                  placeholder="Tulis pelatihan lain yang Anda inginkan..."
                                  style="border-radius:12px;"></textarea>
                        <div class="form-text mt-2" style="color:#6b7280;">
                            Opsional. Isi jika pilihan di atas belum sesuai.
                        </div>
                    </div>

                    <!-- BUTTON (DUMMY) -->
                    <button id="btnSimpanDummy" type="button" class="btn btn-primary w-100 mt-3"
                            style="border-radius:12px; padding:12px 16px; font-weight:700;">
                        Simpan
                    </button>

                    <p class="mt-3 mb-0 text-muted" style="font-size:13px;">
                        *Belum terhubung ke backend (sementara UI saja).
                    </p>

                </div>
            </div>

        </div>
    </div>

    <!-- ==========================
         MODAL FINISH (SATU AJA)
         ========================== -->
    <div class="modal fade" id="finishModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered finish-modal-sm">
            <div class="modal-content border-0 shadow-sm finish-card">

                <!-- HEADER -->
                <div class="card-header baduy-bg text-center finish-header">
                    <h5 class="mb-0 text-white finish-title">
                        <i class="ri-checkbox-circle-line me-2"></i> Form Pelatihan Tersimpan
                    </h5>
                </div>

                <div class="modal-body p-5 text-center">

                    <!-- ICON SUCCESS -->
                    <div class="mb-4">
                        <div class="icon-success mx-auto">
                            <i class="ri-checkbox-circle-fill" style="font-size: 80px; color: #28a745;"></i>
                        </div>
                    </div>

                    <!-- TITLE -->
                    <h3 class="mb-3 finish-h3">
                        Terima Kasih! Form Pelatihan Anda Sudah Diisi
                    </h3>

                    <!-- MESSAGE -->
                    <div class="alert alert-success finish-alert" role="alert">
                        <i class="ri-information-line me-2"></i>
                        Data pilihan pelatihan Anda sudah tersimpan (dummy). Terima kasih.
                    </div>

                    <!-- ✅ SATU CARD (TIME + BUTTON) -->
                    <div class="finish-action mt-4 text-start">
                        <div class="finish-time text-center mb-3">
                            <i class="ri-time-line me-1"></i>
                            Selesai pada: <span id="finishTimeText">{{ date('d F Y H:i:s') }}</span>
                        </div>

                        <button type="button"
                                class="btn btn-primary btn-lg btn-back-blue w-100 d-block"
                                data-bs-dismiss="modal"
                                onclick="history.back()">
                            <i class="ri-arrow-left-line me-2"></i> Kembali
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<style>
    /* ✅ perkecil lebar modal finish */
    .finish-modal-sm{
        max-width: 520px; /* ganti 480/500/520 sesuai selera */
        width: calc(100% - 24px);
        margin-left: auto;
        margin-right: auto; 
    }
    /* ====== finish modal card style (sama kayak yg kamu minta) ====== */
    .finish-card{
        border-radius:14px;
        overflow: hidden;
        background:#fff;
    }
    .finish-header{
        border-radius:14px 14px 0 0;
        border:0;
    }
    .finish-title{
        font-size:20px;
        font-weight:700;
    }
    .finish-h3{
        color:#1a4d8e;
        font-weight:700;
    }

    .icon-success {
        width: 120px;
        height: 120px;
        background: #f8fff8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #d4edda;
    }

    .finish-alert{
        font-size:16px;
        border-radius:10px;
        margin-bottom: 0;
    }

    .finish-action{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:16px;
        box-shadow: 0 10px 22px rgba(2,6,23,.06);
    }
    .finish-time{
        font-size:14px;
        color:#6b7280;
    }

    /* ✅ warna biru button */
    .btn-back-blue{
        background: #1a4d8e !important;
        border-color: #1a4d8e !important;
        color: #fff !important;
        border-radius:12px !important;
        padding: 14px 22px !important;
        font-weight: 700 !important;
        box-shadow: 0 8px 18px rgba(26, 91, 184, .18);
    }
    .btn-back-blue:hover,
    .btn-back-blue:focus{
        background: #164f9e !important;
        border-color: #164f9e !important;
        color: #fff !important;
    }
</style>

@section('sipproja-js')
<script>
    // klik simpan => munculin modal finish
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btnSimpanDummy');
        const modalEl = document.getElementById('finishModal');

        if (btn && modalEl) {
            btn.addEventListener('click', function () {
                // set waktu selesai realtime
                const now = new Date();
                const pad = (n) => String(n).padStart(2, '0');

                const months = [
                    "January","February","March","April","May","June",
                    "July","August","September","October","November","December"
                ];
                const text = `${pad(now.getDate())} ${months[now.getMonth()]} ${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                const timeEl = document.getElementById('finishTimeText');
                if (timeEl) timeEl.textContent = text;

                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();
            });
        }
    });
</script>
@endsection

@endsection
