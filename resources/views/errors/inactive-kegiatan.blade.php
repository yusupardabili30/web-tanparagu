<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock Screen - Expired</title>
    <link rel="stylesheet" href="{{ asset('build/css/login.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

    <style>
        /* Hilangkan semua rounded */
        .no-radius,
        .no-radius * {
            border-radius: 0 !important;
        }

        /* ======================= */
        /* FIX POSISI BADUY (NAIK) */
        /* ======================= */
        .info-section.baduy-bg {
            position: relative;
            margin-top: -60px !important;
        }

        .info-section,
        .carousel-container,
        .text-white.mt-5.pt-5 {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        /* ======================= */
        /* BUTTON STYLE */
        /* ======================= */

        /* Row tombol horizontal */
        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        /* Samain lebar tombol */
        .btn-row .btn-login,
        .btn-row .btn-soft-outline {
            flex: 1;
        }

        .btn-login {
            background-color: #1a5bb8;
            color: #fff !important;
            padding: 14px 18px;
            font-size: 16px;
            border-radius: 12px !important;
            font-weight: 600;
            border: none;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none !important;
        }

        .btn-login:hover {
            opacity: .9;
            color: #fff !important;
            text-decoration: none !important;
        }

        .btn-soft-outline {
            background: transparent;
            border: 2px solid #1a5bb8;
            padding: 14px 18px;
            font-size: 16px;
            border-radius: 12px !important;
            color: #1a5bb8;
            font-weight: 600;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none !important;
        }

        .btn-soft-outline:hover {
            background-color: #1a5bb8;
            color: #fff;
            text-decoration: none !important;
        }

        /* ======================= */
        /* ERROR ICON MERAH KUAT */
        /* ======================= */
        .error-icon {
            color: #e63946 !important;
        }

        /* ======================= */
        /* BADUY BG */
        /* ======================= */
        .baduy-bg {
            background-image: url("/build/images/baduy.jpg");
            background-size: 180px;
            background-repeat: repeat;
            background-color: #1a5bb8;
            position: relative;
            color: white;
        }

        .baduy-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("/build/images/baduy.jpg");
            background-size: 200px;
            background-repeat: repeat;
            opacity: 0.4;
            z-index: 0;
        }

        .baduy-bg * {
            position: relative;
            z-index: 1;
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


        /* Baduy full & turunin dikit biar ga kepotong */
        .info-section.baduy-bg {
            margin-top: 0 !important;
            padding: 25px 15px !important;
        }

        /* Konten kanan rapetin */
        .login-section {
            margin-top: 10px !important;
        }

        /* Judul kiri */
        .info-section .text-white {
            margin-top: 40px !important;
        }

        /* Button jadi vertical + full width */
        .btn-row {
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-row button,
        .btn-row a {
            width: 100% !important;
            min-width: 100% !important;
            flex: unset !important;
        }

        /* Skala teks */
        .login-subtitle {
            font-size: 18px !important;
        }

        .text-center.mb-3 i {
            font-size: 55px !important;
        }

        /* Error code spacing */
        .login-form-container p[style*="font-size:13px"] {
            margin-top: 20px !important;
        }
    </style>


    <div class="auth-container no-radius">

        <!-- ====================== -->
        <!-- BAGIAN KIRI – BADUY BG -->
        <!-- ====================== -->
        <div class="info-section baduy-bg no-radius">
            <div class="carousel-container">

                <div class="text-white" style="text-align:left; max-width:450px; margin-top:70px;">
                    <h1 class="fw-bold" style="font-size:38px; color:white;">
                        KEGIATAN TIDAK AKTIF
                    </h1>

                    <p class="mt-3" style="font-size:18px; opacity:0.9;">
                        Maaf, kegiatan ini sudah tidak aktif atau tidak ditemukan.
                    </p>

                    <div style="
                    background: rgba(255,255,255,0.15);
                    border-left:4px solid #ffb3c7;
                    padding:14px 18px;
                    border-radius:12px;
                    margin-top:20px;
                ">
                        <p class="mb-0 text-white" style="line-height:1.5;">
                            {{ $message ?? 'Kegiatan tidak aktif atau token sudah kedaluwarsa.' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>


        <!-- ====================== -->
        <!-- BAGIAN KANAN – KONTEN  -->
        <!-- ====================== -->
        <div class="login-section" style="margin-top:40px;">
            <div class="login-form-container">

                <div class="login-logo">
                    <h2 class="login-title">
                        <img src="{{ asset('build/images/logo-dark.png') }}"
                            style="width: 250px;">
                    </h2>
                </div>

                <p class="login-subtitle" style="font-size:22px; font-weight:600; text-align:center;">
                    Informasi Kegiatan
                </p>

                <div class="text-center mb-3">
                    <i class="ri-error-warning-line error-icon" style="font-size:70px;"></i>
                </div>

                <p class="text-center text-muted" style="font-size:16px;">
                    Akses ke kegiatan ini tidak dapat dilakukan karena status tidak aktif.
                </p>

                <div class="btn-row">
                    <a href="{{ url()->previous() }}" class="btn-soft-outline">
                        <i class="ri-arrow-left-line me-1"></i> Halaman Sebelumnya
                    </a>


                </div>
                <div>
                    <p class="text-center text-muted"
                        style="font-size:13px; color:red; margin-top:35px;">
                        Kode Error: <code>INACTIVE_KEGIATAN_404</code>
                    </p>
                </div>



            </div>
        </div>

    </div>