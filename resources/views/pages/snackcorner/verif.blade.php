@extends('layout.app')

@section('content')


<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('snaco.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usulan.show', 'snc') }}">Daftar</a></li>
                    <li class="breadcrumb-item active">Verifikasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<secti class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card border border-dark">
                    <div class="card-header">
                        <label class="mt-2">
                            Verifikasi Permintaan Snack Corner
                        </label>
                        <div class="card-tools">
                            <div class="input-group">
                                <div class="form-group mr-1">
                                    <form id="form-true" action="{{ route('usulan.verif', $id) }}" method="GET">
                                        @csrf
                                        <input type="hidden" name="persetujuan" value="true">
                                        <input type="hidden" name="tanggal_ambil" value="true">
                                        <button type="submit" class="btn btn-success border-dark btn-sm mt-2" onclick="confirmTrue(event)">
                                            <i class="fas fa-check-circle"></i> Setuju
                                        </button>
                                    </form>
                                </div>

                                <div class="form-group ml-1">
                                    <form id="form-false" action="{{ route('usulan.verif', $id) }}" method="GET">
                                        @csrf
                                        <input type="hidden" name="persetujuan" value="false">
                                        <input type="hidden" name="alasan_penolakan" id="alasan_penolakan">

                                        <button type="submit" class="btn btn-danger border-dark btn-sm mt-2" onclick="confirmFalse(event)">
                                            <i class="fas fa-times-circle"></i> Tolak
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
                        <!-- <span class="w-25 border border-secondary p-2">
                        <i class="fas fa-clock fa-3x {{ $data->status_persetujuan == 'true' ? 'text-warning' : 'text-dark' }}"></i>
                        <h6 class="text-xs text-uppercase mt-2 {{ $data->status_persetujuan == 'true' ? 'text-warning' : 'text-secondary' }}">
                            Proses {{ $data->kategori }}
                        </h6>
                    </span> -->
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
                                    <label class="w-25">Nomor Naskah</label>
                                    <span class="w-75 text-uppercase">: {{ $data->nomor }}</span>
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
                        <label>Daftar Barang</label>
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered border border-dark">
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Stok Uker</th>
                                        <th>Stok Gudang</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($data->usulanSnc as $row)
                                    <tr class="bg-white">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $row->snc->kategori->nama_kategori }} {{ $row->snc->snc_nama }}</td>
                                        <td>{{ $row->snc->snc_deskripsi }}</td>
                                        <td class="text-center">{{ $row->jumlah_permintaan }}</td>
                                        <td class="text-center">{{ $row->snc->satuan->satuan }}</td>
                                        <td class="text-center">{{ $row->snc->stokUker($data->user->pegawai->uker_id) }}</td>
                                        <td class="text-center">{{ $row->snc->stok() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success small">
                    <div class="inner">
                        <h4>{{ $data->user->kegiatan->where('data_pendukung')->count() }}<small> kegiatan</small></h4>
                        <small>Data pendukung absensi kegiatan lengkap</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="text-center p-2 border border-top">
                        <form action="{{ route('kegiatan.show') }}" method="GET">
                            <input type="hidden" name="uker" value="{{ $data->user->pegawai->uker_id }}">
                            <input type="hidden" name="absen" value="true">
                            <button class="btn btn-default bg-transparent text-white btn-xs btn-block border-transparent">
                                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="small-box bg-danger small">
                    <div class="inner">
                        <h4>{{ $data->user->kegiatan->whereNull('data_pendukung')->count() }}<small> kegiatan</small></h4>
                        <small>Tidak ada data pendukung absensi kegiatan</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="text-center p-2 border border-top">
                        <form action="{{ route('kegiatan.show') }}" method="GET">
                            <input type="hidden" name="uker" value="{{ $data->user->pegawai->uker_id }}">
                            <input type="hidden" name="absen" value="false">
                            <button class="btn btn-default bg-transparent text-white btn-xs btn-block border-transparent">
                                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</secti>

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
    function confirmTrue(event) {
        event.preventDefault();

        const form = document.getElementById('form-true');

        Swal.fire({
            title: 'Setuju',
            text: 'Pilih tanggal pengambilan',
            icon: 'question',
            html: `
                <h6>Tanggal Pengambilan</h6>
                <input type="date" id="tanggal" class="swal2-input ml-0 mt-0 w-100 border border-dark text-center" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="Tanggal Ambil">
            `,
            preConfirm: () => {
                const tanggal = document.getElementById('tanggal').value;

                // Jika belum ada tanggal yang dipilih, set default text
                if (!tanggal) {
                    document.getElementById('tanggal').setAttribute('placeholder', 'Tanggal belum dipilih');
                }

                if (!tanggal) {
                    Swal.showValidationMessage('Harap isi semua input!');
                    return false;
                }

                return {
                    tanggal: tanggal
                };
            },
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            const selectedDate = result.value.tanggal;
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

                document.querySelector('[name="tanggal_ambil"]').value = selectedDate;
                form.submit();
            }
        });
    }

    function confirmFalse(event) {
        event.preventDefault();

        const form = document.getElementById('form-false');

        Swal.fire({
            title: 'Konfirmasi Penolakan',
            text: 'Apakah Anda yakin ingin menolak usulan ini?',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Berikan alasan penolakan di sini...',
            inputAttributes: {
                'aria-label': 'Tulis alasan penolakan di sini'
            },
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan harus diisi!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const alasanPenolakan = result.value;

                Swal.fire({
                    title: 'Ditolak!',
                    text: 'Usulan telah ditolak dengan alasan: ' + alasanPenolakan,
                    icon: 'success'
                });

                document.getElementById('alasan_penolakan').value = alasanPenolakan;
                form.submit();
            }
        });
    }
</script>
@endsection

@endsection
