<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="modal-tittle">Tambah {{ $tittle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" action="{{ route('kegiatan.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="display: none">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="kegiatan_id" placeholder="Kegiatan ID">
                                    <label for="kegiatan_id">Kegiatan ID</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="kegiatan" placeholder="Nama Kegiatan" required>
                                    <label for="kegiatan">Nama Kegiatan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="entity" id="entity" required>
                                        <option value="">Pilih Entitas</option>
                                        <option value="Guru">Guru</option>
                                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                                        <option value="Pengawas">Pengawas</option>
                                    </select>
                                    <label for="entity">Entitas</label>
                                </div>
                            </div>
                        </div>
                        <!-- Tambahkan dropdown tahap di sini -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="tahap" id="tahap" required>
                                        <option value="">Pilih Tahap</option>
                                        <?php

                                        use Illuminate\Support\Facades\DB;
                                        // Ambil data tahap unik dari tabel soal
                                        $tahapList = DB::table('soal')
                                            ->select('tahap')
                                            ->distinct()
                                            ->orderBy('tahap')
                                            ->get();
                                        ?>
                                        @foreach($tahapList as $tahapItem)
                                        <option value="{{ $tahapItem->tahap }}">Tahap {{ $tahapItem->tahap }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tahap">Tahap Soal</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="status_id" id="status_id" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                                    <label for="start_date">Tanggal Mulai</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                                    <label for="end_date">Tanggal Selesai</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="token_display" class="form-label">Token</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="token_display" id="token_display" placeholder="Akan digenerate otomatis" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="copy-token-btn" title="Salin token" disabled>
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url_display" class="form-label">URL Lockscreen</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="url_display" id="url_display" placeholder="Akan digenerate otomatis" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="copy-url-btn" title="Salin URL" disabled>
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Tambah {{ $tittle }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>