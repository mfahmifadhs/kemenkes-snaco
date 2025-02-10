@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container col-md-6">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jenis Barang</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jenis-snaco.show') }}">Daftar</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jenis-snaco.detail', $data->id_kategori) }}">Detail</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                    Edit Jenis Barang
                </label>
            </div>
            <form id="form-submit" action="{{ route('jenis-snaco.update', $data->id_kategori) }}" method="POST">
                @csrf
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
                                    <input type="text" class="form-control rounded" name="nama_kategori" value="{{ $data->nama_kategori }}" required>
                                </span>
                            </div>

                            <div class="input-group mt-3">
                                <label class="w-25 col-form-label">Deskripsi</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <textarea name="deskripsi" class="form-control rounded" id="">{{ $data->deskripsi }}</textarea>
                                </span>
                            </div>

                            <div class="input-group mt-3">
                                <label class="w-25 col-form-label">Icon / Foto</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control rounded" name="icon" placeholder="Foto / Icon Barang" value="{{ $data->icon }}">
                                </span>
                            </div>

                            <div class="input-group mt-3">
                                <label class="w-25 col-form-label">Status</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <select name="status" class="form-control rounded">
                                        <option value="true" <?php echo $data->status == 'true' ? 'selected' : ''; ?>>
                                            Tersedia
                                        </option>
                                        <option value="false" <?php echo $data->status == 'false' ? 'selected' : ''; ?>>
                                            Tidak Tersedia
                                        </option>
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
        const stok = $(form).find('.stok').val();
        const jumlah = $(form).find('.jumlah').val();
        const sisa = stok - jumlah;
        const requiredInputs = form.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)');

        console.log(sisa)
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
            if (sisa <= 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'Stok Tidak Tersedia.',
                    icon: 'error'
                });
            } else {
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
            }
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
