@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Laporan</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('snaco.dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>

            </div>
            <div class="col-sm-6 text-right">
                <!-- <a href="{{ url('unit-kerja/ukt/usulan/pekerjaan/baru') }}" class="btn btn-primary btn-md font-weight-bold mt-3">
                    <i class="fas fa-plus-square"></i> PENGAJUAN
                </a> -->
            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card border border-dark">
                    <div class="card-body">
                        <label class="small">Tabel Permintaan dan Pemakaian Snack Corner</label>
                        <div class="table-responsive">
                            <table class="table table-bordered text-xs">
                                <thead>
                                    <tr class="text-center">
                                        <th class="align-middle">No</th>
                                        <th class="align-middle">Barang</th>
                                        <th class="align-middle">Stok Gudang</th>
                                        @foreach ($uker as $row)
                                        <th class="align-middle">{{ ucwords(strtolower($row->singkatan)) }}</th>
                                        @endforeach
                                        <th class="align-middle">Sisa Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($snaco as $row)
                                    @php
                                    // Total stok masuk
                                    $stokMasuk = $row->stokMasuk()->sum('jumlah');

                                    // Hitung total pemakaian dari semua unit kerja
                                    $totalPemakaian = 0;
                                    @endphp

                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $row->snc_nama }}</td>
                                        <td class="text-center bg-success font-weight-bold">{{ number_format($stokMasuk, 0, ',', '.') }}</td>

                                        @foreach ($uker as $unit)
                                        @php
                                        $pemakaian = $row->stokPermintaan()
                                        ->where('uker_id', $unit->id_unit_kerja)
                                        ->sum('jumlah_permintaan');

                                        $totalPemakaian += $pemakaian;
                                        @endphp
                                        <td class="text-center">{{ number_format($pemakaian, 0, ',', '.') }}</td>
                                        @endforeach

                                        @php
                                        $sisaStok = $stokMasuk - $totalPemakaian;
                                        @endphp

                                        <td class="text-center {{ $sisaStok <= 0 ? 'bg-danger' : 'bg-warning' }} font-weight-bold">
                                            {{ number_format($sisaStok, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
