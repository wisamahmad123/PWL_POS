@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Detail detail Penjualan</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($dPenjualan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $dPenjualan->detail_id }}</td>
                    </tr>
                    <tr>
                        <th>ID Penjualan</th>
                        <td>{{ $dPenjualan->penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td>{{ $dPenjualan->barang->barang_nama }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>{{ $dPenjualan->harga }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>{{ $dPenjualan->jumlah }}</td>
                    </tr>
                </table>
            @endempty
            @if (!empty($dPenjualan))
                <a href="{{ url('/penjualan/' . $dPenjualan->penjualan_id  . '/detail') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @endif
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush
