@extends('layout.app')

@section('content')


<div class="content-header">
    <div class="container col-md-9">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Stok</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('snaco.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('snaco.stok.show') }}">Daftar</a></li>
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
                    Detail Stok
                </label>
            </div>
            <div class="card-body small text-capitalize">
                <div class="d-flex">
                    <div class="w-50 text-left">
                        <label class="text-secondary">Detail Naskah</label>
                    </div>
                    <div class="w-50 text-right text-secondary">
                        #{{ Carbon\Carbon::parse($stok->created_at)->format('dmyHis').$stok->id_stok }}-{{ $stok->id_stok }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="w-25">Tanggal Masuk</label>
                            <span class="w-75">: {{ Carbon\Carbon::parse($stok->tanggal_masuk)->isoFormat('DD MMMM Y') }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Nomor Kwitansi</label>
                            <span class="w-75">: {{ $stok->no_kwitansi }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Total Barang</label>
                            <span class="w-75">: {{ $stok->detail->count() }} barang</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Total Harga</label>
                            <span class="w-75">: Rp {{ number_format($stok->total_harga, 0, '.') }}</span>
                        </div>

                        <div class="input-group">
                            <label class="w-25">Keterangan</label>
                            <span class="w-75">: {{ $stok->keterangan }}</span>
                        </div>
                    </div>
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
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($stok->detail as $row)
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
