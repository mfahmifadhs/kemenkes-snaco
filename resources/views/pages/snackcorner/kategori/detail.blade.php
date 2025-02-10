@extends('layout.app')

@section('content')


<div class="content-header">
    <div class="container col-md-9">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jenis Barang</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jenis-snaco.show') }}">Daftar</a></li>
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
                    Detail Jenis Barang
                </label>
                <div class="card-tools">
                    <a href="{{ route('jenis-snaco.edit', $data->id_kategori) }}" class="btn btn-warning border-dark btn-sm mt-1" onclick="confirmTrue(event)">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="w-50 text-left">
                        <label class="text-secondary text-sm"><i>Informasi Jenis Barang</i></label>
                    </div>
                </div>
                <div class="row text-sm">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="w-25">Nama Jenis Barang</label>
                            <span class="w-75">: {{ $data->nama_kategori }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Deskripsi</label>
                            <span class="w-75">: {{ $data->deskripsi }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Icon</label>
                            <span class="w-75">: {{ $data->icon }} orang</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Status</label>
                            <span class="w-75">:
                                @if ($data->status == 'true') Tersedia @else Tidak Tesedia @endif
                            </span>
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
                                <th>Stok Awal</th>
                                <th>Permintaan</th>
                                <th>Sisa Stok</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->snc as $row)
                            <tr class="bg-white">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row->kategori->nama_kategori }} {{ $row->snc_nama }}</td>
                                <td>{{ $row->snc_deskripsi }}</td>
                                <td class="text-center">{{ number_format($row->stokMasuk()->sum('jumlah'), 0, '.') }}</td>
                                <td class="text-center">{{ number_format($row->stokKeluar()->sum('jumlah_permintaan'), 0, '.') }}</td>
                                <td class="text-center">{{ number_format($row->stok(), 0, '.') }}</td>
                                <td class="text-center">{{ $row->satuan->satuan }}</td>
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
