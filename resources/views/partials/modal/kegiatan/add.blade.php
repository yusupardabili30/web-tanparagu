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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="status" id="status" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                <small class="text-muted">Kontrol akses token dan URL lockscreen</small>
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
                                <small class="text-muted">Informasi tanggal kegiatan (tidak mempengaruhi token)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="token_display" class="form-label">Token (16 karakter, Auto Generate)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="token_display" id="token_display" placeholder="Token" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="copy-token-btn" title="Salin token">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Token unik 16 karakter untuk keperluan internal</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url_display" class="form-label">URL Lockscreen (Auto Generate)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="url_display" id="url_display" placeholder="URL" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="copy-url-btn" title="Salin URL">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                                <small class="text-muted">URL untuk akses lockscreen peserta</small>
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