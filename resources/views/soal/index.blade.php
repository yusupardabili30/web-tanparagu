@extends('layouts.main')
@section('mycss')
<style>
        .studi-kasus-container {
            max-width: 100%;
            margin: 0 auto;
        }
        .studi-kasus-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
            animation: slideIn 0.5s ease-in-out;
        }
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .studi-kasus-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .studi-kasus-content {
            padding: 20px;
        }
        .soal-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }
        .soal-teks {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .pilihan-list {
            list-style: none;
            padding: 0;
        }
        .pilihan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            margin-bottom: 5px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .pilihan-teks {
            flex: 1;
            font-size: 14px;
        }
        .bobot {
            font-weight: bold;
            color: #28a745;
            background: #d4edda;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .aksi {
            margin-top: 20px;
            text-align: right;
        }
        button {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
            transition: background 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .edit-soal-btn {
            background: #ffc107;
            color: #212529;
        }
        .edit-soal-btn:hover {
            background: #e0a800;
        }
        .add-soal-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background: #28a745;
        }
        .add-soal-btn:hover {
            background: #218838;
        }
        /* Modal Edit */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-in-out;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 60px;
        }
        .pilihan-edit {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }
        .pilihan-edit input[type="text"] {
            flex: 1;
        }
        .pilihan-edit input[type="number"] {
            width: 80px;
        }
        </style>
@endsection
@section('mycontent')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Daftar {{ $tittle }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $tittle }}</a></li>
                        <li class="breadcrumb-item active">Daftar {{ $tittle }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="ticketsList">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1"></h5>
                        <div class="flex-shrink-0">
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-danger add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Tambah {{ $tittle }}</button>
                                <button class="btn btn-secondary" id="remove-actions" onclick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end card-body-->
                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle mb-0" id="ticketTable">
                            {{-- {{dd($data)}} --}}
                            <tbody class="list form-check-all" id="ticket-list-data">
                                @foreach ($data as $row)
                                <tr>
                                    <td>
                                        <div class="studi-kasus-container" id="studiKasusContainer">
                                            <div class="studi-kasus-card">
                                                <div class="studi-kasus-header">{{ $row->sub_indikator_code . ' ' .$row->sub_indikator->sub_indikator_name }}</div>                                                
                                                <div class="studi-kasus-content">
                                                    <p>{{$row->case}}</p>
                                                    <div class="soal-item">
                                                        @foreach ($row->soal as $item)                                                           
                                                            <div class="soal-teks">Soal {{ $item->no_urut }} : {{ $item->soal }}</div>
                                                             @foreach ($item->soal_jawaban as $item)
                                                            <ul class="pilihan-list">
                                                                <li class="pilihan-item">
                                                                   
                                                                        <div class="pilihan-teks">{{$item->pilihan_jawaban}}</div>
                                                                        <div class="bobot">Bobot: {{$item->bobot}}</div>
                                                                    
                                                                </li>                                                            
                                                            </ul>
                                                            @endforeach
                                                        @endforeach
                                                        <button class="edit-soal-btn">Edit Soal</button>
                                                    </div>                                                    
                                                    <div class="aksi">
                                                        <button>Edit Studi Kasus</button>
                                                        <button>Hapus Studi Kasus</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="add-soal-btn">Tambah Studi Kasus Baru</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                         {{-- Pagination --}}
                        {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}

                    </div>

                    <!-- Modal -->
                    <div class="modal fade flip" id="deleteOrder" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4>You are about to delete ?</h4>
                                        <p class="text-muted fs-14 mb-4">Deleting will remove all of your information from our database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <input type="text" id="tusi_id" style="display: none">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</button>
                                            <button class="btn btn-danger" id="delete-record">Yes, Delete It</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end modal -->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    {{-- @include('partials.modal.kegiatan.add') --}}
</div>
@endsection

@section('sipproja-js')
    <script>
        @if (session('success'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'data inserted successfully!',
                showConfirmButton: false,
                timer: 2000,
                showCloseButton: true
            });
        @endif

        //start add btn
        $('.add-btn').on('click', function () {
            $('#modal-tittle').text('Tambah Kegiatan');    
            $('#exampleModalLabel').text('Tambah Kegiatan');    
             $('#add-btn').text('Tambah Kegiatan'); 
            $('#add-btn').show();
            $('input[name="kegiatan_id"]').val('');
            $('input[name="kegiatan"]').val('');
        });
        // end add

        // start btn-view
        $('.btn-view').on('click', function () {
        let kegiatan_id = $(this).data('id'); // pastikan ini sesuai HTML
        $('#modal-tittle').text('View Data');    
        $('#exampleModalLabel').text('View Data');    
        $('#add-btn').hide();
        $.get('/kegiatan/get/' + kegiatan_id , function (data) {
            $('input[name="kegiatan_id"]').val(data.kegiatan_id);
            $('input[name="kegiatan"]').val(data.kegiatan_name);
            $('#showModal').modal('show');
            });
        });
        // end btn-view

        // edit
        $('.btn-edit').on('click', function () {
        let kegiatan_id = $(this).data('id'); // pastikan ini sesuai HTML
        // alert(kegiatan_id);
        $('#modal-tittle').text('Ubah Kegiatan');    
        $('#exampleModalLabel').text('Ubah Kegiatan');    
        $('#add-btn').text('Ubah Kegiatan');  
        $('#add-btn').show();
        $.get('/kegiatan/get/' + kegiatan_id , function (data) {
            $('input[name="kegiatan_id"]').val(data.kegiatan_id);
            $('input[name="kegiatan"]').val(data.kegiatan_name);
            $('#showModal').modal('show');
            });
        });

        //start
        // Saat tombol "Hapus" (yang buka modal) diklik
        $('.btn-delete').on('click', function () {
            var id = $(this).data('id'); // Ambil ID dari data-id
            $('#kegiatan_id').val(id);     // Masukkan ke input hidden
        });

        // Saat tombol "Yes, Delete It" ditekan di modal
        $('#delete-record').on('click', function () {
            var id = $('#tusi_id').val(); 
            $.ajax({
                url: "/tusi/delete/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // CSRF token wajib di Laravel
                },
                success: function (response) {
                    // Tampilkan pesan sukses dengan SweetAlert
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message || 'Data deleted successfully!',
                        showConfirmButton: false,
                        timer: 2000,
                        showCloseButton: true
                    });

                    // Sembunyikan modal
                    $('#deleteOrder').modal('hide');

                    // Refresh halaman atau reload data tabel
                    location.reload(); // atau bisa juga: fetchResults();
                },
                error: function (xhr) {
                    // Tampilkan pesan error dengan SweetAlert
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal menghapus data',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus.',
                        showConfirmButton: true
                    });

                    console.error(xhr.responseText);
                }
            });
        });
        //end
    </script>
@endsection
