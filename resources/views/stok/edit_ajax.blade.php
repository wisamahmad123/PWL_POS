@empty($stok)
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
                <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/stok/' . $stok->stok_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <select name="supplier" id="supplier_id" class="form-control" required>
                            <option value="">- Pilih Supplier -</option>
                            @foreach ($supplier as $s)
                                <option {{ $s->supplier_id == $stok->supplier_id ? 'selected' : '' }}
                                    value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="barang" id="barang_id" class="form-control" required>
                            <option value="">- Pilih Barang -</option>
                            @foreach ($barang as $b)
                                <option {{ $b->barang_id == $stok->barang_id ? 'selected' : '' }}
                                    value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <select name="user" id="user_id" class="form-control" required>
                            <option value="">- Pilih Username -</option>
                            @foreach ($user as $u)
                                <option {{ $u->user_id == $user->user_id ? 'selected' : '' }} value="{{ $u->user_id }}">
                                    {{ $u->user_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Stok</label>
                        <input value="{{ $stok->stok_tanggal }}" type="text" name="stok_tanggal" id="stok_tanggal"
                            class="form-control" required>
                        <small id="error-stok_tanggal" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Stok</label>
                        <input value="{{ $stok->stok_jumlah }}" type="text" name="stok_jumlah" id="stok_jumlah"
                            class="form-control" required>
                        <small id="error-stok_jumlah" class="error-text form-text text-danger"></small>
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
            jQuery.validator.addMethod("dateTimeFormat", function(value, element) {
                // Regex untuk format YYYY-MM-DD HH:MM:SS
                return this.optional(element) || /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(value);
            }, "Format tanggal dan waktu harus YYYY-MM-DD HH:MM:SS");
            $("#form-edit").validate({
                rules: {
                    supplier_id: {
                        required: true,
                        number: true
                    },
                    barang_id: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    user_id: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    stok_tanggal: {
                        required: true,
                        dateTimeFormat: true
                    },
                    stok_jumlah: {
                        required: true,
                        minlength: 1
                    },
                    messages: {
                        stok_tanggal: {
                            required: "Tanggal dan waktu wajib diisi",
                            dateTimeFormat: "Format tanggal dan waktu harus YYYY-MM-DD HH:MM:SS"
                        }
                    }
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
                                dataStok.ajax.reload();
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
