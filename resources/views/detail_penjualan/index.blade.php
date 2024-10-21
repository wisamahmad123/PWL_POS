@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Detail Penjualan</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/penjualan/'. $id .'/detail/import') }}')" class="btn btn-info">Import Detail
                    Penjualan</button>
                <a href="{{ url('/penjualan/'. $id .'/detail/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i>
                    Export Detail Penjualan</a>
                <a href="{{ url('/penjualan/'. $id .'/detail/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i>
                    Export Detail Penjualan</a>
                <button onclick="modalAction('{{ url('/penjualan/'. $id .'/detail/create_ajax') }}')" class="btn btn-success">Tambah
                    Data(Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm" id="table-detail-penjualan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Penjualan</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data- backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataDetailPenjualan;
        $(document).ready(function() {
            dataDetailPenjualan = $('#table-detail-penjualan').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan/' . $id . '/detail/list') }}",
                    "dataType": "json",
                    "type": "POST",
                },

                columns: [{
                    // nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "penjualan.penjualan_id",
                    className: "",
                    // orderable: true, jika ingin kolom ini bisa diurutkan
                    orderable: true,
                    // searchable: true, jika ingin kolom ini bisa dicari
                    searchable: true
                }, {
                    data: "barang.barang_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    // mengambil data user hasil dari ORM berelasi
                    data: "harga",
                    className: "",
                    orderable: true,
                    searchable: false
                }, {
                    // mengambil data user hasil dari ORM berelasi
                    data: "jumlah",
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
        });
    </script>
@endpush
