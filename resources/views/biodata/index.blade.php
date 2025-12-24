@extends('layouts.main')

@section('mycontent')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Daftar {{ $tittle }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $tittle }}</a></li>
                        <li class="breadcrumb-item active">Daftar {{ $tittle }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- Toast Container untuk Notifikasi -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <!-- Success Toast -->
        @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-checkbox-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

        <!-- Error Toast -->
        @if(session('error'))
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-error-warning-fill me-2"></i>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>

    <!-- Notifikasi Alert untuk Info -->
    @if(session('info'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="ri-information-line me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Biodata</h5>
                        <div>
                            {{-- TOMBOL EXPORT DENGAN SEMUA PARAMETER FILTER --}}
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="ri-file-pdf-line me-1"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('biodata.index') }}" class="row g-3 mb-4" id="filterForm">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" placeholder="Cari NIP/Nama/NIK..."
                                value="{{ request('search') }}" id="searchInput">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="kegiatan_id" id="kegiatanSelect">
                                <option value="">Semua Kegiatan</option>
                                @foreach ($data['kegiatans'] as $kegiatan)
                                <option value="{{ $kegiatan->kegiatan_id }}"
                                    {{ request('kegiatan_id') == $kegiatan->kegiatan_id ? 'selected' : '' }}>
                                    {{ $kegiatan->kegiatan_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line"></i> Cari
                            </button>
                            <a href="{{ route('biodata.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <!-- Di dalam tabel, tambahkan kolom TTD -->
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>Kegiatan</th>
                                    <th width="100">TTD</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['peserta'] as $row)
                                <tr>
                                    <td>{{ ($data['peserta']->currentPage() - 1) * $data['peserta']->perPage() + 1 }}</td>
                                    <td>{{ $row->nip }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td>{{ $row->jenjang_jabatan ?? '-' }}</td>

                                    <td>
                                        @if(!empty($row->nama_sekolah))
                                        {{ $row->nama_sekolah }}
                                        @elseif(!empty($row->instansi))
                                        {{ $row->instansi }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $row->kegiatan_name ?? '-' }}</td>
                                    <td>
                                        @if(!empty($row->ttd_base64))
                                        <span class="badge bg-success">
                                            <i class="ri-check-line me-1"></i> Ada
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">
                                            <i class="ri-close-line me-1"></i> Tidak Ada
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewSignatureModal"
                                                data-nama="{{ $row->nama }}"
                                                data-ttd-base64="{{ $row->ttd_base64 ?? '' }}">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <a href="{{ route('biodata.exportPdf', $row->peserta_id) }}"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirmExportSingle(event)">
                                                <i class="ri-file-pdf-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal untuk Melihat Tanda Tangan -->
    <div class="modal fade" id="viewSignatureModal" tabindex="-1" aria-labelledby="viewSignatureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSignatureModalLabel">
                        <i class="ri-signature-line me-2"></i> Tanda Tangan Digital
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h6 id="signatureOwnerName" class="mb-3">Nama Peserta</h6>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <div id="signatureImageContainer" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                <div id="signaturePlaceholder" class="text-muted">
                                    <i class="ri-signature-line fs-1"></i>
                                    <p class="mt-2">Tanda tangan tidak tersedia</p>
                                </div>
                                <img id="signatureImage"
                                    src=""
                                    alt="Tanda Tangan"
                                    style="max-width: 100%; max-height: 150px; display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="ri-information-line me-2"></i>
                        Tanda tangan digital ini disimpan dalam format base64 dan dapat digunakan untuk dokumen resmi.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="downloadSignatureBtn">
                        <i class="ri-download-line me-1"></i> Download Gambar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Export -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="ri-file-pdf-line text-danger me-2"></i> Konfirmasi Export PDF
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        Export data dengan filter berikut:
                    </div>

                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4 fw-bold">Pencarian:</div>
                                <div class="col-8">
                                    <span id="exportSearchInfo" class="text-primary">(Semua)</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 fw-bold">Kegiatan:</div>
                                <div class="col-8">
                                    <span id="exportKegiatanInfo" class="text-primary">(Semua Kegiatan)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <i class="ri-alert-line me-2"></i>
                        Data akan diexport dalam format PDF dan otomatis didownload.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmExportBtn" onclick="proceedExport()">
                        <i class="ri-file-pdf-line me-1"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-dark fw-bold">Sedang memproses...</p>
                    <p class="text-muted small">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Single Export -->
    <div class="modal fade" id="singleExportModal" tabindex="-1" aria-labelledby="singleExportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singleExportModalLabel">
                        <i class="ri-file-pdf-line text-danger me-2"></i> Export PDF Perorangan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ri-file-pdf-line text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin export PDF biodata ini?</p>
                    <p class="text-center text-muted small">File PDF akan otomatis didownload.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Batal
                    </button>
                    <a href="#" class="btn btn-danger" id="singleExportLink" onclick="startSingleExport()">
                        <i class="ri-file-pdf-line me-1"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variabel untuk menyimpan instance modal
        let loadingModalInstance = null;
        let exportModalInstance = null;
        let singleExportModalInstance = null;

        // Saat modal export ditampilkan
        document.getElementById('exportModal').addEventListener('show.bs.modal', function(event) {
            // Ambil nilai dari form filter
            const search = document.getElementById('searchInput').value;
            const kegiatanId = document.getElementById('kegiatanSelect').value;

            // Update informasi di modal
            document.getElementById('exportSearchInfo').textContent = search || '(Semua)';

            if (kegiatanId) {
                const kegiatanSelect = document.getElementById('kegiatanSelect');
                const kegiatanText = kegiatanSelect.options[kegiatanSelect.selectedIndex].text;
                document.getElementById('exportKegiatanInfo').textContent = kegiatanText;
            } else {
                document.getElementById('exportKegiatanInfo').textContent = '(Semua Kegiatan)';
            }
        });

        function proceedExport() {
            // Ambil nilai dari form filter
            const search = document.getElementById('searchInput').value;
            const kegiatanId = document.getElementById('kegiatanSelect').value;

            // Buat URL dengan parameter filter
            let url = "{{ route('biodata.exportAllPdf') }}";
            const params = new URLSearchParams();

            if (search) {
                params.append('search', search);
            }

            if (kegiatanId) {
                params.append('kegiatan_id', kegiatanId);
            }

            // Tambahkan parameter jika ada
            if (params.toString()) {
                url += '?' + params.toString();
            }

            // Tutup modal konfirmasi
            if (exportModalInstance) {
                exportModalInstance.hide();
            }

            // Tampilkan modal loading
            showLoadingModal();

            // Simpan URL untuk digunakan nanti
            window.exportUrl = url;

            // Set timeout untuk memberikan efek loading
            setTimeout(function() {
                // Redirect ke URL export
                window.location.href = url;

                // Set timeout untuk hide loading (fallback jika redirect lambat)
                setTimeout(hideLoadingModal, 3000);
            }, 500);
        }

        function confirmExportSingle(event) {
            event.preventDefault();

            // Dapatkan URL dari link yang diklik
            const exportUrl = event.currentTarget.href;

            // Set URL ke modal
            document.getElementById('singleExportLink').href = exportUrl;

            // Tampilkan modal konfirmasi
            singleExportModalInstance = new bootstrap.Modal(document.getElementById('singleExportModal'));
            singleExportModalInstance.show();

            return false;
        }

        function startSingleExport() {
            // Dapatkan URL dari link
            const url = document.getElementById('singleExportLink').href;

            // Tutup modal konfirmasi
            if (singleExportModalInstance) {
                singleExportModalInstance.hide();
            }

            // Tampilkan modal loading
            showLoadingModal();

            // Redirect ke URL export
            setTimeout(function() {
                window.location.href = url;

                // Set timeout untuk hide loading (fallback jika redirect lambat)
                setTimeout(hideLoadingModal, 3000);
            }, 500);

            return false;
        }

        function showLoadingModal() {
            // Buat instance modal loading
            const loadingModal = document.getElementById('loadingModal');
            loadingModalInstance = new bootstrap.Modal(loadingModal, {
                backdrop: 'static',
                keyboard: false
            });

            // Tampilkan modal
            loadingModalInstance.show();

            // Simpan timestamp untuk deteksi
            window.loadingStartTime = Date.now();
        }

        function hideLoadingModal() {
            if (loadingModalInstance) {
                loadingModalInstance.hide();
                loadingModalInstance = null;
            }
        }

        // Fungsi untuk mendeteksi kapan PDF selesai didownload
        function checkIfDownloadComplete() {
            // Cek apakah sudah lebih dari 3 detik sejak loading dimulai
            if (window.loadingStartTime && Date.now() - window.loadingStartTime > 3000) {
                hideLoadingModal();
            }
        }

        // Event listener untuk window sebelum unload (saat redirect)
        window.addEventListener('beforeunload', function() {
            // Loading modal akan otomatis hilang saat halaman di-reload
            if (loadingModalInstance) {
                // Tidak perlu hide di sini, karena akan otomatis hilang
            }
        });

        // Event listener untuk ketika halaman dimuat ulang (setelah export)
        window.addEventListener('pageshow', function(event) {
            // Jika ini adalah navigasi kembali (seperti setelah download)
            if (event.persisted || window.performance && window.performance.navigation.type === 2) {
                // Sembunyikan loading modal jika masih terbuka
                hideLoadingModal();
            }
        });

        // Cek secara periodik apakah loading sudah terlalu lama
        setInterval(checkIfDownloadComplete, 1000);

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap Toasts
            const toastElList = document.querySelectorAll('.toast');
            const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));

            // Auto-hide toasts after 5 seconds
            toastList.forEach(toast => toast.show());

            // Auto-hide alerts setelah 5 detik
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert:not(.toast .alert)');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Buat instance modal export
            exportModalInstance = new bootstrap.Modal(document.getElementById('exportModal'));

            // Buat instance modal single export
            singleExportModalInstance = new bootstrap.Modal(document.getElementById('singleExportModal'));

            // Event listener untuk modal loading saat ditutup
            const loadingModal = document.getElementById('loadingModal');
            loadingModal.addEventListener('hidden.bs.modal', function() {
                loadingModalInstance = null;
            });

            // Event listener untuk modal export saat ditutup
            const exportModal = document.getElementById('exportModal');
            exportModal.addEventListener('hidden.bs.modal', function() {
                // Reset button state
                const confirmBtn = document.getElementById('confirmExportBtn');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="ri-file-pdf-line me-1"></i> Export PDF';
            });
        });
    </script>
    <script>
        // Script untuk modal lihat tanda tangan
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Modal lihat tanda tangan
            const viewSignatureModal = document.getElementById('viewSignatureModal');
            if (viewSignatureModal) {
                viewSignatureModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const nama = button.getAttribute('data-nama');
                    const ttdBase64 = button.getAttribute('data-ttd-base64');

                    // Update modal content
                    document.getElementById('signatureOwnerName').textContent = nama;

                    const signatureImage = document.getElementById('signatureImage');
                    const signaturePlaceholder = document.getElementById('signaturePlaceholder');

                    if (ttdBase64 && ttdBase64.trim() !== '') {
                        // Show signature image
                        signatureImage.src = ttdBase64;
                        signatureImage.style.display = 'block';
                        signaturePlaceholder.style.display = 'none';

                        // Enable download button
                        document.getElementById('downloadSignatureBtn').disabled = false;
                    } else {
                        // Show placeholder
                        signatureImage.style.display = 'none';
                        signaturePlaceholder.style.display = 'block';

                        // Disable download button
                        document.getElementById('downloadSignatureBtn').disabled = true;
                    }
                });

                // Clear modal when hidden
                viewSignatureModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('signatureImage').src = '';
                    document.getElementById('signatureImage').style.display = 'none';
                    document.getElementById('signaturePlaceholder').style.display = 'block';
                });
            }

            // Download signature button
            document.getElementById('downloadSignatureBtn')?.addEventListener('click', function() {
                const signatureImage = document.getElementById('signatureImage');
                const nama = document.getElementById('signatureOwnerName').textContent;

                if (signatureImage.src) {
                    // Create a temporary link
                    const link = document.createElement('a');
                    link.href = signatureImage.src;
                    link.download = 'ttd-' + nama.replace(/\s+/g, '-').toLowerCase() + '.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            });
        });
    </script>

    <style>
        /* TTD Badge styling */
        .badge.bg-success {
            background-color: #198754 !important;
            color: white !important;
            padding: 4px 8px;
            font-size: 12px;
        }

        .badge.bg-secondary {
            background-color: #6c757d !important;
            color: white !important;
            padding: 4px 8px;
            font-size: 12px;
        }

        /* View signature button */
        .btn-sm.btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
            color: white;
            transition: all 0.3s;
        }

        .btn-sm.btn-info:hover {
            background-color: #0ba8ca;
            border-color: #0a9dbe;
            transform: translateY(-1px);
        }

        /* Signature modal */
        #signatureImageContainer {
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px dashed #dee2e6;
        }

        #signaturePlaceholder i {
            font-size: 3rem;
            color: #adb5bd;
        }




        /* Custom Toast Styling */
        .toast {
            margin-bottom: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.375rem;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-success {
            background-color: #198754 !important;
        }

        .toast-error {
            background-color: #dc3545 !important;
        }

        .toast .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Loading Modal Styling */
        #loadingModal .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        #loadingModal .spinner-border {
            width: 4rem;
            height: 4rem;
            border-width: 0.25em;
            color: #0d6efd;
        }

        #loadingModal .modal-body {
            padding: 2rem;
        }

        /* Card hover effect */
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* Button hover effects */
        .btn-danger {
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .btn-info {
            transition: all 0.3s ease;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }

        /* Table row hover effect */
        .table-hover tbody tr {
            transition: background-color 0.2s;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        /* Modal styling */
        .modal-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }

        /* Animation for modal */
        .modal.fade .modal-dialog {
            transform: translateY(-50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        /* Disabled button styling */
        #confirmExportBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Loading animation for buttons */
        .btn-loading {
            position: relative;
        }

        .btn-loading .spinner-border {
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -0.75rem;
            margin-top: -0.75rem;
        }

        .btn-loading span {
            visibility: hidden;
        }
    </style>
    @endsection