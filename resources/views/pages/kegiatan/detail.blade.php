@extends('layout.app')

@section('content')


<div class="content-header">
    <div class="container col-md-9">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Kegiatan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kegiatan.show') }}">Daftar</a></li>
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
                    Detail Kegiatan
                </label>
                <div class="card-tools">
                    <a href="{{ route('kegiatan.edit', $kegiatan->id_kegiatan) }}" class="btn btn-warning border-dark btn-sm mt-1" onclick="confirmTrue(event)">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body text-capitalize">
                <div class="d-flex">
                    <div class="w-50 text-left">
                        <label class="text-secondary text-sm"><i>Informasi Kegiatan</i></label>
                    </div>
                    <div class="w-50 text-right text-secondary">
                        #{{ Carbon\Carbon::parse($kegiatan->created_at)->format('dmyHis').$kegiatan->id_kegiatan }}-{{ $kegiatan->id_kegiatan }}
                    </div>
                </div>
                <div class="row text-sm">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="w-25">Tanggal Kegiatan</label>
                            <span class="w-75">: {{ Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->isoFormat('DD MMMM Y') }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Nama Kegiatan</label>
                            <span class="w-75">: {{ $kegiatan->nama_kegiatan }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Jumlah Peserta</label>
                            <span class="w-75">: {{ $kegiatan->jumlah_peserta }} orang</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Jumlah Barang</label>
                            <span class="w-75">: {{ $kegiatan->detail->count() }} barang</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Keterangan</label>
                            <span class="w-75">: {{ $kegiatan->keterangan }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <label class="text-secondary text-sm"><i>Informasi Barang</i></label>
                <div class="table-responsive">
                    <table id="table" class="table table-bordered border border-dark">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Barang</th>
                                <th>Deskripsi</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kegiatan->detail as $row)
                            <tr class="bg-white">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row->snc->kategori->nama_kategori }} {{ $row->snc->snc_nama }}</td>
                                <td>{{ $row->snc->snc_deskripsi }}</td>
                                <td class="text-center">{{ number_format($row->jumlah, 0, '.') }}</td>
                                <td class="text-center">{{ $row->snc->satuan->satuan }}</td>
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

<script>
    function confirm(event, url) {
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
</script>
@endsection

@endsection
