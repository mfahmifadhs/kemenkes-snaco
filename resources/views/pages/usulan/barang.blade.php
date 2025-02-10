@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Permintaan</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
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
                            Daftar Permintaan
                        </label>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table-data" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 5%;">Foto</th>
                                        <th>Kode</th>
                                        <th>Barang</th>
                                        <th>Merk</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($barang == 0)
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

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(document).ready(function() {
        let userRole = '{{ Auth::user()->role_id }}';

        // Muat tabel saat halaman pertama kali dimuat
        loadTable();

        function loadTable(kategori, barang, status) {
            $.ajax({
                url: `{{ route('usulan.show.item.selectAll') }}`,
                method: 'GET',
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
                            if (item.role_id == 1) {
                                actionButton = `
                                    <a href="#" class="btn btn-default btn-xs bg-primary rounded" onclick="showModal('${item.id}')">
                                        <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                    </a>
                                `;
                            }
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no}</td>
                                    <td class="align-middle">${item.foto}</td>
                                    <td class="align-middle">${item.kode}</td>
                                    <td class="align-middle">${item.kategori}</td>
                                    <td class="align-middle text-left">${item.barang}</td>
                                    <td class="align-middle text-left">${item.deskripsi}</td>
                                    <td class="align-middle">${item.jumlah}</td>
                                    <td class="align-middle">${item.satuan}</td>
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
                                title: 'show',
                                exportOptions: {
                                    columns: [0, 3, 4, 5, 6, 7, 8, 9, 10]
                                },
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'show',
                                exportOptions: {
                                    columns: [0, 12, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                                },
                            },
                            userRole == 1 ? {
                                text: ' Upload',
                                className: 'bg-primary',
                                action: function(e, dt, button, config) {
                                    $('#modal-upload').modal('show');
                                }
                            } : null
                        ].filter(Boolean),
                            "bDestroy": true
                        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });

            window.showModal = function(id) {
                $.ajax({
                    url: `{{ url('/snaco/detail/select/') }}/${id}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Mengisi modal dengan data tamu
                        $('#input-id').val(data.id_snc);
                        $('#input-kategori').val(data.snc_kategori).change();
                        $('#input-barang').val(data.snc_nama).change();
                        $('#input-deskripsi').val(data.snc_deskripsi);
                        $('#input-harga').val(new Intl.NumberFormat('id-ID').format(data.snc_harga));
                        $('#input-satuan').val(data.snc_satuan).change();
                        $('#input-keterangan').val(data.snc_keterangan).change();

                        if (data.snc_foto) {
                            $('#modal-foto').attr('src', `{{ asset('storage/file/foto_snaco/') }}/${data.snc_foto}`)
                        } else {
                            $('#modal-foto').attr('src', `https://cdn-icons-png.flaticon.com/512/679/679821.png`)
                        }


                        $('#input-status').val(data.snc_status).change();

                        // Menampilkan modal
                        $('#editModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        // console.error('Error fetching detail:', error);
                    }
                });
            };
        }
    });
</script>

<script>
    $(function() {
        $('.previewImg').change(function() {
            const previewId = $(this).data('preview'); // Ambil ID target dari data-preview
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                // Ketika file dibaca, update atribut src dari elemen target
                reader.onload = (e) => {
                    $(`#${previewId}`).attr('src', e.target.result);
                };

                reader.readAsDataURL(file); // Membaca file sebagai URL
            }
        });
    });
</script>

@endsection
@endsection
