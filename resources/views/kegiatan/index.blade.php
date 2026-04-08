@extends('layouts.main')
@section('mycontent')

<style>
    /* BUTTON SUBMIT MODAL: "Tambah Kegiatan" */
#showModal #add-btn,
#showModal .modal-footer .btn-primary{
  background: #1a4d8e !important;
  border-color: #1a4d8e !important;
  color:#fff !important;
  font-weight: 800;
  border-radius: 12px;
}

#showModal #add-btn:hover,
#showModal .modal-footer .btn-primary:hover{
  background: #163f74 !important;
  border-color: #163f74 !important;
  color:#fff !important;
}

#showModal #add-btn:focus,
#showModal #add-btn:active,
#showModal .modal-footer .btn-primary:focus,
#showModal .modal-footer .btn-primary:active{
  box-shadow: 0 0 0 .25rem rgba(26,77,142,.25) !important;
}

    :root{
        --ink:#1f2937;
        --muted:#6b7280;
        --line:#e5e7eb;
        --blue:#1a4d8e;
        --soft:#f8fafc;
        --success:#16a34a;
        --danger:#dc2626;
    }

    .page-title-box{ padding: 6px 0 14px 0; }

    /* ===== CARD ===== */
    .kegiatan-card{
        border: 0;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(2,6,23,.08);
        overflow: hidden;
        background: #fff;
    }

    /* matiin header default/gradient lama */
    .kegiatan-card .card-header,
    .kegiatan-card .kegiatan-header{
        background: none !important;
        border-bottom: 0 !important;
        padding: 0 !important;
    }

    /* =====================================================
       HEADER: BADUY STYLE (MODEL KODE HI-HEAD)
       - motif repeat (kecil)
       - overlay solid biru
       ===================================================== */
    .baduy-hero{
        position: relative;
        border-radius: 18px 18px 0 0;
        overflow: hidden;
        padding: 18px 18px;        /* ✅ tipis */
        min-height: 92px;          /* ✅ ga ketinggian */
        background: var(--blue);
        border-bottom: 1px solid rgba(255,255,255,.14);
    }

    /* motif baduy repeat (kecil) */
    .baduy-hero::before{
        content:"";
        position:absolute;
        inset:0;
        background-image: url("{{ asset('build/images/baduy.jpg') }}");
        background-repeat: repeat;
        background-size: 140px auto;
        background-position: center;
        opacity: .55;
        filter: grayscale(100%) contrast(1.15);
        z-index: 0;
    }

    /* overlay solid biru (biar teks kebaca) */
    .baduy-hero::after{
        content:"";
        position:absolute;
        inset:0;
        background: rgba(26,91,184,.50);
        z-index: 1;
        pointer-events:none;
    }

    .baduy-hero .hero-inner{
        position: relative;
        z-index: 2;
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .hero-left{
        display:flex;
        align-items:center;
        gap: 12px;
        min-width: 260px;
    }

    /* icon glass (bukan background putih) */
    .hero-icon{
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.18);
        color: #fff;
        flex: 0 0 auto;
        backdrop-filter: blur(6px);
    }
    .hero-icon i{ font-size: 20px; }

    .hero-title{
        margin:0;
        font-weight: 900;
        font-size: 20px;                 /* ✅ sedikit lebih kecil */
        letter-spacing: .2px;
        color:#fff;
        line-height: 1.15;
        text-shadow: 0 2px 12px rgba(0,0,0,.35);
    }

    .hero-sub{
        margin-top: 4px;
        font-weight: 500;
        font-size: 13px;
        color: rgba(255,255,255,.92);
        text-shadow: 0 2px 12px rgba(0,0,0,.35);
    }

    /* tombol di header: glass/transparent */
    .btn-hero{
        background: rgba(255,255,255,.16) !important;
        border: 1px solid rgba(255,255,255,.22) !important;
        color: #fff !important;
        font-weight: 900;
        border-radius: 14px;
        padding: 10px 14px;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition: .2s ease;
        backdrop-filter: blur(6px);
        box-shadow: 0 10px 18px rgba(2,6,23,.12);
    }
    .btn-hero:hover{
        background: rgba(255,255,255,.22) !important;
        transform: translateY(-1px);
    }

    .btn-hero-icon{
        width: 44px;
        height: 44px;
        padding: 0 !important;
        justify-content:center;
    }

    /* =========================
       TABLE + SCROLL SAMPING
    ========================= */
    .table-card{
        border: 1px solid rgba(229,231,235,.85);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        margin-top: 14px;
    }

    .table-scroll{
        position: relative;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }

    #ticketTable{ min-width: 1350px; }

    #ticketTable thead th{
        background: #f8fafc;
        border-bottom: 1px solid rgba(229,231,235,.9);
        color: var(--muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 14px 12px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    #ticketTable tbody td{
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(229,231,235,.75);
        color: var(--ink);
        font-weight: 500;
        font-size: 13.5px;
        background: #fff;
    }

    #ticketTable tbody tr:hover td{
        background: rgba(26,91,184,.04);
    }

    .cell-id a{
        font-weight: 900 !important;
        color: var(--blue) !important;
        text-decoration: none;
    }

    .cell-small{
        color: var(--muted) !important;
        font-weight: 500 !important;
    }

    .url-wrap{
        display:flex;
        align-items:center;
        gap:8px;
        max-width: 420px;
    }
    .url-wrap a{
        display:block;
        max-width: 340px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--blue);
        text-decoration: none;
        font-weight: 800;
    }

    .btn-copy-mini{
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border: 1px solid rgba(229,231,235,.9);
        background: #fff;
        transition: .2s ease;
    }
    .btn-copy-mini:hover{
        background: rgba(26,91,184,.06);
        border-color: rgba(26,91,184,.25);
        transform: translateY(-1px);
    }

    .badge-soft{
        padding: 7px 10px;
        border-radius: 999px;
        font-weight: 900;
        letter-spacing:.2px;
        font-size: 12px;
        display:inline-flex;
        align-items:center;
        gap:7px;
    }
    .badge-soft i{ font-size: 14px; }
    .badge-active{
        background: rgba(22,163,74,.12);
        color: var(--success);
        border: 1px solid rgba(22,163,74,.18);
    }
    .badge-inactive{
        background: rgba(220,38,38,.10);
        color: var(--danger);
        border: 1px solid rgba(220,38,38,.16);
    }

    .btn-action-more{
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border: 1px solid rgba(229,231,235,.9);
        background: #fff;
        transition: .2s ease;
    }
    .btn-action-more:hover{
        background: rgba(2,6,23,.04);
        transform: translateY(-1px);
    }

    .dropdown-menu{
        border-radius: 14px;
        border: 1px solid rgba(229,231,235,.9);
        box-shadow: 0 18px 30px rgba(2,6,23,.12);
        overflow: hidden;
        padding: 8px;
    }
    .dropdown-item{
        border-radius: 12px;
        padding: 10px 12px;
        font-weight: 800;
        display:flex;
        align-items:center;
        gap:10px;
    }
    .dropdown-item i{ font-size: 16px; }

    /* shadow indikator scroll */
    .table-scroll::before,
    .table-scroll::after{
        content:"";
        position: sticky;
        top: 0;
        width: 26px;
        height: 100%;
        z-index: 3;
        pointer-events: none;
        opacity: 0;
    }
    .table-scroll::before{
        left: 0;
        float: left;
        background: linear-gradient(to right, rgba(2,6,23,.14), transparent);
    }
    .table-scroll::after{
        right: 0;
        float: right;
        background: linear-gradient(to left, rgba(2,6,23,.14), transparent);
    }
    .table-scroll.has-left::before{ opacity: 1; }
    .table-scroll.has-right::after{ opacity: 1; }

    .table-scroll::-webkit-scrollbar{ height: 10px; }
    .table-scroll::-webkit-scrollbar-track{ background: #f1f5f9; border-radius: 999px; }
    .table-scroll::-webkit-scrollbar-thumb{ background: rgba(2,6,23,.18); border-radius: 999px; }
    .table-scroll::-webkit-scrollbar-thumb:hover{ background: rgba(2,6,23,.28); }

    @media (max-width: 1200px){
        .url-wrap{ max-width: 320px; }
        .url-wrap a{ max-width: 240px; }
    }
    @media (max-width: 992px){
        .url-wrap{ max-width: 260px; }
        .url-wrap a{ max-width: 180px; }
    }
</style>

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
            <div class="card kegiatan-card" id="ticketsList">

                {{-- HEADER BADUY (REPEAT + OVERLAY SOLID) --}}
                <div class="baduy-hero">
                    <div class="hero-inner">
                        <div class="hero-left">
                            <div class="hero-icon">
                                <i class="ri-calendar-event-line"></i>
                            </div>
                            <div>
                                <h5 class="hero-title">{{ $tittle }}</h5>
                                <div class="hero-sub">
                                    Menampilkan
                                    <b>{{ method_exists($data,'total') ? $data->total() : $data->count() }}</b>
                                    data
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-hero add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                                <i class="ri-add-line"></i> Tambah {{ $tittle }}
                            </button>

                            <button class="btn btn-hero btn-hero-icon" id="remove-actions" onclick="deleteMultiple()">
                                <i class="ri-delete-bin-2-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="table-card">
                        <div class="table-scroll" id="tableScroll">
                            <table class="table align-middle table-nowrap mb-0" id="ticketTable">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:46px;">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                            </div>
                                        </th>
                                        <th class="sort" data-sort="id">ID</th>
                                        <th class="sort" data-sort="kegiatan">Kegiatan</th>
                                        <th class="sort" data-sort="kegiatan">Entitas</th>
                                        <th class="sort" data-sort="kegiatan">Tahap Soal</th>
                                        <th class="sort" data-sort="kegiatan">Token</th>
                                        <th class="sort" data-sort="kegiatan">URL</th>
                                        <th class="sort" data-sort="kegiatan">URL Register</th>
                                        <th class="sort" data-sort="kegiatan">Status</th>
                                        <th class="sort" data-sort="action" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="list form-check-all" id="ticket-list-data">
                                    @foreach ($data as $row)
                                    @php
                                        $encoded_kegiatan_id = \Vinkla\Hashids\Facades\Hashids::encode($row->kegiatan_id);
                                        $urlLock = route('lockscreen', ['encode_kegiatan_id' => $encoded_kegiatan_id]);
                                        $urlReg  = route('register.index', ['encode_kegiatan_id' => $encoded_kegiatan_id]);
                                    @endphp
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="checkbox" name="checkAll" value="option1">
                                            </div>
                                        </th>

                                        <td class="cell-id id">
                                            <a href="javascript:void(0);" onclick="ViewTickets(this)" data-id="001" class="fw-medium link-primary">
                                                #{{ $row->kegiatan_id }}
                                            </a>
                                        </td>

                                        <td class="client_name">{{ $row->kegiatan_name }}</td>
                                        <td class="client_name cell-small">{{ $row->entity }}</td>
                                        <td class="client_name cell-small">{{ $row->tahap }}</td>

                                        <td class="client_name">
                                            <span class="cell-small">{{ $row->instrumen_token }}</span>
                                        </td>

                                        <td class="client_name">
                                            <div class="url-wrap">
                                                <a href="{{ $urlLock }}" target="_blank" title="{{ $urlLock }}">{{ $urlLock }}</a>
                                                <button type="button" class="btn-copy-mini"
                                                    onclick="copyToClipboard('{{ $urlLock }}', this)" title="Copy URL">
                                                    <i class="ri-file-copy-line"></i>
                                                </button>
                                            </div>
                                        </td>

                                        <td class="client_name">
                                            <div class="url-wrap">
                                                <a href="{{ $urlReg }}" target="_blank" title="{{ $urlReg }}">{{ $urlReg }}</a>
                                                <button type="button" class="btn-copy-mini"
                                                    onclick="copyToClipboard('{{ $urlReg }}', this)" title="Copy URL Register">
                                                    <i class="ri-file-copy-line"></i>
                                                </button>
                                            </div>
                                        </td>

                                        <td class="client_name">
                                            @if($row->status == 'Active')
                                                <span class="badge-soft badge-active">
                                                    <i class="ri-checkbox-circle-line"></i> Active
                                                </span>
                                            @else
                                                <span class="badge-soft badge-inactive">
                                                    <i class="ri-close-circle-line"></i> Inactive
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn-action-more" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item btn-view"
                                                            href="#showModal" data-bs-toggle="modal"
                                                            data-id="{{$row->kegiatan_id}}">
                                                            <i class="ri-eye-fill text-muted"></i> View
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item edit-item-btn btn-edit"
                                                            href="#showModal" data-bs-toggle="modal"
                                                            data-id="{{$row->kegiatan_id}}">
                                                            <i class="ri-pencil-fill text-muted"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item remove-item-btn btn-delete"
                                                            data-bs-toggle="modal" href="#deleteOrder"
                                                            data-id="{{$row->kegiatan_id}}">
                                                            <i class="ri-delete-bin-fill text-muted"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}

                    <!-- Modal Delete -->
                    <div class="modal fade flip" id="deleteOrder" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                               colors="primary:#405189,secondary:#f06548"
                                               style="width:90px;height:90px"></lord-icon>

                                    <div class="mt-4 text-center">
                                        <h4 style="font-weight:900; color:var(--ink);">You are about to delete?</h4>
                                        <p class="text-muted fs-14 mb-4">Deleting will remove all of your information from our database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <input type="text" id="tusi_id" style="display: none">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal">
                                                <i class="ri-close-line me-1 align-middle"></i> Close
                                            </button>
                                            <button class="btn btn-danger" id="delete-record" style="border-radius:12px; font-weight:800;">
                                                Yes, Delete It
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end modal -->

                </div>
            </div>
        </div>
    </div>

    @include('partials.modal.kegiatan.add')
</div>
@endsection

@section('sipproja-js')
<script>
    @if(session('success'))
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    @endif

    @if(session('error'))
    Swal.fire({
        position: 'center',
        icon: 'error',
        title: '{{ session("error") }}',
        showConfirmButton: true
    });
    @endif

    function copyToClipboard(text, element) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHTML = element.innerHTML;
            element.innerHTML = '<i class="ri-check-line"></i>';
            element.classList.add('btn-success');
            setTimeout(() => {
                element.innerHTML = originalHTML;
                element.classList.remove('btn-success');
            }, 1200);

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

    // Shadow indikator scroll kiri/kanan
    (function(){
        const el = document.getElementById('tableScroll');
        if(!el) return;

        function updateShadow(){
            const maxScroll = el.scrollWidth - el.clientWidth;
            const left = el.scrollLeft;
            el.classList.toggle('has-left', left > 6);
            el.classList.toggle('has-right', (maxScroll - left) > 6);
        }

        el.addEventListener('scroll', updateShadow);
        window.addEventListener('resize', updateShadow);
        updateShadow();
    })();

    // start add
    $('.add-btn').on('click', function() {
        $('#modal-tittle').text('Tambah Kegiatan');
        $('#exampleModalLabel').text('Tambah Kegiatan');
        $('#add-btn').text('Tambah Kegiatan');
        $('#add-btn').show();

        $('input[name="kegiatan_id"]').val('');
        $('input[name="kegiatan"]').val('');
        $('#entity').val('');
        $('#status_id').val('Active');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#token_display').val('');
        $('#url_display').val('');
        $('#url_register').val('');

        const today = new Date().toISOString().split('T')[0];
        $('#start_date').val(today);
        $('#end_date').val(today);

        $('#token_display').val('Akan digenerate otomatis');
        $('#url_display').val('Akan digenerate otomatis');
        $('#url_register').val('Akan digenerate otomatis');

        $('#copy-token-btn, #copy-url-btn, #copy-url-register-btn').prop('disabled', true);
        $('#entity, #status_id, #start_date, #end_date, input[name="kegiatan"]').prop('disabled', false);
    });

   // view
$('.btn-view').on('click', function() {
    let kegiatan_id = $(this).data('id');
    $('#modal-tittle').text('View Data');
    $('#exampleModalLabel').text('View Data');
    $('#add-btn').hide();

    $.get('/kegiatan/get/' + kegiatan_id, function(data) {
        $('input[name="kegiatan_id"]').val(data.kegiatan_id);
        $('input[name="kegiatan"]').val(data.kegiatan_name);
        $('#entity').val(data.entity).prop('disabled', true);
        $('#status_id').val(data.status).prop('disabled', true);
        $('#start_date').val(data.start_date).prop('disabled', true);
        $('#end_date').val(data.end_date).prop('disabled', true);

        // TAMBAHKAN INI - Untuk dropdown tahap di VIEW
        $('#ddl_tahap').val(data.tahap).prop('disabled', true);

        $('#token_display').val(data.instrumen_token || 'Token tidak tersedia');

        const encoded_kegiatan_id = btoa(data.kegiatan_id.toString());
        const baseUrl = window.location.origin;

        $('#url_display').val(baseUrl + '/lockscreen/' + encoded_kegiatan_id);
        $('#url_register').val(baseUrl + '/register/' + encoded_kegiatan_id);

        $('#copy-token-btn').prop('disabled', !(data.instrumen_token && data.instrumen_token !== ''));
        $('#copy-url-btn').prop('disabled', !($('#url_display').val()));
        $('#copy-url-register-btn').prop('disabled', !($('#url_register').val()));

        $('#showModal').modal('show');
    }).fail(function() {
        Swal.fire({ position:'center', icon:'error', title:'Gagal memuat data', showConfirmButton:true });
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
        $('#status_id').val(data.status).prop('disabled', false);
        $('#start_date').val(data.start_date).prop('disabled', false);
        $('#end_date').val(data.end_date).prop('disabled', false);

        // TAMBAHKAN INI - Untuk dropdown tahap
        $('#ddl_tahap').val(data.tahap).prop('disabled', false);

        $('#token_display').val(data.instrumen_token || 'Token tidak tersedia');

        const encoded_kegiatan_id = btoa(data.kegiatan_id.toString());
        const baseUrl = window.location.origin;

        $('#url_display').val(baseUrl + '/lockscreen/' + encoded_kegiatan_id);
        $('#url_register').val(baseUrl + '/register/' + encoded_kegiatan_id);

        $('#copy-token-btn').prop('disabled', !(data.instrumen_token && data.instrumen_token !== ''));
        $('#copy-url-btn').prop('disabled', !($('#url_display').val()));
        $('#copy-url-register-btn').prop('disabled', !($('#url_register').val()));

        $('#showModal').modal('show');
    }).fail(function() {
        Swal.fire({ position:'center', icon:'error', title:'Gagal memuat data', showConfirmButton:true });
    });
});

    // reset modal
    $('#showModal').on('hidden.bs.modal', function() {
        $('#entity,#status_id,#start_date,#end_date').prop('disabled', false);
        $('#copy-token-btn,#copy-url-btn,#copy-url-register-btn').prop('disabled', true);
    });

    // delete
    $('.btn-delete').on('click', function() {
        $('#tusi_id').val($(this).data('id'));
    });

    $('#delete-record').on('click', function() {
        var id = $('#tusi_id').val();
        $.ajax({
            url: "/kegiatan/delete/" + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                Swal.fire({ position:'center', icon:'success', title: response.message || 'Data berhasil dihapus!', showConfirmButton:false, timer:2000, showCloseButton:true });
                $('#deleteOrder').modal('hide');
                setTimeout(function(){ location.reload(); }, 2000);
            },
            error: function(xhr) {
                Swal.fire({ position:'center', icon:'error', title:'Gagal menghapus data', text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus.', showConfirmButton:true });
                console.error(xhr.responseText);
            }
        });
    });
</script>
@endsection
