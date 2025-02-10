@extends('layout.app')

@section('content')

<!-- Main content -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="font-weight-bold mb-4 text-capitalize">
                            Selamat Datang, {{ ucfirst(strtolower(Auth::user()->pegawai->nama_pegawai)) }}
                        </h4>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-widget widget-user-2 border border-dark">
                            <div class="bg-primary p-3">
                                <h6 class="my-auto">Usulan Permintaan</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="nav flex-column font-weight-bold">
                                    <li class="nav-item">
                                        <form action="{{ route('usulan.show', 'snc') }}" method="GET">
                                            @csrf
                                            <input type="hidden" name="proses" value="verif">
                                            <button type="submit" class="nav-link btn btn-link py-2 font-weight-bold text-left btn-block">
                                                <span class="float-left">
                                                    <i class="fas fa-file-signature"></i> Persetujuan
                                                </span>
                                                <span class="float-right">
                                                    {{ $usulan->whereNull('status_persetujuan')->count() }} usulan
                                                </span>
                                            </button>
                                        </form>
                                    </li>
                                    <li class="nav-item">
                                        <form action="{{ route('usulan.show', 'snc') }}" method="GET">
                                            @csrf
                                            <input type="hidden" name="proses" value="proses">
                                            <button type="submit" class="nav-link btn btn-link py-2 font-weight-bold text-left btn-block">
                                                <span class="float-left">
                                                    <i class="fas fa-clock"></i> Proses
                                                </span>
                                                <span class="float-right">
                                                    {{ $usulan->where('status_proses', 'proses')->count() }} usulan
                                                </span>
                                            </button>
                                        </form>
                                    </li>
                                    <li class="nav-item">
                                        <form action="{{ route('usulan.show', 'snc') }}" method="GET">
                                            @csrf
                                            <input type="hidden" name="proses" value="selesai">
                                            <button type="submit" class="nav-link btn btn-link py-2 font-weight-bold text-left btn-block">
                                                <span class="float-left">
                                                    <i class="fas fa-check-circle"></i> Selesai
                                                </span>
                                                <span class="float-right">
                                                    {{ $usulan->where('status_proses', 'selesai')->count() }} usulan
                                                </span>
                                            </button>
                                        </form>
                                    </li>
                                    <li class="nav-item">
                                        <form action="{{ route('usulan.show', 'snc') }}" method="GET">
                                            @csrf
                                            <input type="hidden" name="proses" value="false">
                                            <button type="submit" class="nav-link btn btn-link py-2 font-weight-bold text-left btn-block">
                                                <span class="float-left">
                                                    <i class="fas fa-times-circle"></i> Ditolak
                                                </span>
                                                <span class="float-right">
                                                    {{ $usulan->where('status_persetujuan', 'false')->count() }} usulan
                                                </span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border border-dark" style="height: 40%;">
                            <div class="card-body text-center">
                                <canvas id="stokChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-box"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Jenis Barang</span>
                                        <span class="info-box-number">
                                            {{ $kategori->count() }}
                                            <small>barang</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-file-signature"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Usulan</span>
                                        <span class="info-box-number">
                                            {{ $usulan->where('status_proses', 'selesai')->count() }}
                                            <small>usulan</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-box"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Barang</span>
                                        <span class="info-box-number">
                                            {{ $snaco->count() }}
                                            <small>barang</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border border-dark">
                            <div class="card-body">
                                <label class="text-sm">Stok</label>
                                <div class="table-responsive">
                                    <table id="table-data" class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <th>No</th>
                                                <th>Barang</th>
                                                <th>Deskripsi</th>
                                                <th>Stok Awal</th>
                                                <th>Permintaan</th>
                                                <th>Stok</th>
                                                <th>Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs">
                                            @foreach($stok as $row)
                                            <tr class="bg-white">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $row->kategori->nama_kategori }}</td>
                                                <td>{{ $row->snc_nama }} {{ $row->snc_deskripsi }}</td>
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

                        <div class="card border border-dark">
                            <div class="card-body p-2">
                                <form action="{{ route('snaco.show') }}" method="GET">
                                    @csrf
                                    <div class="row p-1">
                                        @foreach($kategori as $row)
                                        <button class="col-md-2 col-3 my-2 text-center text-dark bg-white border border-white"
                                            id="kategori-{{ $row->id_kategori }}"
                                            name="kategori"
                                            value="{{ $row->id_kategori }}">

                                            <i class="{{ $row->icon }} fa-2x"></i>
                                            <h6 class="mt-2 small">{{ $row->nama_kategori }}</h6>
                                            @if ($row->stok($row->id_kategori) == 0)
                                            <span class="badge badge-danger p-1 w-100"><i class="fas fa-times-circle"></i> <span class="text-xs">Tidak Tersedia</span></span>
                                            @else
                                            <span class="badge badge-success p-1 w-100"><i class="fas fa-check-circle"></i> <span class="text-xs">Tersedia</span></span>
                                            @endif

                                        </button>
                                        @endforeach
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('stokChart').getContext('2d');

        // Ambil data dari Laravel (pastikan tidak kosong)
        var stokMasuk = Number("{{ $stokMasuk ?? 0 }}");
        var stokKeluar = Number("{{ $stokKeluar ?? 0 }}");

        // Pastikan plugin datalabels terdaftar
        Chart.plugins.register(ChartDataLabels);

        var stokChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Barang Masuk', 'Barang Keluar'],
                datasets: [{
                    data: [stokMasuk, stokKeluar], // Data angka
                    backgroundColor: ['#4CAF50', '#FF5733']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom'
                },
                plugins: {
                    datalabels: {
                        color: '#fff', // Warna teks angka
                        anchor: 'center',
                        align: 'center',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            return value.toLocaleString(); // Format angka dengan pemisah ribuan
                        }
                    }
                }
            }
        });
    });
</script>

<script>
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()


        $("#table-data").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": true,
            "paging": true,
            "searching": true,
            buttons: [{
                extend: 'pdf',
                text: ' PDF',
                pageSize: 'A4',
                className: 'bg-danger btn-sm',
                title: 'show'
            }, {
                extend: 'excel',
                text: ' Excel',
                className: 'bg-success btn-sm',
                title: 'show'
            }],
            "bDestroy": true
        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
    })
</script>
@endsection
@endsection
