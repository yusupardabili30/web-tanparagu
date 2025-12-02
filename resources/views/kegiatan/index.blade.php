@extends('layouts.main')
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
                        <h5 class="card-title mb-0 flex-grow-1">{{ $tittle }}</h5>
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
                        <table class="table align-middle table-nowrap mb-0" id="ticketTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th class="sort" data-sort="id">ID</th>
                                    <th class="sort" data-sort="kegiatan">Kegiatan</th>
                                    <th class="sort" data-sort="kegiatan">Entitas</th>
                                    <th class="sort" data-sort="kegiatan">Token</th>
                                    <th class="sort" data-sort="kegiatan">url</th>
                                    <th class="sort" data-sort="kegiatan">status</th>
                                    <th class="sort" data-sort="action">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all" id="ticket-list-data">
                                @foreach ($data as $row)
                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="checkAll" value="option1">
                                        </div>
                                    </th>
                                    <td class="id"><a href="javascript:void(0);" onclick="ViewTickets(this)" data-id="001" class="fw-medium link-primary">{{ $row->kegiatan_id }}</a></td>
                                    <td class="client_name">{{ $row->kegiatan_name }}</td>
                                    <td class="client_name">{{ $row->entity }}</td>
                                    <td class="client_name">{{ $row->instrumen_token }}</td>
                                    {{-- <td class="client_name">
                                        <a href="{{ config('app.url') . '/' . $row->instrumen_url }}" target="_blank">
                                            {{ config('app.url') . '/' . $row->instrumen_url }}
                                        </a>
                                    </td> --}}
                                    <td class="client_name">
                                        <a href="{{ route('lockscreen.lock', ['encode_kegiatan_id'=> Hashids::encode($row->kegiatan_id)]) }}" target="_blank">
                                            {{ route('lockscreen.lock', ['encode_kegiatan_id' => Hashids::encode($row->kegiatan_id)]) }}
                                        </a>
                                    </td>
                                    {{-- Di bagian status --}}
                                    <td class="client_name">
                                        @if($row->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><button
                                                        class="dropdown-item btn-view"
                                                        href="#showModal"
                                                        data-bs-toggle="modal"
                                                        data-id="{{$row->kegiatan_id}}"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</button></li>
                                                <li>
                                                    <a
                                                        class="dropdown-item edit-item-btn btn-edit"
                                                        href="#showModal"
                                                        data-bs-toggle="modal"
                                                        data-id="{{$row->kegiatan_id}}">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item remove-item-btn btn-delete"
                                                        data-bs-toggle="modal"
                                                        href="#deleteOrder"
                                                        data-id="{{$row->kegiatan_id}}">
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
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

    @include('partials.modal.kegiatan.add')
</div>
@endsection

@section('sipproja-js')
<script>
    // Fungsi untuk copy text ke clipboard
    function copyToClipboard(text, element) {
        navigator.clipboard.writeText(text).then(function() {
            // Ubah ikon sementara menjadi centang
            const originalHTML = element.innerHTML;
            element.innerHTML = '<i class="ri-check-line"></i>';
            element.classList.remove('btn-outline-secondary');
            element.classList.add('btn-success');

            // Kembalikan ke keadaan semula setelah 2 detik
            setTimeout(() => {
                element.innerHTML = originalHTML;
                element.classList.remove('btn-success');
                element.classList.add('btn-outline-secondary');
            }, 2000);

            // Tampilkan notifikasi sukses
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Teks berhasil disalin!',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        }).catch(function(err) {
            console.error('Gagal menyalin teks: ', err);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Gagal menyalin teks',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        });
    }

    // Event listener untuk tombol copy token
    $(document).on('click', '#copy-token-btn', function() {
        const tokenValue = $('#token_display').val();
        if (tokenValue && tokenValue !== 'Akan digenerate otomatis (16 karakter)') {
            copyToClipboard(tokenValue, this);
        }
    });

    // Event listener untuk tombol copy URL
    $(document).on('click', '#copy-url-btn', function() {
        const urlValue = $('#url_display').val();
        if (urlValue && urlValue !== 'Akan digenerate otomatis') {
            copyToClipboard(urlValue, this);
        }
    });

    //start add btn
    $('.add-btn').on('click', function() {
        $('#modal-tittle').text('Tambah Kegiatan');
        $('#exampleModalLabel').text('Tambah Kegiatan');
        $('#add-btn').text('Tambah Kegiatan');
        $('#add-btn').show();

        // Reset form
        $('input[name="kegiatan_id"]').val('');
        $('input[name="kegiatan"]').val('');
        $('#entity').val('');
        $('#status').val('Active');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#token_display').val('');
        $('#url_display').val('');

        // Set tanggal default
        const today = new Date().toISOString().split('T')[0];
        $('#start_date').val(today);
        $('#end_date').val(today);

        // Generate preview
        $('#token_display').val('Akan digenerate otomatis (16 karakter)');
        $('#url_display').val('Akan digenerate otomatis');

        // Nonaktifkan tombol copy
        $('#copy-token-btn, #copy-url-btn').prop('disabled', true);
    });

    // start btn-view
    $('.btn-view').on('click', function() {
        let kegiatan_id = $(this).data('id');
        $('#modal-tittle').text('View Data');
        $('#exampleModalLabel').text('View Data');
        $('#add-btn').hide();

        $.get('/kegiatan/get/' + kegiatan_id, function(data) {
            $('input[name="kegiatan_id"]').val(data.kegiatan_id);
            $('input[name="kegiatan"]').val(data.kegiatan_name);
            $('#entity').val(data.entity).prop('disabled', true);
            $('#status').val(data.status).prop('disabled', true);
            $('#start_date').val(data.start_date).prop('disabled', true);
            $('#end_date').val(data.end_date).prop('disabled', true);
            $('#token_display').val(data.instrumen_token);
            $('#url_display').val("{{ config('app.url') }}/" + data.instrumen_url);

            // Aktifkan tombol copy
            $('#copy-token-btn, #copy-url-btn').prop('disabled', false);

            $('#showModal').modal('show');
        });
    });

    // edit
    $('.btn-edit').on('click', function() {
        let kegiatan_id = $(this).data('id');
        $('#modal-tittle').text('Ubah Kegiatan');
        $('#exampleModalLabel').text('Ubah Kegiatan');
        $('#add-btn').text('Ubah Kegiatan');
        $('#add-btn').show();

        $.get('/kegiatan/get/' + kegiatan_id, function(data) {
            $('input[name="kegiatan_id"]').val(data.kegiatan_id);
            $('input[name="kegiatan"]').val(data.kegiatan_name);
            $('#entity').val(data.entity).prop('disabled', false);
            $('#status').val(data.status).prop('disabled', false);
            $('#start_date').val(data.start_date).prop('disabled', false);
            $('#end_date').val(data.end_date).prop('disabled', false);
            $('#token_display').val(data.instrumen_token);
            $('#url_display').val("{{ config('app.url') }}/" + data.instrumen_url);

            // Aktifkan tombol copy
            $('#copy-token-btn, #copy-url-btn').prop('disabled', false);

            $('#showModal').modal('show');
        });
    });

    // Handle form submit untuk edit dan tambah
    $('form.tablelist-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const formData = new FormData(this);
        const kegiatan_id = $('input[name="kegiatan_id"]').val();
        const isEdit = kegiatan_id !== '';

        // Submit form via AJAX
        submitForm(form, formData, isEdit);
    });

    // Fungsi untuk submit form
    function submitForm(form, formData, isEdit) {
        const url = form.attr('action');
        const method = 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Jika response adalah JSON (AJAX)
                if (typeof response === 'object') {
                    if (response.success) {
                        // Tutup modal
                        $('#showModal').modal('hide');

                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            showCloseButton: true
                        }).then(() => {
                            // Refresh halaman
                            location.reload();
                        });
                    } else {
                        // Tampilkan error
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: true
                        });
                    }
                } else {
                    // Jika response bukan JSON (redirect), maka reload halaman
                    // Ini seharusnya tidak terjadi karena kita set AJAX, tapi untuk jaga-jaga
                    location.reload();
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Gagal menyimpan data',
                    text: errorMessage,
                    showConfirmButton: true
                });
            }
        });
    }

    // Saat modal ditutup, reset form
    $('#showModal').on('hidden.bs.modal', function() {
        $('#entity').prop('disabled', false);
        $('#status').prop('disabled', false);
        $('#start_date').prop('disabled', false);
        $('#end_date').prop('disabled', false);

        // Reset tombol copy
        $('#copy-token-btn, #copy-url-btn').prop('disabled', true);
    });

    // Delete functionality
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        $('#tusi_id').val(id);
    });

    $('#delete-record').on('click', function() {
        var id = $('#tusi_id').val();
        $.ajax({
            url: "/kegiatan/delete/" + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response.message || 'Data deleted successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true
                });
                $('#deleteOrder').modal('hide');
                location.reload();
            },
            error: function(xhr) {
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
</script>
@endsection