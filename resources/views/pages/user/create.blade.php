@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-7">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Tambah User</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('user.show') }}">Daftar</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>

            </div>
            <div class="col-sm-6 text-right">
                <!-- <a href="{{ url('unit-kerja/ukt/usulan/pekerjaan/baru') }}" class="btn btn-primary btn-md font-weight-bold mt-3">
                    <i class="fas fa-plus-square"></i> PENGAJUAN
                </a> -->
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid col-md-7">
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">Tambah User</label>
            </div>
            <form id="form" action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Unit Utama*</label>
                            <select id="utamaSelect" name="utama" class="form-control rounded">
                                <option value="">-- Pilih Unit Utama --</option>
                                @foreach($utama as $row)
                                <option value="{{ $row->id_unit_utama }}">{{ $row->unit_utama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Unit Kerja*</label>
                            <select id="ukerSelect" name="uker" class="form-control rounded">
                                <option value="">-- Pilih Unit Kerja --</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>NIP*</label>
                            <input type="number" class="form-control rounded" name="nip" min="1" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nama Pegawai*</label>
                            <input type="text" class="form-control rounded" name="pegawai" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Jabatan</label>
                            <select name="jabatan" class="form-control rounded">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatan as $row)
                                <option value="{{ $row->id_jabatan }}">{{ $row->jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tim Kerja</label>
                            <select name="timker" class="form-control rounded">
                                <option value="">-- Pilih Tim Kerja --</option>
                                @foreach($timker as $row)
                                <option value="{{ $row->id_tim_kerja }}">{{ $row->tim_kerja }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label>Role*</label>
                            <select name="role" class="form-control rounded" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach ($role as $row)
                                <option value="{{ $row->id_role }}">{{ $row->nama_role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Email</label>
                            <input type="email" class="form-control rounded" name="email">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Username*</label>
                            <input type="text" class="form-control rounded" name="username" minlength="8" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Password*</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-dark rounded" id="password" name="password" placeholder="Masukkan Password" required>
                                <div class="input-group-append border rounded border-dark">
                                    <span class="input-group-text h-100 rounded-0 bg-white">
                                        <i class="fas fa-eye" id="eye-icon-pass"></i>
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <label class="w-100">Status</label>
                            <input id="true" type="radio" name="status" value="true">
                            <label for="true" class="mt-2 ml-2 mr-4">Aktif</label>
                            <input id="false" type="radio" name="status" value="false">
                            <label for="false" class="mt-2 ml-2">Tidak Aktif</label>
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

@section('js')
<script>
    $('#utamaSelect').change(function() {
        var selectedUtamaId = $(this).val();

        $.ajax({
            url: '/unit-kerja/selectUtama/' + selectedUtamaId,
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

    $('#ukerSelect').change(function() {
        var selectedUkerId = $(this).val();

        $.ajax({
            url: '/pegawai/selectUker/' + selectedUkerId,
            type: 'GET',
            success: function(data) {
                $('#pegawaiSelect').empty();
                $.each(data, function(key, val) {
                    $('#pegawaiSelect').append('<option value="' + val.id + '">' + val.text + '</option>');
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>

<script>
    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');
        const requiredInputs = form.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)');

        let allInputsValid = true;

        requiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                allInputsValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        if (allInputsValid) {
            Swal.fire({
                title: 'Submit',
                text: '',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Ada input yang diperlukan yang belum diisi.',
                icon: 'error'
            });
        }
    }
</script>
@endsection

@endsection
