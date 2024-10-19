@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Stok Barang</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">Import Stok</button>
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Stok</a>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Stok</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">Tambah Data(Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <!-- untuk Filter data -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_supplier" class="form-control form-control-sm filter_supplier">
                                    <option value="">- Semua -</option>
                                    @foreach ($supplier as $s)
                                        <option value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Supplier</small>
                            </div>
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_barang" class="form-control form-control-sm filter_barang">
                                    <option value="">- Semua -</option>
                                    @foreach ($barang as $b)
                                        <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Nama Barang</small>
                            </div>
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_user" class="form-control form-control-sm filter_user">
                                    <option value="">- Semua -</option>
                                    @foreach ($user as $u)
                                        <option value="{{ $u->user_id }}">{{ $u->username }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Username</small>
                            </div>
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
            <table class="table table-bordered table-sm table-striped table-hover" id="table-stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Nama Barang</th>
                        <th>Username</th>
                        <th>Tanggal Stokl</th>
                        <th>Jumlah Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"data-width="75%"></div>
@endsection
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataStok;
        $(document).ready(function() {
            dataStok = $('#table-stok').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.filter_supplier = $('.filter_supplier').val();
                        d.filter_barang = $('.filter_barang').val();
                        d.filter_user = $('.filter_user').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "5%",
                    orderable: false,
                    searchable: false
                }, {
                    data: "supplier.supplier_nama",
                    className: "",
                    width: "10%",
                    orderable: true,
                    searchable: true
                }, {
                    data: "barang.barang_nama",
                    className: "",
                    width: "37%",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "user.username",
                    className: "",
                    width: "10%",
                    orderable: true,
                    searchable: true,
                    
                }, {
                    data: "stok_tanggal",
                    className: "",
                    width: "10%",
                    orderable: true,
                    searchable: false,
                    
                }, {
                    data: "stok_jumlah",
                    className: "",
                    width: "14%",
                    orderable: true,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "text-center",
                    width: "14%",
                    orderable: false,
                    searchable: false
                }]
            });
            $('#table-stok_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    dataStok.search(this.value).draw();
                }
            });
            $('.filter_supplier').change(function() {
                dataStok.draw();
            });
            $('.filter_barang').change(function() {
                dataStok.draw();
            });
            $('.filter_user').change(function() {
                dataStok.draw();
            });
        });
    </script>
@endpush
