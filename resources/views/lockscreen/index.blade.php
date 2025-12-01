@extends('layouts.auth')
@section('mycontent')
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock Screen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .auth-container {
            display: flex;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .info-section {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
        }

        .carousel-container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .carousel-content {
            width: 100%;
            max-width: 800px;
        }

        .system-name {
            text-align: center;
            margin-bottom: 2rem;
        }

        .system-logo {
            max-width: 300px;
            height: auto;
        }

        .carousel-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 1rem;
            border-radius: 5px;
            bottom: 20px;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title-icon {
            max-width: 200px;
            height: auto;
        }

        .login-subtitle {
            color: #666;
            margin-top: 1rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 500;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .photo-gallery {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .gallery-photo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 5px;
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .input-group-text {
            cursor: pointer;
            background-color: #f8f9fa;
        }

        .kegiatan-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
            }

            .info-section {
                display: none;
            }

            .login-section {
                padding: 1rem;
            }
        }
    </style>
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
                    <p class="login-subtitle">Masuk ke Sistem Assesmen</p>

                    <!-- Info Kegiatan -->
                    <div class="kegiatan-info">
                        <h6 class="mb-1">Kegiatan Aktif:</h6>
                        <p class="mb-1"><strong>{{ $kegiatan->kegiatan_name }}</strong></p>
                        <small class="text-muted">
                            Periode: {{ date('d/m/Y', strtotime($kegiatan->start_date)) }} - {{ date('d/m/Y', strtotime($kegiatan->end_date)) }}
                        </small>
                    </div>
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

                @if(session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('lockscreen.unlock') }}" method="POST">
                    @csrf
                    <!-- Input hidden untuk kegiatan_id -->
                    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan_id }}">

                    <div class="mb-3">
                        <label for="user_name" class="form-label">
                            <i class="ri-user-3-line me-1"></i> NIP / Username
                        </label>
                        <input type="text" class="form-control" id="user_name" name="user_name"
                            placeholder="Masukkan NIP Anda" value="{{ old('user_name') }}" required
                            autofocus>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label">
                                <i class="ri-key-2-line me-1"></i> Token Kegiatan
                            </label>
                            <small>
                                <a href="#" id="show-token-info" data-bs-toggle="modal" data-bs-target="#tokenInfoModal">
                                    <i class="ri-information-line"></i> Info Token
                                </a>
                            </small>
                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukkan token kegiatan" required>
                            <span class="input-group-text password-toggle" id="password-toggle">
                                <i class="ri-eye-fill"></i>
                            </span>
                        </div>
                        <small class="text-muted">Token diberikan oleh administrator kegiatan</small>
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn btn-login text-white" type="submit">
                            <i class="ri-login-box-line me-1"></i> Masuk ke Assesmen
                        </button>
                    </div>
                </form>

                <!-- Galeri Logo di Bawah Form Login -->
                <div class="photo-gallery">
                    <img src="{{ asset('images/logo.jpg') }}" class="gallery-photo" alt="Logo 1">
                    <img src="{{ asset('images/logoPendidikan.png') }}" class="gallery-photo" alt="Logo Pendidikan">
                    <img src="{{ asset('images/logoRamah.png') }}" class="gallery-photo" alt="Logo Ramah">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Info Token -->
    <div class="modal fade" id="tokenInfoModal" tabindex="-1" aria-labelledby="tokenInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tokenInfoModalLabel">
                        <i class="ri-information-line me-2"></i> Informasi Token
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Token Kegiatan</strong> adalah kode unik yang diberikan untuk mengakses sistem assesmen.</p>
                    <p>Token ini biasanya diberikan oleh:</p>
                    <ul>
                        <li>Administrator kegiatan</li>
                        <li>Panitia penyelenggara</li>
                        <li>Atasan langsung</li>
                    </ul>
                    <div class="alert alert-info">
                        <i class="ri-alert-line me-2"></i>
                        Jika Anda tidak memiliki token, silakan hubungi administrator kegiatan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle visibility password
        document.getElementById('password-toggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ri-eye-fill');
                icon.classList.add('ri-eye-off-fill');
            } else {
                passwordInput.type = 'password';
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

        // Validasi form dengan sweet alert atau konfirmasi sederhana
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('user_name').value;
            const password = document.getElementById('password').value;

            if (!username || !password) {
                e.preventDefault();
                alert('Harap isi NIP dan Token terlebih dahulu!');
            }
        });

        // Auto fokus ke input username
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('user_name').focus();
        });
    </script>
</body>

</html>