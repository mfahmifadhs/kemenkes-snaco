@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Jenis Barang</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Jenis Barang</li>
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
                            Daftar Jenis Barang
                        </label>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table-data" class="table table-bordered">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th class="text-center" style="width: 0%;">No</th>
                                        <th class="text-center" style="width: 15%;">Aksi</th>
                                        <th>Nama Jenis Barang</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center" style="width: 15%;">Icon</th>
                                        <th class="text-center" style="width: 15%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('jenis-snaco.detail', $row->id_kategori) }}" class="btn btn-default btn-xs bg-primary rounded border-dark">
                                                <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                                            </a>
                                            <a href="{{ route('jenis-snaco.edit', $row->id_kategori) }}" class="btn btn-default btn-xs bg-warning rounded border-dark">
                                                <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                            </a>
                                            <a href="#" class="btn btn-default btn-xs bg-danger rounded border-dark" onclick="confirmRemove(event, `{{ route('jenis-snaco.delete', $row->id_kategori) }}`)">
                                                <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                            </a>
                                        </td>
                                        <td>{{ $row->nama_kategori }}</td>
                                        <td>{{ $row->deskripsi }}</td>
                                        <td class="text-center"><i class="{{ $row->icon }}"></i></td>
                                        <td class="text-center">
                                            @if ($row->status == 'true')
                                            <span class="badge badge-success">Tersedia</span>
                                            @endif
                                            @if ($row->status == 'false')
                                            <span class="badge badge-danger">Tidak Tersedia</span>
                                            @endif
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
                title: 'show',
                exportOptions: {
                    columns: [0, 2, 3, 4],
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
                    window.location.href = "{{ route('jenis-snaco.create') }}"
                },
                className: 'bg-primary',
            }, ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
    })
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
