@extends('layout.app')

@section('content')

@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
    });
</script>
@endif


<div class="content-header">
    <div class="container col-md-6">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0">Profil saya, </h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </div>
            <div class="col-sm-4 text-right my-auto">
                <!--  -->
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container col-md-6">
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">Edit User</label>
            </div>
            <form id="form" action="{{ route('user.update', $id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Unit Utama*</label>
                            <input type="email" class="form-control rounded" value="{{ $user->pegawai->uker->utama->unit_utama }}" readonly>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Unit Kerja*</label>
                            <input type="email" class="form-control rounded" value="{{ $user->pegawai->uker->unit_kerja }}" readonly>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Pegawai*</label>
                            <select id="pegawaiSelect" name="pegawai" class="form-control rounded">
                                <option value="{{ $user->pegawai_id }}">
                                    {{ $user->pegawai->nama_pegawai }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <hr>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Email</label>
                            <input type="email" class="form-control rounded" name="email" value="{{ $user->email }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Username*</label>
                            <input type="text" class="form-control rounded" name="username" value="{{ $user->username }}" minlength="8" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Password*</label>
                            <div class="input-group border-dark">
                                <input type="password" class="form-control border-dark rounded" id="password" name="password" value="{{ $user->password_text }}" placeholder="Masukkan Password" required>
                                <div class="input-group-append border rounded border-dark">
                                    <span class="input-group-text h-100 rounded-0 bg-white">
                                        <i class="fas fa-eye" id="eye-icon-pass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-success btn-sm font-weight-bold" onclick="confirmSubmit(event)">
                        <i class="fas fa-save"></i> SIMPAN
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>

@endsection
