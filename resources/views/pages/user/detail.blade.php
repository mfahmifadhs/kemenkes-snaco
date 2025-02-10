@extends('dashboard.layout.app')

@section('content')

@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
    });
</script>
@endif


<div class="content-wrapper">
    <div class="content-header">
        <div class="container col-md-5">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0">{{ $pegawai->nama_pegawai }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                        <li class="breadcrumb-item active">{{ $pegawai->nama_pegawai }}</li>
                    </ol>
                </div>
                <div class="col-sm-4 text-right my-auto">
                    <a href="{{ route('pegawai.index') }}" class="btn btn-default border border-dark btn-sm rounded-3">
                        <i class="fas fa-arrow-alt-circle-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container col-md-5">
            <div class="card border border-dark">
                <div class="card-header">
                    <label class="mt-2">Informasi Pegawai</label>
                </div>
                <div class="card-body">
                    <div class="input-group form-group">


                        <h6 class="w-25 font-weight-bold">NIP</h6>
                        <h6 class="w-75">: {{ $pegawai->nip }}</h6>

                        <h6 class="w-25 font-weight-bold">Nama Pegawai</h6>
                        <h6 class="w-75">: {{ $pegawai->nama_pegawai }}</h6>

                        <h6 class="w-25 font-weight-bold">Jabatan</h6>
                        <h6 class="w-75">: {{ $pegawai->jabatan->jabatan }}</h6>

                        <h6 class="w-25 font-weight-bold">Tim Kerja</h6>
                        <h6 class="w-75">: {{ $pegawai->timker?->tim_kerja }}</h6>

                        <h6 class="w-25 font-weight-bold">Unit Kerja</h6>
                        <h6 class="w-75">: {{ $pegawai->uker->unit_kerja }}</h6>

                        <h6 class="w-25 font-weight-bold">Unit Utama</h6>
                        <h6 class="w-75">: {{ $pegawai->uker->utama->unit_utama }}</h6>

                        <h6 class="w-25 font-weight-bold">Status</h6>
                        <h6 class="w-75">: {{ $pegawai->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
</div>

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
