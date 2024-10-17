@extends('layouts.template') <!-- Atau sesuaikan dengan layout Anda -->

@section('content')
<div class="container mt-4">
    <h2>Upload Foto Profil</h2>
    @if (Auth::user()->profile_picture)
                <img src="{{ asset('storage/profile_pictures/' . Auth::UserModel()->profile_picture) }}" alt="Foto Profil">
            @else
                <img src="{{ asset('default_profil.png') }}" alt="Foto Profil Default">
            @endif
    <!-- Tampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tampilkan pesan error jika ada -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
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
</div>
@endsection
