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


</head>

<body>
    <div class="auth-container">

        <!-- ========================= -->
        <!-- BAGIAN KIRI: CAROUSEL     -->
        <!-- ========================= -->
        <div class="info-section">
            <div class="carousel-container">

                <!-- LOGO PUTIH -->
                <div class="lock-logo-wrapper">
                    <img src="{{ asset('build/images/logobgtkPutih.png') }}"
                        class="lock-logo"
                        alt="Logo BGTK Putih">
                </div>

                <div class="carousel-content">
                    <div id="infoCarousel" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="2"></button>
                        </div>

                        <div class="carousel-inner">

                            <div class="carousel-item active">
                                <img src="{{ asset('images/1.jpg') }}"
                                     class="carousel-image" alt="Slide 1">
                                <div class="carousel-caption">
                                    <h5>Kolaborasi Tim</h5>
                                    <p>Bekerja sama untuk mencapai tujuan bersama</p>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="{{ asset('images/2.jpg') }}"
                                     class="carousel-image" alt="Slide 2">
                                <div class="carousel-caption">
                                    <h5>Teknologi Modern</h5>
                                    <p>Menggunakan teknologi terkini untuk solusi terbaik</p>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="{{ asset('images/3.jpg') }}"
                                     class="carousel-image" alt="Slide 3">
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


        <!-- ========================= -->
        <!-- BAGIAN KANAN: LOGIN       -->
        <!-- ========================= -->
        <div class="login-section" style="margin-top:40px;">
            <div class="login-form-container">

                <!-- LOGO DARK -->
                <div class="login-logo">
                    <h2 class="login-title" style="margin-bottom:20px;">
                        <img src="{{ asset('build/images/logo-dark.png') }}"
                             class="login-title-icon"
                             alt="Logo Dark">
                    </h2>
                </div>

                <p class="login-subtitle" style="margin-bottom:15px;">
                    Masuk ke Sistem Assesmen
                </p>

                <!-- Info Kegiatan -->
                <div class="kegiatan-info mb-3">
                    <h6 class="mb-1 text-primary">
                        <i class="ri-calendar-event-line me-1"></i> Kegiatan Aktif:
                    </h6>
                    <h5 class="mb-2">Integrasi</h5>
                    <small class="text-muted">
                        <i class="ri-key-2-line me-1"></i> Token Required
                    </small>
                </div>

                <!-- Error -->
                @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Sukses -->
                @if(session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Form Token -->
                <form action="#" method="POST">
                    @csrf
                    <input type="hidden" name="kegiatan_id" value="2">

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="ri-key-2-line me-1"></i> Masukkan Token Kegiatan
                        </label>

                        <div class="input-group">
                            <input type="password"
                                   class="form-control form-control-lg"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan token kegiatan"
                                   required>

                            <span class="input-group-text password-toggle"
                                  id="password-toggle"
                                  style="cursor:pointer;">
                                <i class="ri-eye-fill"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button class="btn btn-login text-white btn-lg" type="submit">
                            <i class="ri-login-box-line me-1"></i> Masuk dengan Token
                        </button>
                    </div>
                </form>

                <!-- Gallery -->
                <div class="photo-gallery mt-4">
                    <img src="{{ asset('images/logoPendidikan.png') }}"
                         class="gallery-photo" alt="">
                    <img src="{{ asset('images/logoRamah.png') }}"
                         class="gallery-photo" alt="">
                </div>

            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password
        document.getElementById('password-toggle').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ri-eye-fill', 'ri-eye-off-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('ri-eye-off-fill', 'ri-eye-fill');
            }
        });
    </script>

</body>
</html>
