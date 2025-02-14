@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container col-md-9">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Kegiatan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kegiatan.show') }}">Daftar</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kegiatan.detail', $kegiatan->id_kegiatan) }}">Detail</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                    Edit Kegiatan
                </label>
            </div>
            <form id="form-submit" action="{{ route('kegiatan.update', $kegiatan->id_kegiatan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body text-capitalize">
                    <div class="d-flex">
                        <div class="w-50 text-left">
                            <label class="text-secondary"><i>Informasi Kegiatan</i></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <label class="w-25 col-form-label">Tanggal</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="date" class="form-control rounded" name="tanggal" value="{{ Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('Y-m-d') }}" required>
                                </span>
                            </div>

                            <div class="input-group mt-2">
                                <label class="w-25 col-form-label">Nama Kegiatan</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control rounded" name="kegiatan" value="{{ $kegiatan->nama_kegiatan }}">
                                </span>
                            </div>

                            <div class="input-group mt-2">
                                <label class="w-25 col-form-label">Total Peserta</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="number" class="form-control number rounded" name="peserta" value="{{ $kegiatan->jumlah_peserta }}" required>
                                </span>
                            </div>

                            <div class="input-group mt-2">
                                <label class="w-25 col-form-label">Keterangan</label>
                                <span class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    <input type="text" class="form-control rounded" name="keterangan" value="{{ $kegiatan->keterangan }}">
                                </span>
                            </div>

                            <div class="input-group mt-2">
                                <label class="w-25 col-form-label">Absensi</label>
                                <div class="w-75 input-group"><span class="col-form-label mx-2">:</span>
                                    @if (!$kegiatan->data_pendukung)
                                    <div class="btn btn-default btn-file w-75 border border-dark p-2">
                                        <i class="fas fa-upload"></i> Upload File
                                        <input type="file" class="form-control image" name="file" onchange="displaySelectedFile(this)" accept=".pdf" required>
                                        <span id="selected-file-name"></span>
                                    </div>
                                    @endif

                                    @if ($kegiatan->data_pendukung)
                                    <a href="{{ route('kegiatan.lihat-pdf', $kegiatan->id_kegiatan) }}" class="btn btn-danger border-dark" target="_blank">
                                        <i class="fas fa-file-pdf"></i> <small>{{ $kegiatan->data_pendukung }}</small>
                                    </a>
                                    <a href="#" class="btn btn-warning border-dark ml-2" onclick="confirmRemove(event, `{{ route('kegiatan.hapus-pdf', $kegiatan->id_kegiatan) }}`)">
                                        <i class="fas fa-trash-alt"></i> <small>Hapus</small>
                                    </a>
                                    <input type="hidden" name="file" value="{{ $kegiatan->data_pendukung }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body small">
                    <div class="d-flex">
                        <label class="w-50 text-secondary">
                            <i>Informasi Barang</i>
                        </label>
                        <label class="w-50 text-right">
                            <a href="#" class="btn btn-default btn-xs bg-primary rounded" data-toggle="modal" data-target="#tambahItem">
                                <i class="fas fa-plus-circle p-1" style="font-size: 12px;"></i> Tambah
                            </a>
                        </label>
                    </div>
                    <div class="table-responsive" style="max-height: 230px; overflow-y: auto;">
                        <table class="table table-bordered text-center">
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
                                @if ($kegiatan->detail)
                                @foreach($kegiatan->detail as $index => $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="#" class="btn btn-default btn-xs bg-primary rounded" data-toggle="modal" data-target="#editItem-{{ $row->id_detail }}">
                                            <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                        </a>
                                        <a href="#" class="btn btn-default btn-xs bg-danger rounded" onclick="confirmRemove(event, `{{ route('kegiatan.item.delete', $row->id_detail) }}`)">
                                            <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                        </a>
                                    </td>
                                    <td class="text-left">{{ $row->snc->kategori->nama_kategori }}</td>
                                    <td class="text-left">{{ $row->snc->snc_nama }} {{ $row->snc->snc_deskripsi }}</td>
                                    <td>{{ number_format($row->jumlah, 0, '', '.') }}</td>
                                    <td>{{ $row->snc->satuan->satuan }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">Tidak ada data</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success btn-sm" onclick="confirmSubmit(event, 'form-submit')">
                        <i class="fas fa-save"></i> Simpan
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
            <form id="form-add" action="{{ route('kegiatan.item.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id_kegiatan }}">
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
                        <label class="w-25 col-form-label">Stok</label>
                        <input type="text" class="form-control text-center w-50 ml-1 stok" name="stok" placeholder="stok" readonly>
                        <input type="text" class="form-control text-center w-25 ml-2 satuan" placeholder="satuan" readonly>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Jumlah</label>
                        <input type="number" class="form-control text-center w-50 ml-1 jumlah" name="jumlah" value="1" min="1" required>
                        <input type="text" class="form-control text-center w-25 ml-2 satuan" placeholder="satuan" readonly>
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

<!-- Modal Edit -->
@foreach($kegiatan->detail as $index => $row)
<div class="modal fade text-left" id="editItem-{{ $row->id_detail }}" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-sm">
            <div class="modal-header">
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit-{{ $row->id_detail }}" action="{{ route('kegiatan.item.update', $row->id_detail) }}" method="POST">
                @csrf
                <input type="hidden" name="snc_id" value="{{ $row->snc_id }}">
                <div class="modal-body">
                    <div class="d-flex">
                        <label class="w-25 col-form-label">Pilih Barang</label>
                        <select name="" id="kategori-{{ $index }}" class="w-75 form-control kategori" style="width: 75%;" disabled>
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
                        <select name="id_snc" id="barang-{{ $index }}" class="w-75 form-control barang" style="width: 75%;" disabled>
                            <option value="{{ $row->snc_id }}">
                                {{ $row->snc->snc_nama }} {{ $row->snc->snc_deskripsi }}
                            </option>
                        </select>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Stok</label>
                        <input type="text" class="form-control text-center w-50 ml-1 stok" name="stok" placeholder="stok" value="{{ $row->snc->stokUker(Auth::user()->pegawai->uker_id) }}" readonly>
                        <input type="text" class="form-control text-center w-25 ml-2 satuan" value="{{ $row->snc->satuan->satuan }}" placeholder="satuan" readonly>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Jumlah</label>
                        <input type="number" class="form-control text-center w-50 ml-1 jumlah" name="jumlah" value="{{ $row->jumlah }}" min="1" required>
                        <input type="text" class="form-control text-center w-25 ml-2 satuan" value="{{ $row->snc->satuan->satuan }}" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'form-edit-{{ $row->id_detail }}')">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


@section('js')
@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
    });
</script>
@endif

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
            var barangId = $(this).val();

            $('.satuan').val(satuan || '');

            $.ajax({
                url: '/snaco/stok/uker/barang/' + barangId, // Endpoint untuk mengambil stok
                type: 'GET',
                success: function(data) {
                    // Tampilkan stok di input field
                    $('.stok').val(data.stok); // Misalnya, data.stok mengandung stok yang tersedia

                    // Batasi input jumlah berdasarkan stok
                    $('.jumlah').attr('max', data.stok);
                },
                error: function(error) {
                    console.log(error);
                }
            });
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
                Swal.fire({
                    title: 'Proses...',
                    text: 'Mohon menunggu.',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = url;
            }
        });
    }

    function confirmSubmit(event, formId) {
        event.preventDefault();

        const form = document.getElementById(formId);
        const stok = $(form).find('.stok').val();
        const jumlah = $(form).find('.jumlah').val();
        const sisa = stok - jumlah;
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
