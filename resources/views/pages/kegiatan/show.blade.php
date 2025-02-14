@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Kegiatan</h4>
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
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header border border-dark">
                        <label class="card-title">
                            Daftar Kegiatan
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
                                        <th style="width: 5%;">Aksi</th>
                                        <th style="width: 12%;">Unit Kerja</th>
                                        <th>Kode</th>
                                        <th style="width: 10%;">Tanggal</th>
                                        <th>Kegiatan</th>
                                        <th>Barang</th>
                                        <th>Peserta</th>
                                        <th>Keterangan</th>
                                        <th style="width: 5%;">Absensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data == 0)
                                    <tr class="text-center">
                                        <td colspan="9">Tidak ada data</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="9">Sedang mengambil data ...</td>
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
            <form method="GET" action="{{ route('kegiatan.show') }}">
                @csrf
                <div class="modal-body">
                    @if (Auth::user()->role_id != 4)
                    <div class="form-group text-sm">
                        <label>Pilih Unit Kerja</label>
                        <select id="uker" name="uker" class="form-control form-control-sm border-dark rounded">
                            <option value="">Seluruh Unit Kerja</option>
                            @foreach($ukerList as $row)
                            <option value="{{ $row->id_unit_kerja }}" <?php echo $uker == $row->id_unit_kerja ? 'selected' : '' ?>>
                                {{ $row->unit_kerja }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group text-sm">
                        <label>Absensi</label>
                        <select id="absen" name="absen" class="form-control form-control-sm border-dark rounded">
                            <option value="">Seluruh Abensi</option>
                            <option value="true" <?php echo $absen == 'true' ? 'selected' : ''; ?>>Ada</option>
                            <option value="false" <?php echo $absen == 'false' ? 'selected' : ''; ?>>Tidak Ada</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('kegiatan.show') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-undo"></i> Muat
                    </a>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                </div>
            </form>
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
    $(document).ready(function() {
        let uker  = $('#uker').val();
        let absen = $('#absen').val();

        // Muat tabel saat halaman pertama kali dimuat
        loadTable(uker, absen);

        function loadTable(uker, absen) {
            $.ajax({
                url: `{{ route('kegiatan.select') }}`,
                method: 'GET',
                data: {
                    uker: uker,
                    absen: absen
                },
                dataType: 'json',
                success: function(response) {
                    let tbody = $('.table tbody');
                    tbody.empty();

                    if (response.message) {
                        tbody.append(`
                        <tr>
                            <td colspan="9">${response.message}</td>
                        </tr>
                    `);
                    } else {
                        // Jika ada data
                        $.each(response, function(index, item) {
                            let actionButton = '';
                            let deleteUrl = "{{ route('kegiatan.delete', ':id') }}".replace(':id', item.id);
                            actionButton = `
                                <a href="#" class="btn btn-default btn-xs bg-danger rounded border-dark"
                                onclick="confirmRemove(event, '${deleteUrl}')">
                                    <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                </a>
                             `;
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no}</td>
                                    <td class="align-middle">${item.aksi}</td>
                                    <td class="align-middle text-left">${item.uker}</td>
                                    <td class="align-middle">${item.kode}</td>
                                    <td class="align-middle">${item.tanggal}</td>
                                    <td class="align-middle text-left">${item.kegiatan}</td>
                                    <td class="align-middle">${item.barang}</td>
                                    <td class="align-middle">${item.peserta}</td>
                                    <td class="align-middle text-left">${item.keterangan}</td>
                                    <td class="align-middle">${item.file}</td>
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
                                title: 'kegiatan',
                                exportOptions: {
                                    columns: [0, 2, 3, 4],
                                },
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'kegiatan',
                                exportOptions: {
                                    columns: [0, 2, 3, 4, 5, 6, 7, 8],
                                },
                            }, {
                                text: 'Tambah',
                                action: function(e, dt, node, config) {
                                    window.location.href = "{{ route('kegiatan.create') }}"
                                },
                                className: 'bg-primary',
                            }, ],
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
