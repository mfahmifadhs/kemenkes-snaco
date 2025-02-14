@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Usulan</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('snaco.dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar</li>
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
                @if ($message = Session::get('success'))
                <div id="alert" class="alert alert-success">
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert').style.display = 'none';
                    }, 5000);
                </script>
                @endif

                @if ($message = Session::get('failed'))
                <div id="alert" class="alert alert-danger">
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert').style.display = 'none';
                    }, 5000);
                </script>
                @endif
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header border border-dark">
                        <label class="card-title">
                            Daftar Usulan
                        </label>
                        <div class="card-tools">
                            <a href="" class="btn btn-default btn-sm text-dark" data-toggle="modal" data-target="#modalFilter">
                                <i class="fas fa-filter"></i> Filter
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table-data" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th style="width: 13%;">Tanggal</th>
                                        <th>Kode</th>
                                        <th>Unit Kerja</th>
                                        <th style="width: 13%;">No. Surat</th>
                                        <th style="width: 13%;">Total Barang</th>
                                        <th>Hal</th>
                                        <th class="d-none">Detail</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($usulan == 0)
                                    <tr class="text-center">
                                        <td colspan="9">Tidak ada data</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="9">Sedang Mengambil data ...</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-filter"></i> Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="GET" action="{{ route('usulan.show', $form) }}">
                @csrf
                <div class="modal-body">
                    @if (Auth::user()->role_id != 4)
                    <div class="form-group text-sm">
                        <label>Pilih Unit Kerja</label>
                        <select name="uker" class="form-control form-control-sm border-dark rounded">
                            <option value="">Seluruh Unit Kerja</option>
                            @foreach($ukerList as $row)
                            <option value="{{ $row->id_unit_kerja }}" <?php echo $uker == $row->id_unit_kerja ? 'selected' : '' ?>>
                                {{ $row->unit_kerja }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="text-sm">Pilih Tanggal</label>
                        <select name="tanggal" class="form-control form-control-sm border-dark rounded">
                            <option value="">Semua Tanggal</option>
                            @foreach(range(1, 31) as $dateNumber)
                            @php $rowTgl = Carbon\Carbon::create()->day($dateNumber)->isoFormat('DD'); @endphp
                            <option value="{{ $rowTgl }}" <?php echo $tanggal == $rowTgl ? 'selected' : '' ?>>
                                {{ $rowTgl }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-sm">Pilih Bulan</label>
                        <select name="bulan" class="form-control form-control-sm border-dark rounded">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $monthNumber)
                            @php $rowBulan = Carbon\Carbon::create()->month($monthNumber); @endphp
                            <option value="{{ $rowBulan->isoFormat('MM') }}" <?php echo $bulan == $rowBulan->isoFormat('M') ? 'selected' : '' ?>>
                                {{ $rowBulan->isoFormat('MMMM') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-sm">Pilih Tahun</label>
                        <select name="tahun" class="form-control form-control-sm border-dark rounded">
                            <option value="">Semua Tahun</option>
                            @foreach(range(2025,2026) as $yearNumber)
                            @php $rowTahun = Carbon\Carbon::create()->year($yearNumber); @endphp
                            <option value="{{ $rowTahun->isoFormat('Y') }}" <?php echo $tahun == $rowTahun->isoFormat('Y') ? 'selected' : '' ?>>
                                {{ $rowTahun->isoFormat('Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-sm">Pilih Status</label>
                        <select name="proses" class="form-control form-control-sm text-capitalize border-dark">
                            <option value="">Seluruh Status Proses</option>
                            <option value="verif" <?php echo $proses == 'verif' ? 'selected' : ''; ?>>Persetujuan</option>
                            <option value="proses" <?php echo $proses == 'proses' ? 'selected' : ''; ?>>Proses</option>
                            <option value="selesai" <?php echo $proses == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                            <option value="false" <?php echo $proses == 'false' ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('usulan.show', $form) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-undo"></i> Muat
                    </a>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elements = document.querySelectorAll('.hide-text');

        elements.forEach(function(element) {
            var maxHeight = parseFloat(window.getComputedStyle(element).getPropertyValue('max-height'));

            if (element.scrollHeight > maxHeight) {
                var showMoreLink = document.createElement('a');
                showMoreLink.className = 'show-more';
                showMoreLink.textContent = 'Show More';

                var hideText = function() {
                    element.style.maxHeight = maxHeight + 'px';
                    showMoreLink.style.display = 'block';
                    showMoreLink.textContent = 'Tampilkan lebih banyak';
                };

                var showMore = function() {
                    element.style.maxHeight = 'none';
                    showMoreLink.style.display = 'block';
                    showMoreLink.textContent = 'Tampilkan lebih sedikit';
                };

                showMoreLink.addEventListener('click', function() {
                    if (element.style.maxHeight === 'none') {
                        hideText();
                    } else {
                        showMore();
                    }
                });

                element.insertAdjacentElement('afterend', showMoreLink);
                hideText();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        let tanggal = $('[name="tanggal"]').val();
        let bulan = $('[name="bulan"]').val();
        let tahun = $('[name="tahun"]').val();
        let status = $('[name="status"]').val();
        let proses = $('[name="proses"]').val();
        let kategori = $('[name="kategori"]').val();
        let uker     = $('[name="uker"]').val();

        // Muat tabel saat halaman pertama kali dimuat
        loadTable(tanggal, bulan, tahun, status, proses, kategori, uker);

        function loadTable(tanggal, bulan, tahun, status, proses, kategori, uker) {
            $.ajax({
                url: `{{ route($form . '.show') }}`,
                method: 'GET',
                data: {
                    tanggal: tanggal,
                    bulan: bulan,
                    tahun: tahun,
                    status: status,
                    proses: proses,
                    kategori: kategori,
                    uker: uker
                },
                dataType: 'json',
                success: function(response) {
                    let tbody = $('.table tbody');
                    tbody.empty(); // Mengosongkan tbody sebelum menambahkan data

                    if (response.message) {
                        // Jika ada pesan dalam respons (misalnya "No data available")
                        tbody.append(`
                        <tr>
                            <td colspan="9">${response.message}</td>
                        </tr>
                    `);
                    } else {
                        // Jika ada data
                        $.each(response, function(index, item) {
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no}</td>
                                    <td class="align-middle">${item.aksi}</td>
                                    <td class="align-middle">${item.tanggal}</td>
                                    <td class="align-middle">${item.kode}</td>
                                    <td class="align-middle text-left">${item.uker}</td>
                                    <td class="align-middle">${item.nosurat}</td>
                                    <td class="align-middle">${item.totalItem} barang</td>
                                    <td class="align-middle text-left">${item.hal}</td>
                                    <td class="align-middle text-left d-none">${item.deskripsi}</td>
                                    <td class="align-middle">${item.status}</td>
                                </tr>
                            `);
                        });

                        $("#table-data").DataTable({
                            "responsive": false,
                            "lengthChange": true,
                            "autoWidth": false,
                            "info": true,
                            "paging": true,
                            "searching": true,
                            buttons: [{
                                extend: 'pdf',
                                text: ' PDF',
                                pageSize: 'A4',
                                className: 'bg-danger',
                                title: 'usulan',
                                exportOptions: {
                                    columns: [2, 3, 4, 5, 6, 7]
                                },
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'usulan',
                                exportOptions: {
                                    columns: ':not(:nth-child(2))'
                                },
                            }],
                            "bDestroy": true
                        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    });
</script>

@endsection
@endsection
