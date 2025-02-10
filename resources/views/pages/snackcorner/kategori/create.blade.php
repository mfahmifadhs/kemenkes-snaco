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
            <div class="col-sm-6">
                <h1 class="m-0">Jenis Barang</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jenis-snaco.show') }}">Daftar</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container col-md-6">
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">
                    Tambah Jenis Barang
                </label>
            </div>
            <form id="form-submit" action="{{ route('jenis-snaco.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <div class="card-body text-capitalize">
                    <div class="d-flex">
                        <div class="w-50 text-left">
                            <label class="text-secondary"><i>Informasi Jenis Barang</i></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mt-3">
                                <label class="w-25 col-form-label">Nama Jenis Barang</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control rounded" name="nama_kategori" placeholder="Contoh : Snack / Makanan Basah/ Buah" required>
                                </span>
                            </div>

                            <div class="input-group mt-3">
                                <label class="w-25 col-form-label">Deskripsi</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <textarea name="deskripsi" class="form-control rounded" id=""></textarea>
                                </span>
                            </div>

                            <div class="input-group mt-3">
                                <label class="w-25 col-form-label">Icon / Foto</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control rounded" name="icon" placeholder="Foto / Icon Barang">
                                </span>
                            </div>

                            <div class="input-group mt-3">
                                <label class="w-25 col-form-label">Status</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <select name="status" class="form-control rounded">
                                        <option value="true">Tersedia</option>
                                        <option value="false">Tidak Tersedia</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-sm" onclick="confirmSubmit(event, 'form-submit')">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script>
    function confirmSubmit(event, formId) {
        event.preventDefault();

        const form = document.getElementById(formId);
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
                    Swal.fire({
                        title: 'Proses...',
                        text: 'Mohon menunggu.',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

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
