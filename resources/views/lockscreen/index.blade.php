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
=======
@endsection
