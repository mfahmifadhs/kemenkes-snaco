@extends('layout.app')

@section('content')


<div class="content-header">
    <div class="container col-md-9">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('snaco.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usulan.show', 'snc') }}">Daftar</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container col-md-9">
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">
                    Permintaan Snack Corner
                </label>
                <div class="card-tools">
                    @if ((!$data->status_persetujuan) || ($data->status_persetujuan && in_array(Auth::user()->id, [1, 2])))
                    <a href="{{ route('usulan.edit', $data->id_usulan) }}" class="badge badge-warning mt-2 p-2 border border-dark">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <a href="#" class="badge badge-danger mt-2 p-2 border border-dark" onclick="confirmLink(event, `{{ route('usulan.delete', $data->id_usulan) }}`)">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </a>
                    @endif

                    @if ($data->status_persetujuan == 'true')
                    <!-- @if ($data->status_proses != 'selesai')
                    <a href="#" class="btn btn-primary border-dark btn-xs mt-0 p-1" onclick="confirmLink(event, `{{ route('usulan.resendToken', $data->id_usulan) }}`)">
                        <i class="fas fa-paper-plane"></i> Resend Token
                    </a>
                    @endif -->
                    <span class="badge badge-success mt-2 p-2 border border-dark">
                        <i class="fas fa-check-circle"></i> Permintaan Diterima
                    </span>
                    @endif

                    @if ($data->status_persetujuan == 'false')
                    <span class="badge badge-danger mt-2 p-2 border border-dark">
                        <i class="fas fa-times-circle"></i> Permintaan Ditolak
                    </span>
                    @endif
                </div>
            </div>
            <div class="card-header d-flex text-center flex-wrap justify-content-center">
                @php
                if (!$data->status_proses && !$data->status_persetujuan) {
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

                if ($data->status_persetujuan == 'true' && $data->status_proses == 'proses') {
                $proses = 'bg-warning';
                } else if ($data->status_proses == 'selesai') {
                $proses = 'bg-success';
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
                        {{ $data->kode_usulan }}#{{ Carbon\Carbon::parse($data->created_at)->format('dmyHis').$data->id_pengajuan }}-{{ $data->id_usulan }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
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

                        @if ($data->status_persetujuan == 'false')
                        <div class="input-group">
                            <label class="w-25">Alasan Ditolak</label>
                            <span class="w-75 text-danger">: {{ $data->keterangan_tolak }}</span>
                        </div>
                        @endif

                        @if ($data->status_persetujuan == 'true' && $data->keterangan_tolak && Auth::user()->role_id != 4)
                        <div class="input-group">
                            <label class="w-25">Catatan</label>
                            <span class="w-75">: {{ $data->keterangan_tolak }}</span>
                        </div>
                        @endif


                    </div>
                    <div class="col-md-6">
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
                            <label class="w-25">Surat Pengajuan</label>
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

                        @if ($data->nama_penerima)
                        <div class="input-group">
                            <label class="w-25">Penerima</label>
                            <span class="w-75">: {{ $data->nama_penerima }}</span>
                        </div>
                        @endif

                        <div class="input-group">
                            <label class="w-100 text-secondary my-2">Data Pendukung</label>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Surat</label>
                            <span class="w-75">:
                                <a href="{{ route('usulan.lihat-surat', $data->id_usulan) }}" target="_blank">
                                    <i class="fas fa-file-pdf"></i> <u>Lihat Surat</u>
                                </a>
                            </span>
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
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($data->usulanSnc as $row)
                            <tr class="bg-white">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row->snc->snc_nama }}</td>
                                <td>{{ $row->snc->snc_deskripsi }}</td>
                                <td class="text-center">{{ $row->jumlah_permintaan.' '.$row->snc->satuan->satuan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
    });
</script>
@endif
@endsection

@endsection
