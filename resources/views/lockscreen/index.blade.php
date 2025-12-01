@extends('layouts.auth')
@section('mycontent')
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock Screen - {{ $kegiatan->kegiatan_name ?? 'Kegiatan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="auth-container">
        <!-- Bagian Kiri: Carousel -->
        <div class="info-section">
            <div class="carousel-container">
                <div class="carousel-content">
                    <h1 class="system-name">
                        <img src="{{ asset('images/logobgtkputih.png') }}" class="system-logo" alt="Logo">
                    </h1>

                    <div id="infoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/1.jpg') }}" class="carousel-image" alt="Team Collaboration">
                                <div class="carousel-caption">
                                    <h5>Kolaborasi Tim</h5>
                                    <p>Bekerja sama untuk mencapai tujuan bersama</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/2.jpg') }}" class="carousel-image" alt="Technology">
                                <div class="carousel-caption">
                                    <h5>Teknologi Modern</h5>
                                    <p>Menggunakan teknologi terkini untuk solusi terbaik</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/3.jpg') }}" class="carousel-image" alt="Meeting">
                                <div class="carousel-caption">
                                    <h5>Diskusi Produktif</h5>
                                    <p>Berbagi ide untuk inovasi yang lebih baik</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#infoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#infoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <!-- Info Kegiatan -->
                    <div class="kegiatan-info mt-4 p-3 bg-light rounded">
                        <h6 class="text-white mb-2">Informasi Kegiatan</h6>
                        <p class="text-white mb-1"><strong>Nama:</strong> {{ $kegiatan->kegiatan_name }}</p>
                        <p class="text-white mb-1"><strong>Periode:</strong>
                            {{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}
                        </p>
                        <p class="text-white mb-0"><strong>Status:</strong>
                            <span class="badge bg-success">Aktif</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Form Login -->
        <div class="login-section">
            <div class="login-form-container">
                <div class="login-logo">
                    <h2 class="login-title">
                        <img src="{{ asset('images/logobgtkhitam.png') }}" class="login-title-icon" alt="Login Icon">
                    </h2>
                    <p class="login-subtitle">Silahkan Masuk</p>
                    <p class="text-muted small">Kegiatan: <strong>{{ $kegiatan->kegiatan_name }}</strong></p>
                </div>

                <!-- Menampilkan pesan error jika ada -->
                @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('lockscreen.unlock') }}" method="POST" id="unlockForm">
                    @csrf
                    <!-- Tambahkan hidden field untuk encode_kegiatan_id -->
                    <input type="hidden" name="encode_kegiatan_id" value="{{ $encode_kegiatan_id }}">

                    <div class="mb-3">
                        <label for="user_name" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user_name" name="user_name"
                            placeholder="Masukkan username" value="{{ old('user_name') }}"
                            required autofocus>
                        <div class="form-text">Gunakan username yang terdaftar</div>
                    </div>

                    <div class="mb-3">
                        <label for="instrumen_token" class="form-label">Token Kegiatan</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="instrumen_token" name="instrumen_token"
                                placeholder="Masukkan token kegiatan" required>
                            <span class="input-group-text password-toggle" id="token-toggle" style="cursor: pointer;">
                                <i class="ri-eye-fill"></i>
                            </span>
                        </div>
                        <div class="form-text">Token didapatkan dari panitia kegiatan</div>
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn btn-login text-white" type="submit" id="submitBtn">
                            <span id="submitText">Buka Kunci</span>
                            <div id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Galeri Foto di Bawah Form Login -->
                <div class="photo-gallery">
                    <img src="{{ asset('images/logo.jpg') }}" class="gallery-photo" alt="Photo 1">
                    <img src="{{ asset('images/logoPendidikan.png') }}" class="gallery-photo" alt="Photo 2">
                    <img src="{{ asset('images/logoRamah.png') }}" class="gallery-photo" alt="Photo 3">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle visibility token
        document.getElementById('token-toggle').addEventListener('click', function() {
            const tokenInput = document.getElementById('instrumen_token');
            const icon = this.querySelector('i');

            if (tokenInput.type === 'password') {
                tokenInput.type = 'text';
                icon.classList.remove('ri-eye-fill');
                icon.classList.add('ri-eye-off-fill');
            } else {
                tokenInput.type = 'password';
                icon.classList.remove('ri-eye-off-fill');
                icon.classList.add('ri-eye-fill');
            }
        });

        // Auto slide carousel
        const myCarousel = document.getElementById('infoCarousel');
        const carousel = new bootstrap.Carousel(myCarousel, {
            interval: 4000,
            wrap: true
        });

        // Form submission handler
        document.getElementById('unlockForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            submitSpinner.classList.remove('d-none');
        });

        // Auto focus on username field
        document.getElementById('user_name').focus();
    </script>

    <style>
        .auth-container {
            display: flex;
            min-height: 100vh;
        }

        .info-section {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .carousel-container {
            max-width: 600px;
            width: 100%;
        }

        .system-logo {
            max-width: 200px;
            margin-bottom: 2rem;
        }

        .carousel-image {
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 5px;
            padding: 1rem;
        }

        .kegiatan-info {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px);
        }

        .login-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #f8f9fa;
        }

        .login-form-container {
            max-width: 400px;
            width: 100%;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .login-title-icon {
            max-width: 150px;
        }

        .login-subtitle {
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover:not(:disabled) {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-login:disabled {
            opacity: 0.6;
        }

        .photo-gallery {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #dee2e6;
        }

        .gallery-photo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
            }

            .info-section,
            .login-section {
                flex: none;
            }

            .carousel-image {
                height: 300px;
            }
        }
    </style>
</body>

</html>