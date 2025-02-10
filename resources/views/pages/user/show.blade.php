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
                <h4 class="m-0">Daftar User</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('snaco.dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">User</li>
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
        <div class="card border border-dark">
            <div class="card-header">
                <label class="mt-2">Daftar User</label>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-data" class="table table-bordered text-sm">
                        <thead>
                            <tr>
                                <th style="width: 0%;" class="text-center">No</th>
                                <th style="width: 12%;" class="text-center">Aksi</th>
                                <th style="width: 20%;">Nama</th>
                                <th style="width: 10%;">NIP</th>
                                <th style="width: 20%;">Jabatan</th>
                                <th style="width: 20%;">Tim Kerja</th>
                                <th style="width: 20%;">Unit Kerja</th>
                                <th style="width: 10%;">Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    <!-- <a href="{{ route('user.detail', $row->id) }}" class="btn btn-default btn-xs bg-primary rounded border-dark">
                                        <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                                    </a> -->
                                    <a href="{{ route('user.edit', $row->id) }}" class="btn btn-default btn-xs bg-warning rounded border-dark">
                                        <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                    </a>
                                    <!-- <a href="#" class="btn btn-default btn-xs bg-danger rounded border-dark" onclick="confirmRemove(event, `{{ route('user.delete', $row->id) }}`)">
                                        <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                    </a> -->
                                </td>
                                <td>{{ $row->pegawai->nama_pegawai }}</td>
                                <td>{{ $row->pegawai->nip }}</td>
                                <td>{{ $row->pegawai->jabatan?->jabatan }}</td>
                                <td>{{ $row->pegawai->timker?->tim_kerja }}</td>
                                <td>{{ $row->pegawai->uker->unit_kerja }}</td>
                                <td>{{ $row->username }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

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
        }, {
            text: 'Tambah',
            action: function(e, dt, node, config) {
                window.location.href = "{{ route('user.create') }}"
            },
            className: 'bg-primary',
        }, ],
        "bDestroy": true
    }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
</script>


<script>
    function confirmRemove(event, url) {
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
