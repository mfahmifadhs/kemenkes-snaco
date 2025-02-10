@extends('layout.app')

@section('content')


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Barang</h1>
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
    <div class="container-fluid">
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">
                    Detail Barang
                </label>
            </div>
            <div class="card-body">
                <div class="row text-sm">
                    <div class="col-md-5">
                        <label class="text-secondary text-sm"><i>Informasi Barang</i></label>
                        <div class="input-group">
                            <label class="w-25">Jenis Barang</label>
                            <span class="w-75">: {{ $data->kategori->nama_kategori }}</span>
                        </div>
                        <div class="input-group">
                            <label class="w-25">Nama Barang</label>
                            <span class="w-75">: {{ $data->snc_nama }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Deskripsi</label>
                            <span class="w-75">: {{ $data->snc_nama }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Harga Satuan</label>
                            <span class="w-75">: Rp {{ number_format($data->snc_harga, 0, '.') }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Satuan</label>
                            <span class="w-75">: {{ $data->satuan->satuan }}</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="text-secondary text-sm"><i>Informasi Ketersediaan</i></label>
                        <div class="input-group">
                            <label class="w-25">Stok Awal</label>
                            <span class="w-75">: {{ number_format($data->stokMasuk()->sum('jumlah'), 0, '.') }} {{ $data->satuan->satuan }}</span>
                        </div>
                        <div class="input-group">
                            <label class="w-25">Total Permintaan</label>
                            <span class="w-75">: {{ number_format($data->stokKeluar()->sum('jumlah_permintaan'), 0, '.') }} {{ $data->satuan->satuan }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Sisa Stok</label>
                            <span class="w-75">: {{ number_format($data->stok(), 0, '.') }} {{ $data->satuan->satuan }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Status</label>
                            <span class="w-75">:
                                @if ($data->stok() == 0)
                                <span class="badge badge-danger">Tidak Tersedia</span>
                                @else
                                <span class="badge badge-success">Tersedia</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2 border border-dark">
                        @if ($data->snc_foto)
                            <img src="{{ asset('storage/file/foto_snaco/' . $data->snc_foto) }}" class="img-fluid" alt="">
                        @else
                            <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card border border-dark">
                    <div class="card-body">
                        <label class="text-secondary text-sm"><i>Pembelian</i></label>
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

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border border-dark">
                    <div class="card-body">
                        <label class="text-secondary text-sm"><i>Permintaan</i></label>
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

                                </tbody>
                            </table>
                        </div>
                    </div>
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
