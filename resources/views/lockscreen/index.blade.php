@section('mycontent')
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock Screen - {{ $kegiatan->kegiatan_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- IMPORT CSS LOGIN + PROFIL -->
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">
    <style>
        /* Hilangkan semua rounded */
        .no-radius,
        .no-radius * {
            border-radius: 0 !important;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <!-- Bagian Kiri: Carousel + BG Baduy -->
        <div class="info-section baduy-bg no-radius">
            <div class="carousel-container" style="position: relative; z-index: 1;">
                <div class="carousel-content">

                    <h1 class="system-name"> <!-- margin-bottom: 0 -->
                        <img src="{{ asset('build/images/logobgtkPutih.png') }}"
                            class="system-logo"
                            style="width: 250px; height: auto; margin-bottom: 10px;"> <!-- margin-bottom kecil -->
                    </h1>

                    <div id="infoCarousel" class="carousel slide" data-bs-ride="carousel" style="margin-bottom: 77px;">

                        <div class=" carousel-indicators">
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
        <div class="login-section">
            <div class="login-form-container">
                <div class="login-logo pt-5"> <!-- pt-4 untuk padding top -->
                    <img src="{{ asset('build/images/logo-dark.png') }}" class="login-title-icon mb-3" alt="Logo Sistem">
                    <p class="login-subtitle">Masuk ke Sistem Assesmen</p>
                </div>

                <!-- Info Kegiatan Aktif -->
                <div class="kegiatan-info mb-3">
                    <div class="text-primary fw-semibold small mb-1">
                        <i class="ri-calendar-event-line align-baseline" style="font-size: 11px;"></i>
                        <span class="ms-1">KEGIATAN BERLANGSUNG</span>
                    </div>
                    <div class="fw-bold mb-1" style="font-size: 14px;">{{ $kegiatan->kegiatan_name }}</div>
                    <div class="text-muted small">
                        <i class="ri-calendar-2-line align-baseline" style="font-size: 11px;"></i>
                        <span class="ms-1">
                            Periode: {{ date('d/m/Y', strtotime($kegiatan->start_date)) }}
                            -
                            {{ date('d/m/Y', strtotime($kegiatan->end_date)) }}
                        </span>
                    </div>
                </div>


                <!-- Form Login -->
                <form id="login-form" method="POST">
                    @csrf
                    <!-- Kirim kegiatan_id ASLI (decoded) -->
                    <input type="hidden" id="kegiatan_id" name="kegiatan_id" value="{{ $kegiatan_id }}">

                    <!-- Field NIP -->
                    <div class="mb-3">
                        <label class="form-label required-field">Nomor Induk Pegawai (NIP)</label>
                        <input type="text" name="nip" id="nip" class="form-control form-control-lg"
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
                        <label class="form-label required-field">Token Kegiatan</label>
                        <div class="input-group">
                            <input type="password" name="token" id="tokenInput"
                                class="form-control form-control-lg"
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
                            <i class="ri-login-box-line me-2"></i> MASUK KE SISTEM
                        </button>
                    </div>
                </form>

                <!-- Logo Partner -->
                <div class="photo-gallery">
                    <img src="{{ asset('images/logoPendidikan.png') }}" class="gallery-photo" alt="Kemdikbud">
                    <img src="{{ asset('images/logoRamah.png') }}" class="gallery-photo" alt="Ramah Anak">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrasi -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-information-line fs-4 me-3"></i>
                            <div>
                                <h6 class="mb-1">NIP Belum Terdaftar</h6>
                                <p class="mb-0" id="modal-info-text">
                                    <!-- Akan diisi oleh JavaScript -->
                                </p>
                            </div>
                        </div>
                    </div>

                    <form id="register-form">
                        @csrf
                        <!-- Data akan diisi oleh JavaScript -->
                        <input type="hidden" id="reg_nip" name="nip">
                        <input type="hidden" id="reg_kegiatan_id" name="kegiatan_id">
                        <input type="hidden" id="reg_token" name="token">

                        <div class="row">
                            <!-- Data Identitas -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control"
                                    placeholder="16 digit NIK"
                                    maxlength="16">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">NUPTK</label>
                                <input type="text" name="nuptk" class="form-control"
                                    placeholder="16 digit NUPTK"
                                    maxlength="16">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">NPWP</label>
                                <input type="text" name="npwp" class="form-control"
                                    placeholder="15 digit NPWP"
                                    maxlength="20">
                            </div>

                            <!-- Data Pribadi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">NAMA LENGKAP</label>
                                <input type="text" name="nama" class="form-control"
                                    placeholder="Nama lengkap sesuai ijazah"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">JENIS KELAMIN</label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">LAKI-LAKI</option>
                                    <option value="P">PEREMPUAN</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">TEMPAT LAHIR</label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    placeholder="Kota tempat lahir"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">TANGGAL LAHIR</label>
                                <input type="date" name="tgl_lahir" class="form-control" required>
                            </div>

                            <!-- Data Jabatan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">JABATAN</label>
                                <select name="id_jabatan" class="form-control" required>
                                    <option value="">Pilih Jabatan</option>
                                    @foreach($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id_jabatan }}">
                                        {{ $jabatan->nama_jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kontak -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">EMAIL</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="email@contoh.com"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">NOMOR HP</label>
                                <input type="text" name="no_hp" class="form-control"
                                    placeholder="081234567890"
                                    required>
                            </div>

                            <!-- Data Tambahan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">AGAMA</label>
                                <select name="agama" class="form-control">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">PENDIDIKAN TERAKHIR</label>
                                <input type="text" name="pendidikan" class="form-control"
                                    placeholder="Contoh: S1 Pendidikan Matematika">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">INSTANSI/SEKOLAH</label>
                                <input type="text" name="instansi" class="form-control"
                                    placeholder="Nama instansi tempat bertugas">
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">ALAMAT RUMAH</label>
                                <textarea name="alamat_rumah" class="form-control" rows="3"
                                    placeholder="Alamat lengkap tempat tinggal"></textarea>
                            </div>
                        </div>

                        <!-- Informasi Form -->
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="ri-alert-line me-3"></i>
                                <div>
                                    <h6 class="mb-1">Perhatian!</h6>
                                    <p class="mb-0">
                                        Data yang ditandai dengan <span class="text-danger">*</span> wajib diisi.
                                        Pastikan data yang dimasukkan akurat dan valid.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-2"></i> BATAL
                    </button>
                    <button type="button" class="btn btn-primary" id="submit-register">
                        <i class="ri-save-line me-2"></i> SIMPAN DATA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
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

        // Format NIP input (hanya angka, maks 18 digit)
        document.getElementById('nip')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 18) {
                value = value.substring(0, 18);
            }
            e.target.value = value;
        });

        // Format No HP input (hanya angka)
        document.querySelector('input[name="no_hp"]')?.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // SweetAlert configuration
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

        // Handle login form submission
        document.getElementById('login-form')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const nip = document.getElementById('nip').value.trim();
            const token = document.getElementById('tokenInput').value.trim();
            const kegiatan_id = document.getElementById('kegiatan_id').value;

            // Validasi NIP
            if (nip.length !== 18) {
                showAlert('error', 'Kesalahan', 'NIP harus terdiri dari 18 digit angka!');
                return false;
            }

            // Show loading
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i> MEMPROSES...';
            submitBtn.disabled = true;

            // Send AJAX request
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
                        // Show success message before redirect
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Login berhasil, mengarahkan ke sistem...',
                            showConfirmButton: false,
                            timer: 1500,
                            willClose: () => {
                                // Redirect to PTK page
                                window.location.href = data.redirect_url;
                            }
                        });
                    } else {
                        if (data.show_register_modal) {
                            // Show registration modal
                            document.getElementById('modal-info-text').innerHTML =
                                `NIP <strong>${data.nip}</strong> belum terdaftar dalam sistem. Silakan lengkapi data diri Anda.`;

                            document.getElementById('reg_nip').value = data.nip;
                            document.getElementById('reg_kegiatan_id').value = data.kegiatan_id;
                            document.getElementById('reg_token').value = data.token;

                            const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                            registerModal.show();
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
                    // Restore button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Handle register form submission
        document.getElementById('submit-register')?.addEventListener('click', function() {
            const form = document.getElementById('register-form');
            const formData = new FormData(form);

            // Validate required fields
            const requiredFields = ['nama', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'id_jabatan', 'email', 'no_hp'];
            let isValid = true;
            let errorMessages = [];

            requiredFields.forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');

                    let fieldName = field.replace('_', ' ');
                    fieldName = fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
                    errorMessages.push(`${fieldName} wajib diisi`);
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    html: 'Harap lengkapi data berikut:<br><br>' + errorMessages.join('<br>'),
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#2c7be5'
                });
                return;
            }

            // Show loading in SweetAlert
            Swal.fire({
                title: 'Menyimpan Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send AJAX request
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
                        // Hide modal
                        const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                        registerModal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Registrasi Berhasil!',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2c7be5',
                            willClose: () => {
                                // Auto-refill NIP
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
                });
        });

        // Clear validation on input
        document.querySelectorAll('#register-form input, #register-form select').forEach(input => {
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Auto focus NIP field on page load
        document.addEventListener('DOMContentLoaded', function() {
            const nipField = document.getElementById('nip');
            if (nipField) {
                nipField.focus();
            }
        });
    </script>
</body>

</html>