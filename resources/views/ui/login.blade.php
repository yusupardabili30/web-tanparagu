@extends('layouts.auth')
@section('mycontent')
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
                        <img src="{{ asset('build/images/logobgtkPutih.png') }}"
                            class="system-logo"
                            style="width: 250px; height: auto;"
                            alt="Logo">
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
            <div class="login-section" style="margin-top:40px;">
                <div class="login-form-container">
                    <div class="login-logo">
                        <h2 class="login-title" style="margin-bottom: 40px;">
                            <img src="{{ asset('build/images/logo-dark.png') }}" 
                                class="login-title-icon" 
                                alt="Login Icon"
                                style="width: 400px; height:auto;">
                        </h2>

                        <p class="login-subtitle" style="margin-bottom: 50px;">
                            Masuk ke Sistem Assesmen
                        </p>

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

                <form action="{{ route('authenticate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter username" value="{{ old('user_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                            <span class="input-group-text password-toggle" id="password-toggle">
                                <i class="ri-eye-fill"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn btn-login text-white" type="submit">Masuk</button>
                    </div>
                </form>

                <!-- Galeri Foto di Bawah Form Login -->
                <div class="photo-gallery">
                    <img src="{{ asset('images/logoPendidikan.png') }}" class="gallery-photo" alt="Photo 2">
                    <img src="{{ asset('images/logoRamah.png') }}" class="gallery-photo" alt="Photo 3">
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
    </script>
</body>

</html>