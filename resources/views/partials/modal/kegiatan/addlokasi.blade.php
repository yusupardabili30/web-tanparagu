<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $tittle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" action="{{ route('kegiatan.lokasi.store') }}" method="POST" autocomplete="off">
                @csrf
                        <div class="modal-body">         
                            <div class="row">
                                <div class="col-md-12" style="display: none">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="kegiatan_id" value="{{$data->kegiatan_id}}" placeholder="Kegiatan ID">
                                            <label for="kegiatan_id">kegiatan_id</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <select class="form-select" id="ddl_kota" name="ddl_kota">
                                                <option selected="">Pilih Kota</option>
                                                @foreach ($ddl_kota as $row)
                                                    <option value="{{ $row->nama_kota }}">{{ $row->nama_kota }}</option>
                                                @endforeach
                                            </select>
                                            <label for="ddl_kota">Kota</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <select class="form-select" id="ddl_lokasi" name="ddl_lokasi">
                                                <option selected="">Pilih Lokasi Kegiatan</option>
                                                @foreach ($ddl_lokasi as $row)
                                                    <option value="{{ $row->lokasi }}">{{ $row->lokasi }}</option>
                                                @endforeach
                                            </select>
                                            <label for="ddl_lokasi">Lokasi Kegiatan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="lokasi_desc" placeholder="Detail Lokasi">
                                            <label for="nama_ikk">Detail Lokasi Kegiatan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="lokasi_address" placeholder="Alamat Lokasi Kegiatan">
                                            <label for="nama_ikk">Alamat Lokasi Kegiatan</label>
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
