{{-- resources/views/register/success.blade.php --}}
@extends('layouts.main-user')

@section('mycontent')
<style>
    .success-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .success-card {
        max-width: 500px;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="success-container">
            <div class="card success-card">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="ri-checkbox-circle-line text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="fw-bold mb-3" style="color: #1a3f6b;">Pendaftaran Berhasil!</h2>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <p class="text-muted mb-4">
                        Data Anda telah berhasil disimpan ke dalam sistem. Terima kasih telah mendaftar.
                    </p>

                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-information-line me-2"></i>
                            <div>
                                <h6 class="mb-1">Informasi</h6>
                                <p class="mb-0">Silakan tunggu konfirmasi lebih lanjut dari panitia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection