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
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 2rem;
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
        bottom: -40px;
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

    .system-logo {
        width: 200px;
        height: auto;
        object-fit: contain;
        display: block;
        margin-bottom: 15px;
        /* 🔥 JARAK KE BAWAH */
    }

    .system-name {
        margin: 0;
        padding: 0;
        font-size: 0;
        line-height: 0;
    }

    .login-title-icon {
        width: 300px;
        height: auto;
        object-fit: contain;
        display: inline-block;
        margin: 0;
    }


    .login-title {
        font-size: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 0 !important;
        text-align: center;
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