<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $tittle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" action="{{ route('sasaran.store') }}" method="POST" autocomplete="off">
                @csrf
                        <div class="modal-body">         
                            <div class="row">
                                <div class="col-md-12" style="display: none">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="kegiatan_id" placeholder="Kegiatan ID">
                                            <label for="kegiatan_id">kegiatan_id</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="url_wa" placeholder="Link. Wa Group">
                                            <label for="url_wa">Link. Wa Group</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="url_ig" placeholder="Link. Instagram">
                                            <label for="url_wa">Link Instagram</label>
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
