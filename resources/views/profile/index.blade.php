@extends('layouts.template') <!-- Atau sesuaikan dengan layout Anda -->

@section('content')
    <div class="container mt-4">
        <h2>Upload Foto Profil</h2>
        @if (Auth::user()->profile_picture)
            <img src="{{ asset('storage/profile_pictures/' . Auth::user()->profile_picture) }}" alt="Foto Profil"
                width="150" height="150">
        @else
            <img src="{{ asset('default_profil.png') }}" alt="Foto Profil Default" width="150" height="150">
        @endif

        <!-- Tampilkan pesan sukses jika ada -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tampilkan pesan error jika ada -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <!-- Form untuk upload foto profil -->
                <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Upload Foto Profil</label>
                        <input type="file" class="form-control" name="profile_picture" id="profile_picture" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Informasi Pengguna</h3>
            </div>
            <div class="card-body">
                <table id="table-profile" class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Password</th>
                            <th>Aksi</th> <!-- Tambahkan kolom aksi -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ Auth::user()->username }}</td>
                            <td>{{ Auth::user()->nama }}</td>
                            <td>********</td>
                            <td>
                                <button
                                    onclick="modalAction('{{ url('/profile/' . Auth::user()->user_id . '/change_profile') }}')"
                                    class="btn btn-warning btn-sm">Change Profile</button>
                                <button
                                    onclick="modalAction('{{ url('/profile/' . Auth::user()->user_id . '/change_password') }}')"
                                    class="btn btn-danger btn-sm">Change Password</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
@endsection

@push('js')
    <script>
        var dataProfile;

        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        $(document).ready(function() {
            // Inisialisasi DataTable
            dataProfile = $('#table-profile').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/profile') }}",
                    type: 'POST'
                },
                columns: [{
                        data: "username",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button onclick="modalAction('{{ url('/profile/') }}/${row.user_id}/change_profile')" class="btn btn-warning btn-sm">Change Profile</button>
                    <button onclick="modalAction('{{ url('/profile/') }}/${row.user_id}/change_password')" class="btn btn-danger btn-sm">Change Password</button>`;
                        }
                    },
                ],
                paging: false,
                searching: false
            });

            // Validasi Form Edit
            $("#form-edit").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            console.log(response); // Untuk debug
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataProfile.ajax.reload(); // Reload DataTable
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
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
