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
            <div class="col-md-12 form-group">
                <form action="{{ route('snaco.report') }}" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label>Bulan</label>
                            <select name="bulan" class="form-control" id="">
                                <option value="">-- Semua Bulan --</option>
                                @foreach(range(1, 12) as $monthNumber)
                                @php $rowBulan = Carbon\Carbon::create()->month($monthNumber); @endphp
                                <option value="{{ $rowBulan->isoFormat('MM') }}" <?php echo $bulan == $rowBulan->isoFormat('M') ? 'selected' : '' ?>>
                                    {{ $rowBulan->isoFormat('MMMM') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Tahun</label>
                            <select name="tahun" class="form-control" id="">
                                <option value="">-- Semua Tahun --</option>
                                <option value="2025" <?php echo $tahun == '2025' ? 'selected' : ''; ?>>2025</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Cari</button>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <a href="{{ route('snaco.report') }}" type="submit" class="btn btn-danger btn-block">Muat</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8 form-group">
                <div class="card border border-dark h-100">
                    <div class="card-header">
                        <label class="card-title text-sm">
                            Grafik laporan
                        </label>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="chart">
                                <canvas id="reportChart" height="400px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border border-dark">
                    <div class="card-body">
                        <label>Usulan Unit Kerja</label>
                        @foreach($uker as $row)
                        <div class="d-flex text-xs border-bottom pt-2">
                            <label class="w-75">{{ $loop->iteration }}. {{ $row->unit_kerja }}</label>
                            <label class="w-25 text-right {{ $row->usulan($row->id_unit_kerja) ? 'text-danger' : '' }}">
                                {{ $row->usulan($row->id_unit_kerja) }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card border border-dark">
                    <div class="card-header">
                        <label class="card-title text-sm">
                            Tabel laporan
                        </label>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-report" class="table table-striped small">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Periode</th>
                                        <th>Barang</th>
                                        <th class="text-center">Stok Awal</th>
                                        <th class="text-center">Permintaan</th>
                                        <th class="text-center">Sisa Stok</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <!-- Data akan ditambahkan di sini oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    var surveyUrl = "{{ route('snaco.report.chart', ['bulan' => $bulan, 'tahun' => $tahun]) }}";
    var Survey = [];
    var SurveyTotal = [];

    $(document).ready(function() {
        $.get(surveyUrl, function(result) {
            result.forEach(function(data) {
                Survey.push(data.barang);
                SurveyTotal.push(data.total);
            });

            var maxSurveyTotal = Math.max(...SurveyTotal);
            var doughnutChartCanvas = document.getElementById('reportChart').getContext('2d');
            var doughnutChartData = {
                labels: Survey,
                datasets: [{
                    data: SurveyTotal,
                    borderColor: 'rgb(0, 0, 255)',
                    fill: false,
                    borderWidth: 1,
                }]
            };

            var doughnutChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: maxSurveyTotal + 30
                        }
                    }]
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        boxWidth: 20,
                        fontColor: '#fff',
                        padding: 15,
                        generateLabels: function(chart) {
                            var data = doughnutChartData;
                            return [];
                        }
                    },
                },
                plugins: {
                    datalabels: {
                        color: '#111',
                        textAlign: 'center',
                        font: {
                            lineHeight: 1,
                            fontWeight: 'bold'
                        },
                        anchor: 'end', // Menempatkan label di atas titik
                        align: 'top',
                        offset: 4
                    }
                }
            };

            new Chart(doughnutChartCanvas, {
                type: 'line',
                data: doughnutChartData,
                options: doughnutChartOptions
            });
        });

        $.get(surveyUrl, function(result) {
            if (result.length > 0) {

                // Tambahkan data ke tabel
                var tbody = $('#table-body');
                result.forEach(function(data, index) {
                    let row = `<tr class="bg-white">
                    <td>${index + 1}</td>
                    <td>${data.periode}</td>
                    <td>${data.barang}</td>
                    <td class="text-center">${data.masuk}</td>
                    <td class="text-center">${data.total}</td>
                    <td class="text-center">${data.sisa}</td>
                </tr>`;
                    tbody.append(row);
                });

                // Inisialisasi DataTables setelah data ditambahkan
                $("#table-report").DataTable({
                    "responsive": false,
                    "lengthChange": false,
                    "autoWidth": true,
                    "info": true,
                    "paging": true,
                    "searching": false,
                    buttons: [{
                        extend: 'pdf',
                        text: ' Print PDF',
                        pageSize: 'A4',
                        className: 'bg-danger',
                        title: 'Kehadiran',
                        exportOptions: {
                            columns: [0, 1, 2]
                        },
                    }],
                    "bDestroy": true,
                }).buttons().container().appendTo('#table-report_wrapper .col-md-6:eq(0)');
            } else {
                // Jika tidak ada data, tambahkan pesan ke tbody
                $('#table-body').html('<tr><td colspan="3">No data available in table</td></tr>');
            }
        });
    });
</script>

@endsection
@endsection
