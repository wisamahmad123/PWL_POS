@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Stok Barang</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($stok)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('stok') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('/stok/' . $stok->stok_id) }}" class="form-horizontal">
                    @csrf
                    {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh
            method PUT -->
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">supplier</label>
                        <div class="col-11">
                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                <option value="">- Pilih Supplier -</option>
                                @foreach ($supplier as $item)
                                    <option value="{{ $item->supplier_id }}" @if ($item->supplier_id == $stok->supplier_id) selected @endif>
                                        {{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Kode stok</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="stok_kode" name="stok_kode"
                                value="{{ old('stok_kode', $stok->stok_kode) }}" required>
                            @error('stok_kode')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Nama stok</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="stok_nama" name="stok_nama"
                                value="{{ old('stok_nama', $stok->stok_nama) }}" required>
                            @error('stok_nama')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Harga Beli</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="harga_beli" name="harga_beli"
                                value="{{ old('harga_beli', $stok->harga_beli) }}" required>
                            @error('harga_beli')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Harga Jual</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="harga_jual" name="harga_jual"
                                value="{{ old('harga_jual', $stok->harga_jual) }}" required>
                            @error('harga_jual')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label"></label>
                        <div class="col-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('stok') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush
