    @empty($dPenjualan)
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                        Data yang anda cari tidak ditemukan
                    </div>
                    <a href="{{ url('/penjualan/' . $dPenjualan->penjualan_id . '/detail') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </div>
    @else
        <form action="{{ url('/penjualan/' . $dPenjualan->penjualan_id . '/detail/' .  $dPenjualan->detail_id . '/update_ajax') }}" method="POST" id="form-edit">
            @csrf
            @method('PUT')
            <div id="modal-master" class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Detail Penjualan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>ID Penjualan</label>
                            <select name="penjualan_id" id="penjualan_id" class="form-control" required>
                                <option value="">- Pilih ID Penjualan -</option>
                                @foreach ($penjualan as $p)
                                    <option {{ $p->penjualan_id == $dPenjualan->penjualan_id ? 'selected' : '' }}
                                        value="{{ $p->penjualan_id }}">{{ $p->penjualan_id }}</option>
                                @endforeach
                            </select>
                            <small id="error-penjualan_id" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <select name="barang_id" id="barang_id" class="form-control" required>
                                <option value="">- Pilih Nama Barang -</option>
                                @foreach ($barang as $b)
                                    <option {{ $b->barang_id == $dPenjualan->barang_id ? 'selected' : '' }}
                                        value="{{ $b->barang_id }}">{{ $b->barang_nama }}></option>
                                @endforeach
                            </select>
                            <small id="error-barang_id" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input value="{{ $dPenjualan->harga }}" type="text" name="harga" id="harga" class="form-control"
                                required>
                            <small id="error-harga" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input value="{{ $dPenjualan->jumlah }}" type="text" name="jumlah" id="jumlah" class="form-control"
                                required>
                            <small id="error-jumlah" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                $("#form-edit").validate({
                    rules: {
                        penjualan_id: {
                            required: true,
                            number: true
                        },
                        barang_id: {
                            required: true,
                            number: true
                        },
                        harga: {
                            required: true,
                            number: true
                        },
                        jumlah: {
                            required: true,
                            number: true
                        },
                    },
                    submitHandler: function(form) {
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                if (response.status) {
                                    $('#myModal').modal('hide');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    });
                                    dataDetailPenjualan.ajax.reload();
                                } else {
                                    $('.error-text').text('');
                                    $.each(response.msgField, function(prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                    });
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Terjadi Kesalahan',
                                        text: response.message
                                    });
                                }
                            }
                        });
                        return false;
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        </script>
    @endempty
