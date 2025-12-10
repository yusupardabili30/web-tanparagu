<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $tittle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" action="{{ route('dokumenkegiatan.store') }}" method="POST" autocomplete="off">
                @csrf
                        <div class="modal-body">         
                            <div class="row">
                                <div class="col-md-12" style="display: none">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="dokumen_kegiatan_id" id="dokumen_kegiatan_id" placeholder="Dokumen Kegiatan ID">
                                            <label for="dokumen_kegiatan_id">dokumen_kegiatan_id</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="nama_dokumen" id="nama_dokumen" placeholder="Nama Dokumen">
                                            <label for="nama_dokumen">Nama Dokumen</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="nomor_surat" id="nomor_surat" placeholder="Nomor Persuratan">
                                            <label for="nomor_surat">Nomor Persuratan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>         
                        </div>
              
                <div class="modal-footer" style="display: block;">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="add-btn">Tambah {{ $tittle }}</button>
                        <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
