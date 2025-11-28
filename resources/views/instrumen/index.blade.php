@extends('layouts.main-user')
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

                        <!-- Judul Studi Kasus -->
                        <div class="col-lg-12 aos-init aos-animate fs-4 fw-bold mb-2" data-aos="fade-up">
                            Studi Kasus
                        </div>

                        <!-- Isi Studi Kasus -->
                        <div class="col-lg-12 aos-init aos-animate fs-5 mb-4" data-aos="fade-up">
                            Di sebuah SMP, seorang guru IPA menyadari bahwa hasil belajar siswa pada
                            materi “Sistem Peredaran Darah” menurun drastis. Banyak siswa mengaku tidak memahami
                            konsep karena pembelajaran sebelumnya hanya menggunakan ceramah tanpa media visual.
                            Selain itu, guru jarang memberikan kesempatan bagi siswa untuk berdiskusi atau melakukan
                            eksperimen sederhana. Kondisi ini membuat siswa pasif dan kurang termotivasi untuk
                            belajar. Guru diminta kepala sekolah memperbaiki strategi pembelajaran menjadi lebih
                            interaktif dan sesuai kebutuhan siswa.
                        </div>

                        <!-- Soal Pilihan Ganda -->
                        <div class="col-lg-12 aos-init aos-animate fs-4 fw-semibold mb-2" data-aos="fade-up">
                            Soal
                        </div>

                        <div class="col-lg-12 aos-init aos-animate fs-5 mb-3" data-aos="fade-up">
                            <p><strong>1. Apa permasalahan utama yang terjadi dalam pembelajaran IPA pada kasus tersebut?</strong></p>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal1" id="soal1A" value="A">
                                <label class="form-check-label" for="soal1A">
                                    A. Siswa terlalu banyak bermain di kelas
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal1" id="soal1B" value="B">
                                <label class="form-check-label" for="soal1B">
                                    B. Siswa tidak memahami materi karena metode pembelajaran kurang variatif
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal1" id="soal1C" value="C">
                                <label class="form-check-label" for="soal1C">
                                    C. Guru memberikan tugas yang terlalu banyak
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal1" id="soal1D" value="D">
                                <label class="form-check-label" for="soal1D">
                                    D. Siswa tidak menyukai pelajaran IPA
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Jawab</button>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
    </div> <!-- end col -->
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
