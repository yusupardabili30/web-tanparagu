<!-- Default Modals -->
<div id="taskModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Daftar Penugasan Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('penugasandokumen.store') }}" method="POST">
                @csrf
                <input type="text" class="form-control" style="display: none" name="dokumen_kegiatan_id" id="txtdokumen_kegiatan_id" value="" placeholder="Dokumen Kegiatan">
                <input type="text" name="kegiatan_id" style="display: none" value="{{ $data['kegiatan']->kegiatan_id }}"/>
                <div class="table-responsive table-card mb-4" style="margin-top: 2px">
                    <table class="table align-middle table-nowrap mb-0" id="ticketTable">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 40px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                    </div>
                                </th>
                                <th class="sort" data-sort="id">No</th>
                                <th class="sort" data-sort="peran_id" style="display: none">Peran ID</th>
                                <th class="sort" data-sort="nama">Nama</th>
                                <th class="sort" data-sort="jabatan">Jabatan</th>            
                                <th class="sort" data-sort="lokasi_kantor">Lokasi Kantor</th>           
                            </tr>
                        </thead>
                        <tbody class="list form-check-all" id="ticket-list-data-modal">
                            @php
                                $i=1;
                            @endphp
                            @foreach ($data['penugasan_dokumen'] as $row)                                                      
                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input select-row" type="checkbox" name="user_id[]" value="{{ $row->user_id }}">
                                        </div>
                                    </th>
                                    <td class="id"><a href="javascript:void(0);" onclick="ViewTickets(this)" data-id="001" class="fw-medium link-primary">{{$i}}</a></td>
                                    {{-- <td class="tasks_name peran_id" style="display: block">{{$row->user_id}}</td> --}}
                                    <td class="tasks_name">{{$row->nama}}</td>
                                    <td class="tasks_name">{{$row->jabatan}}</td>
                                    <td class="tasks_name">{{$row->lokasi_kantor}}</td>                                                      
                                </tr>
                                @php
                                $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>   
                    {{-- {!! $data['penugasan_dokumen']->withQueryString()->links('pagination::bootstrap-5') !!}           --}}
                </div>   
                <div id="pagination">
                    <!-- Tombol halaman akan dimasukkan di sini -->
                </div>                                 
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary ">Save Changes</button>
                </div>
            </form> 
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{-- @section('sipproja-js')
<script>

    var currentPage = 1;  // Pastikan currentPage berada di luar fungsi agar bisa diakses global
    // Fungsi untuk mengganti halaman
    function changePage(page) {
        currentPage = page;  // Update currentPage
        fetchResults(currentPage);  // Memuat data berdasarkan halaman
    }

    // Fungsi untuk mengambil hasil pencarian
    function fetchResults(page) {
        $.ajax({
            url: '/home/kegiatan/detail/perencanaanajax/14',  // Sesuaikan dengan rute yang sesuai
            method: 'GET',  // Menggunakan POST
            data: {
                page: page  // Kirim parameter halaman
            },
            success: function(response) {
                console.log(response);
                        
                if (response.penugasan_dokumen.length > 0) {
                    // Bersihkan hasil sebelumnya
                    $('#ticket-list-data-modal').empty();

                    // Loop dan menampilkan data pegawai
                    var resultHTML = '';
                    console.log(response.penugasan_dokumen);
                    $.each(response.penugasan_dokumen, function(index, item) {
                        resultHTML += '<tr data-id="'+item.pegawai_id+'">';
                        resultHTML += '<th scope="row">' +
                                          '<div class="form-check">' +
                                            '<input class="form-check-input select-row" type="checkbox" name="user_id[]" value="' + item.pegawai_id + '">' +
                                          '</div>' +
                                      '</th>';
                        resultHTML += '<td class="tasks_name">' + (index+1) + '</td>';
                        resultHTML += '<td class="tasks_name">' + item.nama + '</td>';
                        resultHTML += '<td class="tasks_name">' + item.jabatan + '</td>';
                        resultHTML += '<td class="tasks_name">' + item.lokasi_kantor + '</td>';
                        
                        resultHTML += '</tr>';
                    });
                    $('#ticket-list-data-modal').html(resultHTML); // Bisa menampilkan di elemen lain jika diperlukan

                    // Menampilkan pagination
                    var paginationHTML = '';
                    if (response.pagination.total > 0) {
                        for (var i = 1; i <= response.pagination.last_page; i++) {
                            paginationHTML += '<li class="page-item ' + (i === response.pagination.current_page ? 'active' : '') + '">';
                            paginationHTML += '<a class="page-link" href="javascript:void(0)" onclick="changePage(' + i + ')">' + i + '</a>';
                            paginationHTML += '</li>';
                        }
                        $('#pagination').html('<ul class="pagination">' + paginationHTML + '</ul>');  // Menampilkan pagination
                    }
                } else {
                    hasil = '<p style="text-align:center">Tidak ditemukan pegawai yang sesuai.</p>';
                    resultHTML += '<td colspan="5" class="tasks_name">' + hasil + '</td>';
                    resultHTML += '</tr>';
                    $('#ticket-list-data-modal').html(resultHTML);  // Menampilkan pesan jika tidak ada data
                }
            },
            error: function() {
                $('#ticket-list-data-modal').html('<p>Terjadi kesalahan. Silakan coba lagi.</p>');
            }
        });
    }

    $(document).ready(function() {    
        //fetchResults(currentPage);       
    });

</script>
@endsection --}}

