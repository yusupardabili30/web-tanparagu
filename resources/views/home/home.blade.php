@extends('layouts.main')
@section('mycontent')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Starter</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Starter</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="container" style="background-color: white">
                        <div class="row">
                            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center aos-init aos-animate" data-aos="fade-up">
                                <h2>Selamat Datang ! </h2>
                                <h5><i class="ri-user-line"></i>{{auth()->user()->nama}} </h5>
                                <h5> <span class="badge rounded-pill bg-success"><b>Role :
                                    @switch(auth()->user()->role_id)
                                        @case(1)
                                            Administrator
                                            @break
                                        @case(2)
                                            Supervisor
                                            @break
                                        @case(3)
                                            Katim
                                            @break
                                        @case(4)
                                            Tim
                                            @break
                                        @default
                                            Role Tidak Terdaftar
                                    @endswitch
                                    </b></span>
                                </h5>
                                <h1>TANPARAGU (Tautan Pemetaan Kebutuhan Belajar Guru) </h1>
                                <p>
                                    Tautan Pemetaan Kebutuhan Belajar Guru adalah aplikasi berbasis web yang dikembangkan oleh Balai Guru dan Tenaga Kependidikan Provinsi Banten untuk membantu satuan pendidikan dalam mengidentifikasi kebutuhan belajar guru secara lebih cepat, akurat, dan terstruktur. Aplikasi ini hadir sebagai bagian dari upaya BGTK Provinsi Banten dalam menyediakan layanan data yang transparan, mudah diakses, serta mendukung peningkatan kualitas pembelajaran di satuan pendidikan.
                                </p>
                                {{-- <div class="d-flex">
                                <a href="#about" class="btn-get-started">Get Started</a>
                                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a>
                                </div> --}}
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2 hero-img aos-init aos-animate" data-aos="zoom-out" data-aos-delay="100">
                                <img src="{{asset('images')}}/hero-img.png" class="img-fluid animated" alt="">
                            </div>
                        </div>
                    </div>
                    
                </div> <!-- end card-body-->
            </div>
        </div> <!-- end col-->
    </div>
    




</div>
<!-- container-fluid -->
@endsection
@section('sipproja-js')
<script>
  $(document).ready(function(){    
    //Swal.fire("SweetAlert2 is working!");
  });
</script>
@endsection
