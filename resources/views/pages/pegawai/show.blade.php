@extends('layout.app')

@section('content')

@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
    });
</script>
@endif


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Pegawai</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></li>
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

<div class="content">
    <div class="container-fluid">
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">Daftar Pegawai</label>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-data" class="table table-bordered text-sm">
                        <thead>
                            <tr>
                                <th style="width: 0%;" class="text-center">No</th>
                                <th style="width: 0%;" class="text-center">Aksi</th>
                                <th style="width: 20%;">Nama</th>
                                <th style="width: 10%;">NIP</th>
                                <th style="width: 20%;">Jabatan</th>
                                <th style="width: 20%;">Tim Kerja</th>
                                <th style="width: 20%;">Unit Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pegawai as $row)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                    @if ($row->status == 'true') <i class="fas fa-check-circle text-success"></i> @endif
                                    @if ($row->status == 'false') <i class="fas fa-times-circle text-danger"></i> @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pegawai.edit', $row->id_pegawai) }}" class="btn btn-default btn-xs bg-warning rounded border-dark">
                                        <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                    </a>
                                </td>
                                <td>{{ $row->nama_pegawai }}</td>
                                <td>{{ $row->nip }}</td>
                                <td>{{ $row->jabatan?->jabatan }}</td>
                                <td>{{ $row->timker?->tim_kerja }}</td>
                                <td>{{ $row->uker->unit_kerja }}</td>
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
<script>
    $("#table-data").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
        "info": true,
        "paging": true,
        "searching": true,
        buttons: [{
            extend: 'pdf',
            text: ' PDF',
            pageSize: 'A4',
            className: 'bg-danger',
            title: 'show',
            exportOptions: {
                columns: [2, 3, 4, 5, 6, 7]
            },
        }, {
            extend: 'excel',
            text: ' Excel',
            className: 'bg-success',
            title: 'show',
            exportOptions: {
                columns: ':not(:nth-child(2))'
            },
        }],
        "bDestroy": true
    }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
</script>
@endsection

@endsection
