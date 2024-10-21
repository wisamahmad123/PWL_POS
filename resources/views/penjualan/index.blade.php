@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Penjualan</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-info">Import Penjualan</button>
                <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Penjualan</a>
                <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Penjualan</a>
                <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-success">Tambah Data(Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_user" class="form-control form-control-sm filter_user">
                                    <option value="">- Semua -</option>
                                    @foreach ($user as $u)
                                        <option value="{{ $u->user_id }}">{{ $u->username }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">User</small>
                            </div>
                        </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table-penjualan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Pembeli</th>
                        <th>Kode Penjualan</th>
                        <th>Tanggal Penjualan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-
    backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('js')
    <script>
        function modalAction(url = ''){
            $('#myModal').load(url,function(){
                $('#myModal').modal('show');
            });
        }
        
        var dataPenjualan;
        $(document).ready(function() {
            dataPenjualan = $('#table-penjualan').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.filter_user = $('.filter_user').val();
                    }
                },
                columns: [{
                    // nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "user.username",
                    className: "",
                    // orderable: true, jika ingin kolom ini bisa diurutkan
                    orderable: true,
                    // searchable: true, jika ingin kolom ini bisa dicari
                    searchable: true
                }, {
                    data: "pembeli",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    // mengambil data user hasil dari ORM berelasi
                    data: "penjualan_kode",
                    className: "",
                    orderable: true,
                    searchable: false
                }, {
                    // mengambil data user hasil dari ORM berelasi
                    data: "penjualan_tanggal",
                    className: "",
                    orderable: true,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }]
            });
            $('#table-penjualan_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    dataPenjualan.search(this.value).draw();
                }
            });
            $('.filter_user').change(function() {
                dataPenjualan.draw();
            });
        });
    </script>
@endpush