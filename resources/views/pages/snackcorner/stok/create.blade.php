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
    <div class="container col-md-9">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Stok</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('snaco.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('snaco.stok.show') }}">Daftar</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container col-md-9">
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">
                    Tambah Stok
                </label>
            </div>
            <form id="form-submit" action="{{ route('snaco.stok.store') }}" method="POST">
                @csrf
                <div class="card-body small text-capitalize">
                    <div class="d-flex">
                        <div class="w-50 text-left">
                            <label>Detail</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label class="w-25 col-form-label">Tanggal</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="date" class="form-control rounded" name="tanggal_masuk" required>
                                </span>
                            </div>

                            <div class="input-group mt-2">
                                <label class="w-25 col-form-label">Nomor Kwitansi</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control rounded" name="no_kwitansi">
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="input-group">
                                <label class="w-25 col-form-label">Total Harga</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control number rounded" name="total_harga" required>
                                </span>
                            </div>

                            <div class="input-group mt-2">
                                <label class="w-25 col-form-label">Keterangan</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control rounded" name="keterangan" value="{{ $keterangan }}" required>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body small">
                    <div class="d-flex">
                        <label class="w-50">
                            <i class="fas fa-boxes"></i> Daftar Barang
                        </label>
                        <label class="w-50 text-right">
                            <a href="#" class="btn btn-default btn-xs bg-primary rounded" data-toggle="modal" data-target="#tambahItem">
                                <i class="fas fa-plus-circle p-1" style="font-size: 12px;"></i> Tambah
                            </a>
                        </label>
                    </div>
                    <div class="table-responsive" style="max-height: 230px; overflow-y: auto;">
                        <table class="table table-bordered text-center text-sm">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 10%;">Aksi</th>
                                    <th style="width: 15%;">Barang</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 10%;">Jumlah</th>
                                    <th style="width: 10%;">Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang as $index => $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="#" class="btn btn-default btn-xs bg-primary rounded" data-toggle="modal" data-target="#editItem-{{ $row->id_keranjang }}">
                                            <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                        </a>
                                        <a href="#" class="btn btn-default btn-xs bg-danger rounded" onclick="confirmRemove(event, `{{ route('snaco.stok.item.delete', ['aksi' => 'bucket', 'id' => $row->id_keranjang]) }}`)">
                                            <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                        </a>

                                        <div class="modal fade text-left" id="editItem-{{ $row->id_keranjang }}" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Barang</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form id="form-edit-{{ $row->id_keranjang }}" action="{{ route('snaco.stok.item.update', $row->id_keranjang) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="d-flex">
                                                                <label class="w-25 col-form-label">Pilih Barang</label>
                                                                <select name="" id="kategori-{{ $index }}" class="w-75 form-control kategori" style="width: 75%;" required>
                                                                    <option value="">-- Pilih Barang --</option>
                                                                    @foreach ($kategori as $subRow)
                                                                    <option value="{{ $subRow->id_kategori }}" {{ $subRow->id_kategori == $row->snc->snc_kategori ? 'selected' : '' }}>
                                                                        {{ $subRow->nama_kategori }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="d-flex mt-2">
                                                                <label class="w-25 col-form-label">Pilih Merk</label>
                                                                <select name="id_snc" id="barang-{{ $index }}" class="w-75 form-control barang" style="width: 75%;" required>
                                                                    <option value="{{ $row->snc_id }}">
                                                                        {{ $row->snc->snc_nama }} {{ $row->snc->snc_deskripsi }}
                                                                    </option>
                                                                </select>
                                                            </div>

                                                            <div class="d-flex mt-2">
                                                                <label class="w-25 col-form-label">Jumlah</label>
                                                                <input type="number" class="form-control text-center w-50 ml-1 number" name="jumlah" value="{{ $row->kuantitas }}" min="1" required>
                                                                <input id="satuan" type="text" class="form-control text-center w-25 ml-2" value="{{ $row->snc->satuan->satuan }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'form-edit-{{ $row->id_keranjang }}')">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-left">{{ $row->snc->kategori->nama_kategori }}</td>
                                    <td class="text-left">{{ $row->snc->snc_nama }} {{ $row->snc->snc_deskripsi }}</td>
                                    <td>{{ number_format($row->kuantitas, 0, '.') }}</td>
                                    <td>{{ $row->snc->satuan->satuan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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


<!-- Modal Tambah -->
<div class="modal fade" id="tambahItem" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-sm">
            <div class="modal-header">
                <h5 class="modal-title">Tambah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add" action="{{ route('snaco.stok.item.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="d-flex">
                        <label class="w-25 col-form-label">Pilih Barang</label>
                        <select name="" id="kategori-add" class="w-75 form-control kategori" style="width: 75%;" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($kategori as $subRow)
                            <option value="{{ $subRow->id_kategori }}">
                                {{ $subRow->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Pilih Merk</label>
                        <select name="id_snc" id="barang-add" class="w-75 form-control barang" style="width: 75%;" required>
                            <option value="">-- Pilih Barang --</option>
                        </select>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Jumlah</label>
                        <input type="number" class="form-control text-center w-50 number" name="jumlah" value="1" min="1" required>
                        <input id="satuan" type="text" class="form-control text-center w-25 ml-2" placeholder="satuan" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'form-add')">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk semua elemen dengan class kategori dan barang
        $('.kategori, .barang').select2();

        // Event listener untuk elemen kategori
        $(document).on('change', '.kategori', function() {
            var kategoriId = $(this).val();
            var index = $(this).attr('id').split('-')[1]; // Ambil indeks dari ID

            $.ajax({
                url: '/snaco/detail/barang/' + kategoriId,
                type: 'GET',
                success: function(data) {
                    var barangSelect = $('#barang-' + index);
                    barangSelect.empty();
                    $.each(data, function(key, val) {
                        barangSelect.append('<option value="' + val.id + '" data-satuan="' + val.satuan + '">' + val.text + '</option>');
                    });

                    // Refresh Select2 untuk barang
                    barangSelect.select2();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        // Event listener untuk elemen barang
        $(document).on('change', '.barang', function() {
            var selectedOption = $(this).find('option:selected');
            var satuan = selectedOption.data('satuan');

            $('#satuan').val(satuan || '');
        });
    });
</script>

<script>
    function confirmRemove(event, url) {
        event.preventDefault();

        Swal.fire({
            title: 'Hapus',
            text: '',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

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
