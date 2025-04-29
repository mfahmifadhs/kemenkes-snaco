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
                            <label class="w-25">Total Permintaan</label>
                            @if (Auth::user()->role_id != 4)
                            <span class="w-75">: {{ number_format($data->stokMasuk->sum('jumlah'), 0, '.') }} {{ $data->satuan->satuan }}</span>
                            @else
                            <span class="w-75">: {{ number_format($data->stokMasukUker->sum('jumlah_permintaan'), 0, '.') }} {{ $data->satuan->satuan }}</span>
                            @endif
                        </div>
                        <div class="input-group">
                            <label class="w-25">Total Pemakaian</label>
                            @if (Auth::user()->role_id != 4)
                            <span class="w-75">: {{ number_format($data->stokKeluar->sum('jumlah_permintaan'), 0, '.') }} {{ $data->satuan->satuan }}</span>
                            @else
                            <span class="w-75">: {{ number_format($data->stokKeluarUker->sum('jumlah'), 0, '.') }} {{ $data->satuan->satuan }}</span>
                            @endif
                        </div>

                        <div class="input-group">
                            <label class="w-25">Sisa Stok</label>
                            @if (Auth::user()->role_id != 4)
                            <span class="w-75">: {{ number_format($data->stok(), 0, '.') }} {{ $data->satuan->satuan }}</span>
                            @else
                            <span class="w-75">: {{ number_format($data->stokMasukUker->sum('jumlah_permintaan') - $data->stokKeluarUker->sum('jumlah'), 0, '.') }} {{ $data->satuan->satuan }}</span>
                            @endif
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
                        <img src="{{ asset('dist/img/foto_snaco/' . $data->snc_foto) }}" class="img-fluid" alt="">
                        @else
                        <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->role_id == 4)
        <div class="row">
            <div class="col-md-6">
                <div class="card border border-dark">
                    <div class="card-body">
                        <div class="float-left">
                            <label class="text-sm">Permintaan (Stok Awal)</label>
                        </div>
                        <div class="float-right">
                            <label class="text-sm">
                                Total {{ $data->stokMasukUker->sum('jumlah_permintaan').' '.$data->satuan->satuan }}
                            </label>
                        </div>
                        <div class="table-responsive">
                            <table id="tMasuk" class="table table-bordered border border-dark small">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 0%;">No</th>
                                        <th style="width: 22%;">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th style="width: 15%;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($data->stokMasukUker as $row)
                                    <tr onclick="window.location.href=`{{ route('snaco.detail', $row->usulan_id) }}`" style="cursor:pointer;">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ Carbon\Carbon::parse($row->usulan->tanggal_usulan)->isoFormat('HH:mm DD MMM Y') }}</td>
                                        <td class="text-left">{{ $row->usulan->keterangan }}</td>
                                        <td>{{ $row->jumlah_permintaan.' '.$row->snc->satuan->satuan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border border-dark">
                    <div class="card-body">
                        <div class="float-left">
                            <label class="text-sm">Pemakaian</label>
                        </div>
                        <div class="float-right">
                            <label class="text-sm">
                                Total {{ $data->stokKeluarUker->sum('jumlah').' '.$data->satuan->satuan }}
                            </label>
                        </div>
                        <div class="table-responsive">
                            <table id="tKeluar" class="table table-bordered border border-dark small">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 0%;">No</th>
                                        <th style="width: 22%;">Tanggal</th>
                                        <th>Kegiatan</th>
                                        <th style="width: 15%;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($data->stokKeluarUker as $row)
                                    <tr onclick="window.location.href=`{{ route('kegiatan.detail', $row->kegiatan_id) }}`" style="cursor:pointer;">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ Carbon\Carbon::parse($row->kegiatan->tanggal_kegiatan)->isoFormat('DD MMM Y') }}</td>
                                        <td class="text-left">{{ $row->kegiatan->nama_kegiatan }}</td>
                                        <td>{{ $row->jumlah.' '.$row->snc->satuan->satuan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
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

<script>
    $("#tMasuk").DataTable({
        "responsive": false,
        "lengthChange": true,
        "autoWidth": false,
        "info": true,
        "paging": true,
        "searching": true,
        buttons: [{
            extend: 'excel',
            text: ' Excel',
            className: 'bg-success',
            title: 'show'
        }].filter(Boolean),
        "bDestroy": true
    }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
</script>

<script>
    $("#tKeluar").DataTable({
        "responsive": false,
        "lengthChange": true,
        "autoWidth": false,
        "info": true,
        "paging": true,
        "searching": true,
        buttons: [{
            extend: 'excel',
            text: ' Excel',
            className: 'bg-success',
            title: 'show'
        }].filter(Boolean),
        "bDestroy": true
    }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
</script>
@endsection

@endsection
