@extends('layouts.form')
@section('mycontent')
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
                                    <input type="text" class="form-control" name="kegiatan" placeholder="Nama Kegiatan" required="" data-listener-added_28c6f7d8="true">
                                    <label for="kegiatan">Nama Kegiatan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="entity" id="entity" required="">
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
                                    <select class="form-select" name="status_id" id="status_id" required="">
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
                                    <input type="date" class="form-control" name="start_date" id="start_date" required="" data-listener-added_28c6f7d8="true">
                                    <label for="start_date">Tanggal Mulai</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="end_date" id="end_date" required="" data-listener-added_28c6f7d8="true">
                                    <label for="end_date">Tanggal Selesai</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="ddl_tahap" id="ddl_tahap" required="">
                                        <option value="1">Tahap 1 - Indikator</option>
                                        <option value="2">Tahap 2 - Sub Indikator</option>
                                    </select>
                                    <label for="ddl_tahap">Tahap</label>
                                </div>
                            </div>
                        </div>
                        
                    </div>

@endsection