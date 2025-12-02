<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock Screen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<style>
    :root {
        --primary-color: #2c7be5;
        --secondary-color: #1a5bb8;
        --success-color: #2c7be5;
        /* Mengubah warna success menjadi biru */
        --light-bg: #f9fbfd;
    }

    body {
        background-color: var(--light-bg);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .auth-container {
        display: flex;
        min-height: 100vh;
    }

    .info-section {
        flex: 1;
        background: linear-gradient(135deg, #2c7be5 0%, #1a5bb8 100%);
        color: white;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .carousel-container {
        position: relative;
    }

    .carousel-logo {
        position: absolute;
        top: 40px;
        left: 70px;
        /* ⬅️ LOGO DIGESER KE KANAN */
        z-index: 20;
    }

    .system-logo {
        width: 250px !important;
        height: auto;
    }

    .lock-logo-wrapper {
        position: absolute;
        top: 40px;
        /* geser atas */
        left: 70px;
        /* geser kanan */
        z-index: 20;
    }

    .lock-logo {
        width: 260px !important;
        height: auto !important;
    }


    .carousel-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .login-form-container {
        width: 100%;
        max-width: 400px;
    }

    .system-name {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .system-tagline {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .carousel-item {
        height: 400px;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .carousel-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .carousel-caption {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 0 0 10px 10px;
        padding: 1rem;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.8;
    }

    .carousel-control-prev {
        left: 10px;
    }

    .carousel-control-next {
        right: 10px;
    }

    .carousel-indicators {
        bottom: -30px;
    }

    .carousel-indicators button {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin: 0 5px;
    }

    .info-footer {
        padding: 1.5rem 2rem;
        background: rgba(0, 0, 0, 0.2);
        margin-top: auto;
    }

    .info-footer p {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .login-logo {
        margin-top: -30px !important;
        text-align: center;
    }


    .login-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }

    .login-subtitle {
        margin-top: 3px !important;
        margin-bottom: 20px;
    }


    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .btn-login {
        background-color: var(--primary-color);
        /* Menggunakan primary color (biru) */
        border: none;
        padding: 0.75rem;
        font-weight: 500;
    }

    .btn-login:hover {
        background-color: var(--secondary-color);
        /* Warna biru lebih gelap saat hover */
    }

    .password-toggle {
        cursor: pointer;
        color: var(--secondary-color);
    }

    .photo-gallery {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
        gap: 10px;
    }

    .gallery-photo {
        width: 75px;
        height: 75px;
        object-fit: contain;
        background: transparent;
        border-radius: 50%;
        padding: 4px;
        border: none;
        margin: 0;
    }

    .gallery-photo:hover {
        transform: scale(1.05);
        border-color: var(--primary-color);
    }

    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.3);
        color: #721c24;
    }


    .system-name {
        margin: 0;
        padding: 0;
        font-size: 0;
        line-height: 0;
    }


    @media (max-width: 992px) {
        .auth-container {
            flex-direction: column;
        }

        .info-section {
            min-height: 50vh;
        }

        .carousel-item {
            height: 300px;
        }

        .login-section {
            padding: 2rem 1rem;
        }
    }



    .auth-container {
        display: flex;
        min-height: 100vh;
        background: #f8f9fa;
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
        width: 250px !important;
        /* paksa ukuran berubah */
        height: auto !important;
    }

    .login-subtitle {
        color: #666;
        margin-top: 1rem;
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

<body>
    <div class="auth-container">

        <!-- Bagian Kiri -->
        <div class="info-section">
            <div class="carousel-container">
                <div class="carousel-content">
                    <h1 class="system-name" style="margin-top: -100px;">
                        <img src="{{ asset('build/images/logobgtkPutih.png') }}" class="system-logo" alt="Logo"
                            style="width: 250px; height: auto;">
                    </h1>

                    <div id="infoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="0"
                                class="active"></button>
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
                                    <p>Menggunakan teknologi terkini</p>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="{{ asset('images/3.jpg') }}" class="carousel-image">
                                <div class="carousel-caption">
                                    <h5>Diskusi Produktif</h5>
                                    <p>Berbagi ide untuk solusi</p>
                                </div>
                            </div>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#infoCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#infoCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan -->
        <div class="login-section" style="margin-top: 80px;">
            <div class="login-form-container">

                <h2 class="login-title">
                    <img src="{{ asset('build/images/logo-dark.png') }}" style="width:400px">
                </h2>
                <p class="login-subtitle">Masuk ke Sistem Assesmen</p>

                <!-- Info Kegiatan -->
                <div class="kegiatan-info">
                    <h6 class="text-primary">
                        <i class="ri-calendar-event-line"></i> Kegiatan Aktif
                    </h6>
                    <h5>{{ $kegiatan->kegiatan_name }}</h5>
                    <small class="text-muted">
                        {{ date('d/m/Y', strtotime($kegiatan->start_date)) }}
                        -
                        {{ date('d/m/Y', strtotime($kegiatan->end_date)) }}
                    </small>
                </div>

                <!-- Error -->
                @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <!-- Form -->
                <form action="{{ route('lockscreen.unlock') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan_id }}">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Token Kegiatan</label>

                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control form-control-lg"
                                placeholder="Masukkan token" required autofocus>

                            <span class="input-group-text password-toggle" id="password-toggle">
                                <i class="ri-eye-fill"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Masuk
                    </button>
                </form>

                <div class="photo-gallery mt-3">
                    <img src="{{ asset('images/logoPendidikan.png') }}" width="80">
                    <img src="{{ asset('images/logoRamah.png') }}" width="80">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('password-toggle').onclick = function() {
            let input = document.getElementById('password');
            let icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ri-eye-fill', 'ri-eye-off-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('ri-eye-off-fill', 'ri-eye-fill');
            }
        };
    </script>

</body>

</html>