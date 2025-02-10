@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Stok</h4>
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
                            Daftar Stok
                        </label>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table-data" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Tanggal</th>
                                        <th>No. Kwitansi</th>
                                        <th>Total Barang</th>
                                        <th>Total Harga</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stok as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('snaco.stok.detail', $row->id_stok) }}" class="btn btn-default btn-xs bg-primary rounded border-dark">
                                                <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                                            </a>'
                                            <a href="{{ route('snaco.stok.edit', $row->id_stok) }}" class="btn btn-default btn-xs bg-warning rounded border-dark">
                                                <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                            </a>'
                                            <a href="#" class="btn btn-default btn-xs bg-danger rounded border-dark" onclick="confirmRemove(event, `{{ route('snaco.stok.delete', $row->id_stok) }}`)">
                                                <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                            </a>'
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($row->tanggal_masuk)->isoFormat('DD MMMM Y') }}</td>
                                        <td>{{ $row->no_kwitansi }}</td>
                                        <td>{{ $row->detail->count() }} barang</td>
                                        <td>Rp {{ number_format($row->total_harga, 0, '.') }}</td>
                                        <td>{{ $row->keterangan }}</td>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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
            }],
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
