@extends('layouts.main')
@section('mycontent')

<style>
    :root{
        --ink:#1f2937;
        --muted:#6b7280;
        --line:#e5e7eb;

        /* PRIMARY BLUE */
        --blue:#1A4BB8;

        --soft:#f8fafc;
        --success:#16a34a;
        --danger:#dc2626;
        --warning:#f59e0b;

        /* legacy (kalau kepake) */
        --purple:#1A4BB8;
    }

    .page-title-box{ padding: 6px 0 14px 0; }

    /* ===== CARD ===== */
    .users-card{
        border: 0;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(2,6,23,.08);
        overflow: hidden;
        background: #fff;
    }

    /* HEADER: BADUY STYLE */
    .baduy-hero{
        position: relative;
        border-radius: 18px 18px 0 0;
        overflow: hidden;
        padding: 18px 18px;
        min-height: 92px;
        background: var(--blue);
        border-bottom: 1px solid rgba(255,255,255,.14);
    }

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

    .baduy-hero::after{
        content:"";
        position:absolute;
        inset:0;
        background: rgba(26,75,184,.50);
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
        font-size: 20px;
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

    #usersTable{ min-width: 1350px; }

    #usersTable thead th{
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

    #usersTable tbody td{
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(229,231,235,.75);
        color: var(--ink);
        font-weight: 500;
        font-size: 13.5px;
        background: #fff;
    }

    #usersTable tbody tr:hover td{
        background: rgba(26,75,184,.04);
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

    /* USER CELL (1 BARIS, NO @) */
    .user-cell{
        font-weight: 900;
        color: var(--ink);
        white-space: nowrap;
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

    .badge-role-admin{
        background: rgba(26,75,184,.12);
        color: var(--blue) !important;
        border: 1px solid rgba(26,75,184,.18);
    }
    .badge-role-user{
        background: rgba(59,130,246,.12);
        color: #3b82f6;
        border: 1px solid rgba(59,130,246,.18);
    }
    .badge-role-superadmin{
        background: rgba(220,38,38,.12);
        color: var(--danger);
        border: 1px solid rgba(220,38,38,.18);
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

    /* modal form styles */
    .form-label{
        font-weight: 800;
        color: var(--ink);
        font-size: 14px;
        margin-bottom: 8px;
    }
    .form-control, .form-select{
        border-radius: 12px;
        padding: 12px 14px;
        border: 1px solid rgba(229,231,235,.9);
        font-weight: 500;
    }
    .form-control:focus, .form-select:focus{
        border-color: var(--blue) !important;
        box-shadow: 0 0 0 3px rgba(26,75,184,.15) !important;
    }

    /* modal header SOLID */
    .modal-head-blue{
        background: var(--blue) !important;
        padding: 24px 30px !important;
    }

    /* submit btn solid */
    #submitBtn{
        background: var(--blue) !important;
        border-color: var(--blue) !important;
        border-radius: 12px !important;
        font-weight: 800 !important;
    }

    /* =========================
       PAGINATION RAPII
    ========================= */
    .pagination-wrap{
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap: 14px;
        margin-top: auto;
        padding-top: 38px;
        padding-bottom: 10px;
    }

    .pagination-info{
        color: var(--muted);
        font-weight: 700;
        font-size: 13px;
        white-space: nowrap;
    }

    .pagination-links{
        display:flex;
        justify-content: flex-end;
    }

    /* responsive: kalau layar kecil, turun kebawah */
    @media (max-width: 576px){
        .pagination-wrap{
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .pagination-links{
            width: 100%;
            justify-content: center;
        }
    }
        /* ===== Biar pagination turun ke bawah (nempel bawah card) ===== */
    .users-card .card-body{
        display: flex;
        flex-direction: column;
    }

    /* table biar ngambil ruang, pagination terdorong ke bawah */
    .users-card .table-card{
        flex: 1 1 auto;
    }

    /* pagination didorong ke bawah */
    .pagination-wrap{
        margin-top: auto;      /* ini kuncinya */
        padding-top: 38px;     /* jarak dari table */
        padding-bottom: 10px;  /* nafas bawah */
    }
    /* =========================
    MODAL HEADER: BADUY
    ========================= */
    .modal-head-baduy{
        position: relative;
        background: var(--blue) !important;
        padding: 22px 26px !important;
        overflow: hidden;
    }

    .modal-head-baduy::before{
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

    .modal-head-baduy::after{
        content:"";
        position:absolute;
        inset:0;
        background: rgba(26,75,184,.55);
        z-index: 1;
        pointer-events:none;
    }

    /* isi header tetep di atas motif */
    .modal-head-baduy > *{
        position: relative;
        z-index: 2;
    }

    /* tombol close putih + cantik */
    .modal-head-baduy .btn-close{
        filter: invert(1);
        opacity: .9;
    }
    .modal-head-baduy .btn-close:hover{
        opacity: 1;
    }

    /* judul & subjudul */
    .modal-head-baduy .modal-title{
        font-weight: 900;
        letter-spacing: .2px;
    }
    .modal-head-baduy p{
        margin: 6px 0 0 0;
    }
    /* =========================
    TOOLBAR SEARCH
    ========================= */
    .users-toolbar{
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 14px;
        margin-bottom: 10px;
    }

    .searchbox{
        position: relative;
        min-width: 280px;
        flex: 1 1 520px;
    }

    .searchbox i.ri-search-line{
        position:absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 18px;
        pointer-events:none;
    }

    .searchbox #userSearch{
        padding-left: 44px;
        padding-right: 42px;
        border-radius: 14px;
        height: 46px;
    }

    .searchbox .btn-clear{
        position:absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        padding: 4px;
        color: #9ca3af;
        display:none;
    }
    .searchbox .btn-clear:hover{ color: var(--ink); }

    .searchmeta{
        color: var(--muted);
        font-weight: 700;
        font-size: 13px;
        white-space: nowrap;
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
            <div class="card users-card" id="usersList">

                {{-- HEADER BADUY --}}
                <div class="baduy-hero">
                    <div class="hero-inner">
                        <div class="hero-left">
                            <div class="hero-icon">
                                <i class="ri-user-line"></i>
                            </div>
                            <div>
                                <h5 class="hero-title">{{ $tittle }}</h5>
                                <div class="hero-sub">
                                    Menampilkan
                                    <b>{{ method_exists($data,'total') ? $data->total() : $data->count() }}</b>
                                    data user
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-hero add-btn" data-bs-toggle="modal" data-bs-target="#userModal">
                                <i class="ri-add-line"></i> Tambah User
                            </button>

                            <button class="btn btn-hero btn-hero-icon" id="remove-actions" onclick="deleteMultiple()">
                                <i class="ri-delete-bin-2-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="users-toolbar">
                        <div class="searchbox">
                            <i class="ri-search-line"></i>
                            <input type="text" id="userSearch" class="form-control"
                                placeholder="Cari: ID / Username / Nama / Role / Tim Kerja / NIP / NIK / Email / Sekolah...">
                            <button type="button" class="btn-clear" id="clearSearch" title="Clear" aria-label="Clear">
                                <i class="ri-close-circle-fill"></i>
                            </button>
                        </div>
                        <div class="searchmeta" id="searchMeta"></div>
                    </div>

                    <div class="table-card">
                        <div class="table-scroll" id="tableScroll">
                            <table class="table align-middle table-nowrap mb-0" id="usersTable">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:46px;">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                            </div>
                                        </th>
                                        <th class="sort" data-sort="id">ID</th>
                                        <th class="sort" data-sort="user">User</th>
                                        <th class="sort" data-sort="nama">Nama Lengkap</th>
                                        <th class="sort" data-sort="role">Role</th>
                                        <th class="sort" data-sort="tim_kerja">Tim Kerja</th>
                                        <th class="sort" data-sort="nip">NIP/NIK</th>
                                        <th class="sort" data-sort="email">Email</th>
                                        <th class="sort" data-sort="sekolah">Sekolah</th>
                                        <th class="sort" data-sort="action" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="list form-check-all" id="user-list-data">
                                    @foreach ($data as $row)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="checkbox" name="checkAll" value="{{ $row->user_id }}">
                                            </div>
                                        </th>

                                        <td class="cell-id id">
                                            <a href="javascript:void(0);" onclick="viewUser(this)" data-id="{{ $row->user_id }}" class="fw-medium link-primary">
                                                #{{ $row->user_id }}
                                            </a>
                                        </td>

                                        {{-- USER (1 baris, tanpa @) --}}
                                        <td class="user-cell">
                                            {{ $row->user_name }}
                                        </td>

                                        <td class="fw-600">{{ $row->nama }}</td>

                                        <td>
                                            @if($row->role)
                                                @php
                                                    $roleColors = [
                                                        'admin' => 'badge-role-admin',
                                                        'superadmin' => 'badge-role-superadmin',
                                                        'user' => 'badge-role-user'
                                                    ];
                                                    $roleClass = $roleColors[strtolower($row->role->role)] ?? 'badge-role-user';
                                                @endphp
                                                <span class="badge-soft {{ $roleClass }}">
                                                    <i class="ri-shield-user-line"></i> {{ $row->role->role }}
                                                </span>
                                            @else
                                                <span class="badge-soft badge-role-user">
                                                    <i class="ri-user-line"></i> User
                                                </span>
                                            @endif
                                        </td>

                                        <td class="cell-small">
                                            {{ $row->timKerja->tim_kerja ?? '-' }}
                                        </td>

                                        <td class="cell-small">
                                            <div>NIP: {{ $row->nip ?? '-' }}</div>
                                            <div>NIK: {{ $row->nik ?? '-' }}</div>
                                        </td>

                                        <td class="cell-small">{{ $row->email ?? '-' }}</td>

                                        <td class="cell-small">
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $row->nama_satuan_pendidikan }}">
                                                {{ $row->nama_satuan_pendidikan ?? '-' }}
                                            </div>
                                            @if($row->npsn)
                                                <div class="small text-muted">NPSN: {{ $row->npsn }}</div>
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
                                                            href="#userModal" data-bs-toggle="modal"
                                                            data-id="{{ $row->user_id }}">
                                                            <i class="ri-eye-fill text-muted"></i> View
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item edit-item-btn btn-edit"
                                                            href="#userModal" data-bs-toggle="modal"
                                                            data-id="{{ $row->user_id }}">
                                                            <i class="ri-pencil-fill text-muted"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item reset-password-btn"
                                                            href="javascript:void(0);"
                                                            data-id="{{ $row->user_id }}">
                                                            <i class="ri-key-2-fill text-muted"></i> Reset Password
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item remove-item-btn btn-delete"
                                                            data-bs-toggle="modal" href="#deleteModal"
                                                            data-id="{{ $row->user_id }}">
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

                    {{-- PAGINATION --}}
                    <div class="pagination-wrap">
                        <div class="pagination-info">
                            @if(method_exists($data,'firstItem'))
                                Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of {{ $data->total() ?? 0 }} results
                            @else
                                Showing 1 to {{ $data->count() }} of {{ $data->count() }} results
                            @endif
                        </div>

                        <div class="pagination-links">
                            {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>

                    <!-- Modal Delete -->
                    <div class="modal fade flip" id="deleteModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                               colors="primary:#405189,secondary:#f06548"
                                               style="width:90px;height:90px"></lord-icon>

                                    <div class="mt-4 text-center">
                                        <h4 style="font-weight:900; color:var(--ink);">Hapus User?</h4>
                                        <p class="text-muted fs-14 mb-4">User yang dihapus tidak dapat dikembalikan.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <input type="text" id="delete_user_id" style="display: none">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal">
                                                <i class="ri-close-line me-1 align-middle"></i> Close
                                            </button>
                                            <button class="btn btn-danger" id="delete-record" style="border-radius:12px; font-weight:800;">
                                                Ya, Hapus
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

    <!-- Modal Add/Edit User -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header border-0 pb-0 modal-head-baduy">
                    <div>
                        <h5 class="modal-title text-white" id="modalTitle">Tambah User</h5>
                        <p class="text-white-50 mb-0" id="modalSubtitle">Tambahkan user baru ke sistem</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="userForm" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">

                    <div class="modal-body p-30">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
                            </div>

                            <div class="col-md-6">
                                <label for="user_name" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="user_name" name="user_name" required placeholder="Masukkan username">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com">
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label" id="passwordLabel">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter">
                                <small class="text-muted" id="passwordHelp">Untuk edit user, kosongkan jika tidak ingin mengubah password</small>
                            </div>

                            <div class="col-md-6">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" placeholder="16 digit NIP" maxlength="16">
                            </div>

                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" placeholder="16 digit NIK" maxlength="16">
                            </div>

                            <div class="col-md-6">
                                <label for="role_id" class="form-label">Role</label>
                                <select class="form-select" id="role_id" name="role_id">
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->role_id }}">{{ $role->role }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="tim_kerja_id" class="form-label">Tim Kerja</label>
                                <select class="form-select" id="tim_kerja_id" name="tim_kerja_id">
                                    <option value="">Pilih Tim Kerja</option>
                                    @foreach($timKerja as $tim)
                                        <option value="{{ $tim->tim_kerja_id }}">{{ $tim->tim_kerja }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">Informasi Sekolah (Opsional)</h6>
                            </div>

                            <div class="col-md-6">
                                <label for="npsn" class="form-label">NPSN</label>
                                <input type="text" class="form-control" id="npsn" name="npsn" placeholder="Kode NPSN sekolah">
                            </div>

                            <div class="col-md-6">
                                <label for="nama_satuan_pendidikan" class="form-label">Nama Sekolah</label>
                                <input type="text" class="form-control" id="nama_satuan_pendidikan" name="nama_satuan_pendidikan" placeholder="Nama lengkap sekolah">
                            </div>

                            <div class="col-12">
                                <label for="alamat_satuan_pendidikan" class="form-label">Alamat Sekolah</label>
                                <input type="text" class="form-control" id="alamat_satuan_pendidikan" name="alamat_satuan_pendidikan" placeholder="Alamat lengkap sekolah">
                            </div>

                            <div class="col-md-6">
                                <label for="kab_kota" class="form-label">Kabupaten/Kota</label>
                                <input type="text" class="form-control" id="kab_kota" name="kab_kota" placeholder="Kabupaten/Kota">
                            </div>

                            <div class="col-md-6">
                                <label for="bos" class="form-label">Status BOS</label>
                                <input type="text" class="form-control" id="bos" name="bos" placeholder="Status BOS">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 12px; font-weight: 800;">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

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
        $('#modalTitle').text('Tambah User');
        $('#modalSubtitle').text('Tambahkan user baru ke sistem');
        $('#passwordLabel').html('Password <span class="text-danger">*</span>');
        $('#passwordHelp').text('Minimal 6 karakter');
        $('#password').prop('required', true);

        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#password').val('').prop('disabled', false);

        $('#userForm input, #userForm select').prop('disabled', false);
        $('#submitBtn').show();
    });

    // view
    $('.btn-view').on('click', function() {
        let userId = $(this).data('id');
        $('#modalTitle').text('View User');
        $('#modalSubtitle').text('Lihat detail user');

        $('#userForm input, #userForm select').prop('disabled', true);
        $('#submitBtn').hide();

        loadUserData(userId);
    });

    // edit
    $('.btn-edit').on('click', function() {
        let userId = $(this).data('id');
        $('#modalTitle').text('Edit User');
        $('#modalSubtitle').text('Edit data user');
        $('#passwordLabel').html('Password (kosongkan jika tidak diubah)');
        $('#passwordHelp').text('Kosongkan jika tidak ingin mengubah password');
        $('#password').prop('required', false);

        $('#userForm input, #userForm select').prop('disabled', false);
        $('#password').prop('disabled', false);
        $('#submitBtn').show();

        loadUserData(userId);
    });

    function loadUserData(userId) {
        $.get('/users/get/' + userId, function(data) {
            $('#user_id').val(data.user_id);
            $('#nama').val(data.nama);
            $('#user_name').val(data.user_name);
            $('#email').val(data.email);
            $('#nip').val(data.nip);
            $('#nik').val(data.nik);
            $('#role_id').val(data.role_id);
            $('#tim_kerja_id').val(data.tim_kerja_id);
            $('#npsn').val(data.npsn);
            $('#nama_satuan_pendidikan').val(data.nama_satuan_pendidikan);
            $('#alamat_satuan_pendidikan').val(data.alamat_satuan_pendidikan);
            $('#kab_kota').val(data.kab_kota);
            $('#bos').val(data.bos);

            $('#password').val('');
            $('#userModal').modal('show');
        }).fail(function() {
            Swal.fire({ position:'center', icon:'error', title:'Gagal memuat data', showConfirmButton:true });
        });
    }

    // reset password
    $('.reset-password-btn').on('click', function() {
        let userId = $(this).data('id');

        Swal.fire({
            title: 'Reset Password?',
            text: 'Password akan direset ke "password123"',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1A4BB8',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/reset-password/' + userId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT'
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Gagal mereset password',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                            showConfirmButton: true
                        });
                    }
                });
            }
        });
    });

    // delete
    $('.btn-delete').on('click', function() {
        $('#delete_user_id').val($(this).data('id'));
    });

    $('#delete-record').on('click', function() {
        var id = $('#delete_user_id').val();
        $.ajax({
            url: "/users/delete/" + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire({
                    position:'center',
                    icon:'success',
                    title: response.message || 'Data user berhasil dihapus!',
                    showConfirmButton:false,
                    timer:2000
                });
                $('#deleteModal').modal('hide');
                setTimeout(function(){
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                Swal.fire({
                    position:'center',
                    icon:'error',
                    title:'Gagal menghapus data',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus.',
                    showConfirmButton:true
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Reset modal saat ditutup
    $('#userModal').on('hidden.bs.modal', function() {
        $('#userForm')[0].reset();
        $('#userForm input, #userForm select').prop('disabled', false);
        $('#submitBtn').show();
        $('#password').prop('required', true);
        $('#passwordLabel').html('Password <span class="text-danger">*</span>');
        $('#passwordHelp').text('Untuk edit user, kosongkan jika tidak ingin mengubah password');
    });
    // =========================
// SEARCH FILTER (LIVE)
// =========================
(function(){
    const input = document.getElementById('userSearch');
    const btnClear = document.getElementById('clearSearch');
    const meta = document.getElementById('searchMeta');
    const tbody = document.getElementById('user-list-data');

    if(!input || !tbody) return;

    function normalize(s){
        return (s || '').toString().toLowerCase().trim();
    }

    function getRows(){
        return Array.from(tbody.querySelectorAll('tr'));
    }

    function updateMeta(visible, total, q){
        if(!meta) return;
        if(!q){
            meta.textContent = `Menampilkan ${total} data`;
        }else{
            meta.textContent = `Hasil: ${visible} dari ${total}`;
        }
    }

    function filter(){
        const q = normalize(input.value);
        const rows = getRows();
        const total = rows.length;
        let visible = 0;

        rows.forEach(tr => {
            const text = normalize(tr.innerText);
            const match = !q || text.includes(q);
            tr.style.display = match ? '' : 'none';
            if(match) visible++;
        });

        // tombol clear
        if(btnClear){
            btnClear.style.display = q ? 'inline-flex' : 'none';
        }
        updateMeta(visible, total, q);
    }

    input.addEventListener('input', filter);

    if(btnClear){
        btnClear.addEventListener('click', function(){
            input.value = '';
            input.focus();
            filter();
        });
    }

    // init
    filter();
})();

</script>
@endsection
