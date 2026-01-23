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
                        <i class="ri-file-list-3-line me-2"></i> Pelatihan Apa Yang Paling Anda Butuhkan
                    </h5>
                </div>

                <div class="card-body p-4">

                    <!-- ALERT WAJIB ISI -->
                    <div class="alert alert-warning" style="border-radius:12px;">
                        <i class="ri-alert-line me-2"></i>
                        <b>Form ini WAJIB diisi.</b>
                    </div>

                    <!-- FORM PELATIHAN -->
                    <form id="pelatihanForm">
                        @csrf
                        <input type="hidden" name="ptk_id" value="{{ $ptk->ptk_id }}">
                        <input type="hidden" name="kegiatan_id" value="{{ $kegiatan_id }}">

                        <!-- INFO PTK DAN ENTITY -->
                        <div class="alert alert-info mb-4" style="border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <i class="ri-user-line me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1" style="font-weight: 700;">Informasi Peserta</h6>
                                    <p class="mb-0">
                                        <strong>Nama:</strong> {{ $ptk->nama ?? 'Tidak ditemukan' }} | 
                                        <strong>NIP:</strong> {{ $ptk->nip ?? 'Tidak ditemukan' }} | 
                                        <strong>Kegiatan:</strong> {{ $kegiatan->kegiatan_name ?? 'Tidak ditemukan' }} | 
                                        <strong>Entity:</strong> {{ $kegiatan->entity ?? 'Tidak ditemukan' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($masterPelatihan->isNotEmpty())
                        <!-- PILIHAN PELATIHAN DARI DAFTAR BERDASARKAN ENTITY -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight:700; color:#1f2937;">
                                Pilih pelatihan yang diinginkan (boleh lebih dari satu):
                            </label>
                            
                            {{-- <div class="alert alert-primary mb-3" style="border-radius:10px;">
                                <i class="ri-information-line me-2"></i>
                                Menampilkan pelatihan untuk entity: <strong>{{ $kegiatan->entity ?? 'Semua' }}</strong>
                            </div> --}}

                            <div class="mt-2" style="display:flex; flex-direction:column; gap:10px;">
                                @foreach($masterPelatihan as $pelatihan)
                                <label class="d-flex align-items-center gap-2 p-3 border rounded-3 pelatihan-item" 
                                       style="cursor:pointer; border-color: #dee2e6 !important;">
                                    <input class="form-check-input m-0 pelatihan-checkbox" 
                                           type="checkbox" 
                                           name="pelatihan_pilihan[]" 
                                           value="{{ $pelatihan->ms_pelatihan_id }}"
                                           id="pelatihan_{{ $pelatihan->ms_pelatihan_id }}"
                                           {{ in_array($pelatihan->ms_pelatihan_id, $selectedPelatihanIds) ? 'checked' : '' }}>
                                    <span style="font-weight:600; flex:1;">{{ $pelatihan->nama_pelatihan }}</span>
                                    @if($pelatihan->entity)
                                    {{-- <span class="badge bg-secondary" style="font-size:11px;">
                                        {{ $pelatihan->entity }}
                                    </span> --}}
                                    @endif
                                </label>
                                @endforeach
                            </div>

                            <div class="form-text mt-2" style="color:#6b7280;">
                                Centang minimal 1 pilihan.
                            </div>
                        </div>
                        @else
                        <!-- JIKA TIDAK ADA PELATIHAN UNTUK ENTITY INI -->
                        <div class="alert alert-warning mb-4" style="border-radius:12px;">
                            <div class="d-flex align-items-center">
                                <i class="ri-alert-line me-3" style="font-size:24px;"></i>
                                <div>
                                    <h6 class="mb-1" style="font-weight:700;">Pelatihan Tidak Tersedia</h6>
                                    <p class="mb-0">
                                        Belum ada daftar pelatihan untuk entity <strong>{{ $kegiatan->entity ?? 'ini' }}</strong>. 
                                        Silakan gunakan kolom "Pelatihan lainnya" di bawah.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- FREE TEXT: PELATIHAN LAINNYA -->
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:700; color:#1f2937;">
                                Pelatihan lainnya (jika ada):
                            </label>
                            <textarea class="form-control" name="pelatihan_lainnya" rows="4"
                                      placeholder="Tulis pelatihan lain yang Anda inginkan..."
                                      style="border-radius:12px;">{{ $pelatihanLainnya }}</textarea>
                            <div class="form-text mt-2" style="color:#6b7280;">
                                Opsional. Isi jika pilihan di atas belum sesuai atau tidak tersedia.
                            </div>
                        </div>

                        <!-- BUTTON SIMPAN -->
                        <button id="btnSimpan" type="button" class="btn btn-primary w-100 mt-3"
                                style="border-radius:12px; padding:12px 16px; font-weight:700;">
                            <i class="ri-check-double-line me-2"></i> Simpan
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <!-- MODAL SUCCESS -->
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
                        Data pilihan pelatihan Anda sudah tersimpan. Terima kasih.
                    </div>

                    <!-- SATU CARD (TIME + BUTTON) -->
                    <div class="finish-action mt-4 text-start">
                        <div class="finish-time text-center mb-3">
                            <i class="ri-time-line me-1"></i>
                            Selesai pada: <span id="finishTimeText">{{ date('d F Y H:i:s') }}</span>
                        </div>

                        <button type="button"
                                class="btn btn-primary btn-lg btn-back-blue w-100 d-block"
                                onclick="window.location.href='{{ route('ptk.show', ['encode_kegiatan_id' => $encoded_kegiatan_id, 'nip' => $nip]) }}'">
                            <i class="ri-home-line me-2"></i> Kembali ke Dashboard
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
        max-width: 520px;
        width: calc(100% - 24px);
        margin-left: auto;
        margin-right: auto; 
    }
    /* ====== finish modal card style ====== */
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

    /* Checkbox Styles */
    .pelatihan-item {
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }
    
    .pelatihan-item:hover {
        border-color: #1a4d8e;
        background-color: rgba(26, 77, 142, 0.05);
    }
    
    .pelatihan-checkbox:checked {
        background-color: #1a4d8e;
        border-color: #1a4d8e;
    }
    
    .pelatihan-checkbox:focus {
        box-shadow: 0 0 0 0.25rem rgba(26, 77, 142, 0.25);
    }
</style>

@section('sipproja-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // klik simpan => AJAX ke backend
    document.addEventListener('DOMContentLoaded', function () {
        const btnSimpan = document.getElementById('btnSimpan');
        const modalEl = document.getElementById('finishModal');
        const form = document.getElementById('pelatihanForm');

        if (btnSimpan) {
            btnSimpan.addEventListener('click', function () {
                // Validasi minimal satu checkbox tercentang atau textarea terisi
                const checkboxes = document.querySelectorAll('.pelatihan-checkbox:checked');
                const textarea = document.querySelector('textarea[name="pelatihan_lainnya"]');
                const textareaValue = textarea ? textarea.value.trim() : '';

                if (checkboxes.length === 0 && textareaValue === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih minimal satu pelatihan atau tulis pelatihan lainnya',
                        confirmButtonColor: '#1a4d8e'
                    });
                    return;
                }

                // Tampilkan loading
                const originalText = btnSimpan.innerHTML;
                btnSimpan.disabled = true;
                btnSimpan.innerHTML = '<i class="ri-loader-4-line me-2"></i> Menyimpan...';

                // Kumpulkan data form
                const formData = new FormData(form);

                // Kirim AJAX request
                fetch('{{ route("pelatihan.store", ["encoded_kegiatan_id" => $encoded_kegiatan_id, "nip" => $nip]) }}', {
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
                        // Update waktu di modal
                        const now = new Date();
                        const pad = (n) => String(n).padStart(2, '0');
                        const months = [
                            "Januari","Februari","Maret","April","Mei","Juni",
                            "Juli","Agustus","September","Oktober","November","Desember"
                        ];
                        const text = `${pad(now.getDate())} ${months[now.getMonth()]} ${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                        
                        const timeEl = document.getElementById('finishTimeText');
                        if (timeEl) timeEl.textContent = text;
                        
                        // Tampilkan modal success
                        const successModal = new bootstrap.Modal(modalEl);
                        successModal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan data',
                        confirmButtonColor: '#dc3545'
                    });
                })
                .finally(() => {
                    // Reset button state
                    btnSimpan.disabled = false;
                    btnSimpan.innerHTML = '<i class="ri-check-double-line me-2"></i> Simpan';
                });
            });
        }
    });
</script>
@endsection

@endsection