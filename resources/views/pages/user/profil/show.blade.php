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
                <label class="mt-2">Informasi Pegawai</label>
            </div>
            <div class="card-body">
                <div class="input-group form-group">
                    <h6 class="w-25 font-weight-bold form-group">NIP</h6>
                    <h6 class="w-75 form-group">: {{ $user->pegawai->nip }}</h6>

                    <h6 class="w-25 font-weight-bold form-group">Nama Pegawai</h6>
                    <h6 class="w-75 form-group">: {{ $user->pegawai->nama_pegawai }}</h6>

                    <h6 class="w-25 font-weight-bold form-group">Jabatan</h6>
                    <h6 class="w-75 form-group">: {{ $user->pegawai->jabatan->jabatan }}</h6>

                    <h6 class="w-25 font-weight-bold form-group">Tim Kerja</h6>
                    <h6 class="w-75 form-group">: {{ $user->pegawai->timker?->tim_kerja }}</h6>

                    <h6 class="w-25 font-weight-bold form-group">Unit Kerja</h6>
                    <h6 class="w-75 form-group">: {{ $user->pegawai->uker->unit_kerja }}</h6>

                    <h6 class="w-25 font-weight-bold form-group">Unit Utama</h6>
                    <h6 class="w-75 form-group">: {{ $user->pegawai->uker->utama->unit_utama }}</h6>

                    <h6 class="w-25 font-weight-bold form-group">Email</h6>
                    <h6 class="w-75 form-group">: {{ $user->email }}</h6>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $('#utamaSelect').change(function() {
        var selectedUtamaId = $(this).val();

        $.ajax({
            url: '/uker/select/' + selectedUtamaId,
            type: 'GET',
            success: function(data) {
                $('#ukerSelect').empty();
                $.each(data, function(key, val) {
                    $('#ukerSelect').append('<option value="' + val.id + '">' + val.text + '</option>');
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>
@endsection

@endsection
