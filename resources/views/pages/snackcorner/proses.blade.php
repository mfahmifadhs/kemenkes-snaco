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
                <h1 class="m-0">Proses Usulan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('snaco.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usulan.show', 'snc') }}">Daftar</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('snaco.detail', $data->id_usulan) }}">Detail</a></li>
                    <li class="breadcrumb-item active">Proses</li>
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
                    Proses Permintaan Snack Corner
                </label>
                <div class="card-tools">
                    <div class="input-group">
                        <div class="form-group ml-1">
                            <form id="form-submit" action="{{ route('snaco.proses', $data->id_usulan) }}" method="GET">
                                @csrf
                                <input type="hidden" name="proses" value="selesai">
                                <input type="hidden" name="tanggal_bast" id="tanggal_bast">
                                <input type="hidden" name="otp" id="otp">
                                <input type="hidden" name="penerima" id="penerima">

                                <button type="submit" class="btn btn-warning border-dark btn-sm mt-2" onclick="confirmOtp(event)">
                                    <i class="fas fa-file-import"></i> Proses
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header d-flex text-center flex-wrap justify-content-center">
                @php
                if (!$data->status_proses) {
                $verifikasi = 'bg-warning';
                } else if ($data->status_persetujuan == 'false') {
                $verifikasi = 'bg-danger';
                } else if ($data->status_persetujuan == 'true') {
                $verifikasi = 'bg-success';
                } else {
                $verifikasi = '';
                }
                @endphp
                <span class="w-25 w-md-25 border border-dark {{ $verifikasi }} p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-1 fa-3x"></i>
                    <b class="ms-3 ml-2">Verifikasi</b>
                </span>
                @php
                if ($data->status_proses == 'selesai') {
                $proses = 'bg-success';
                } else if ($data->status_persetujuan == 'true' && $data->status_proses == 'proses') {
                $proses = 'bg-warning';
                } else {
                $proses = '';
                }
                @endphp
                <span class="w-50 w-md-50 border border-dark {{ $proses }} p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-2 fa-3x"></i>
                    <b class="ms-3 ml-2">Proses</b>
                </span>
                @php
                if ($data->status_proses == 'selesai') {
                $selesai = 'bg-success';
                } else {
                $selesai = '';
                }
                @endphp
                <span class="w-25 w-md-25 border border-dark {{ $selesai }} p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-3 fa-3x"></i>
                    <b class="ms-3 ml-2">Selesai</b>
                </span>
            </div>
            <div class="card-body small">
                <div class="d-flex">
                    <div class="w-50 text-left">
                        <label>Detail Naskah</label>
                    </div>
                    <div class="w-50 text-right text-secondary">
                        #{{ Carbon\Carbon::parse($data->created_at)->format('dmyHis').$data->id_pengajuan }}-{{ $data->id_usulan }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <div class="input-group">
                            <label class="w-25">Tanggal {{ $data->kategori }}</label>
                            <span class="w-75">: {{ $data->tanggal_usulan }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Jenis Pengajuan</label>
                            <span class="w-75">: {{ $data->form->nama_form }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Nama Pegawai</label>
                            <span class="w-75">: {{ $data->user->pegawai->nama_pegawai }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Jabatan</label>
                            <span class="w-75">: {{ ucwords(strtolower($data->user->pegawai->jabatan->jabatan)) }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Unit Kerja</label>
                            <span class="w-75">:
                                {{ ucwords(strtolower($data->user->pegawai->uker->unit_kerja)) }} |
                                {{ ucwords(strtolower($data->user->pegawai->uker->utama->unit_utama)) }}
                            </span>
                        </div>
                        <div class="input-group">
                            <label class="w-25">Keterangan</label>
                            <span class="w-75">: {{ $data->keterangan }}</span>
                        </div>


                    </div>
                    <div class="col-md-5">
                        @if ($data->tanggal_ambil)
                        <div class="input-group">
                            <label class="w-25">Tanggal Ambil</label>
                            <span class="w-75">: {{ Carbon\Carbon::parse($data->tanggal_ambil)->isoFormat('DD MMMM Y') }}</span>
                        </div>
                        @endif
                        <div class="input-group">
                            <label class="w-25">Nomor Naskah</label>
                            <span class="w-75 text-uppercase">: {{ $data->nomor_usulan }}</span>
                        </div>
                        <div class="input-group">
                            <label class="w-25">Surat</label>
                            <span class="w-75">:
                                <a href="{{ route('usulan.surat', $data->id_usulan) }}" target="_blank">
                                    <u><i class="fas fa-file-alt"></i> Lihat Surat</u>
                                </a>
                            </span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Email</label>
                            <span class="w-75">: {{ $data->user->email }}</span>
                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                            <div class="input-group">
                                <label class="w-25">Hal</label>
                                <span class="w-75 text-justify">: {{ $data->keterangan }}</span>
                            </div>
                        </div> -->
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
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 10%;">Aksi</th>
                                <th>Barang</th>
                                <th>Deskripsi</th>
                                <th style="width: 15%;">Jumlah</th>
                                <th style="width: 15%;">Stok Gudang</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach($data->usulanSnc as $index => $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="#" class="btn btn-default btn-xs bg-primary rounded" data-toggle="modal" data-target="#editItem-{{ $row->id_usulan_snc }}">
                                        <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                    </a>
                                    <a href="#" class="btn btn-default btn-xs bg-danger rounded" onclick="confirmRemove(event, `{{ route('snaco.item.delete', $row->id_usulan_snc) }}`)">
                                        <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                    </a>

                                    <div class="modal fade text-left" id="editItem-{{ $row->id_usulan_snc }}" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modal title</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="form-edit-{{ $row->id_usulan_snc }}" action="{{ route('snaco.item.update') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="usulan_id" value="{{ $data->id_usulan }}">
                                                    <input type="hidden" name="id_usulan_snc" value="{{ $row->id_usulan_snc }}">
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
                                                            <input type="number" class="form-control text-center w-50 ml-1" name="jumlah" value="{{ $row->jumlah_permintaan }}" min="1" required>
                                                            <input id="satuan" type="text" class="form-control text-center w-25 ml-2" placeholder="satuan" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'form-edit-{{ $row->id_usulan_snc }}')">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-left">{{ $row->snc->kategori->nama_kategori }}</td>
                                <td>{{ $row->snc->snc_deskripsi }}</td>
                                <td>{{ $row->jumlah_permintaan }} {{ $row->snc->satuan->satuan }}</td>
                                <td>{{ $row->snc->stok() }} {{ $row->snc->satuan->satuan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Modal Tambah -->
<div class="modal fade" id="tambahItem" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-sm">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add" action="{{ route('snaco.item.store') }}" method="POST">
                @csrf
                <input type="hidden" name="usulan_id" value="{{ $data->id_usulan }}">
                <div class="modal-body">
                    <div class="d-flex">
                        <label class="w-25 col-form-label">Pilih Barang</label>
                        <select name="" id="kategori-add" class="w-75 form-control kategori" style="width: 75%;" required>
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
                        <select name="id_snc" id="barang-add" class="w-75 form-control barang" style="width: 75%;" required>
                            <option value="{{ $row->snc_id }}">
                                {{ $row->snc->snc_nama }} {{ $row->snc->snc_deskripsi }}
                            </option>
                        </select>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Jumlah</label>
                        <input type="number" class="form-control text-center w-50" name="jumlah" value="1" min="1" required>
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

    function confirmTrue(event) {
        event.preventDefault();

        const form = document.getElementById('form-submit');

        Swal.fire({
            title: 'Konfirmasi Penyerahan',
            text: 'Apakah Anda yakin selesai menyerahkan permintaan ini?',
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'Pilih Tanggal Penyerahan',
            inputAttributes: {
                'aria-label': 'Pilih tanggal penyerahan di sini',
                'readonly': true
            },
            customClass: {
                input: 'text-center'
            },
            showCancelButton: true,
            confirmButtonText: 'Proses',
            cancelButtonText: 'Batal',
            didOpen: () => {
                flatpickr(Swal.getInput(), {
                    dateFormat: "Y-m-d"
                });
            },
        }).then((result) => {
            const selectedDate = result.value;
            if (result.isConfirmed && selectedDate) {
                Swal.fire({
                    title: 'Penyerahan',
                    text: 'Usulan telah diserahkan pada tanggal: ' + selectedDate,
                    icon: 'success'
                });

                document.getElementById('tanggal_bast').value = selectedDate;
                form.submit();
            } else {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Belum memilih tanggal ',
                    icon: 'error'
                });
            }
        });
    }

    function confirmOtp(event) {
        event.preventDefault();

        const otp3 = `{{ $data->otp_3 }}`
        const form = document.getElementById('form-submit');

        Swal.fire({
            title: 'Konfirmasi Pengambilan',
            text: 'Masukkan Kode OTP yang diterima oleh Unit Kerja',
            icon: 'warning',
            html: `
                <input type="number" id="input1" class="swal2-input w-75 border border-dark text-center" placeholder="Kode OTP" value="${otp3}">
                <input type="date" id="input2" class="swal2-input w-75 border border-dark text-center" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="Enter second value">
                <input type="text" id="input3" class="swal2-input w-75 border border-dark text-center" placeholder="Nama Penerima">
            `,
            customClass: {
                input: 'text-center',

            },
            preConfirm: () => {
                const otp = document.getElementById('input1').value;
                const tanggal = document.getElementById('input2').value;
                const penerima = document.getElementById('input3').value;

                // Jika belum ada tanggal yang dipilih, set default text
                if (!tanggal) {
                    document.getElementById('input2').setAttribute('placeholder', 'Tanggal belum dipilih');
                }

                if (!otp || !tanggal || !penerima) {
                    Swal.showValidationMessage('Harap isi semua input!');
                    return false;
                }

                return {
                    otp: otp,
                    tanggal: tanggal,
                    penerima: penerima
                };
            },
            showCancelButton: true,
            confirmButtonText: 'Proses',
            cancelButtonText: 'Batal'
        }).then((result) => {
            const otp_old = `{{ $data->otp_3 }}`
            const otp = result.value.otp;
            const selectedDate = result.value.tanggal;
            const penerima = result.value.penerima;

            if (result.isConfirmed) {
                if (otp_old != otp) {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'OTP Tidak Sesuai ',
                        icon: 'error'
                    });
                } else if (!selectedDate) {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Belum memilih tanggal ',
                        icon: 'error'
                    });
                } else {
                    Swal.fire({
                        title: 'Penyerahan',
                        text: 'Usulan telah diserahkan pada tanggal: ' + selectedDate,
                        icon: 'success'
                    });

                    document.getElementById('otp').value = otp;
                    document.getElementById('tanggal_bast').value = selectedDate;
                    document.getElementById('penerima').value = penerima;
                    form.submit();
                }
            }
        });
    }
</script>
@endsection

@endsection
