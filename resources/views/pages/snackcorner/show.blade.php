@extends('layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Barang</h4>
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
                            Daftar Barang
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
                                        <th style="width: 5%;">Foto</th>
                                        <th>Barang</th>
                                        <th>Merk</th>
                                        <th>Deskripsi</th>
                                        <th>Maksimal</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Satuan</th>
                                        <th>Status</th>
                                        <th class="d-none">ID</th>
                                        <th class="d-none">Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data->count() == 0)
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
            <form method="GET" action="{{ route('snaco.show') }}">
                @csrf
                <div class="modal-body">
                    <div class="d-flex">
                        <label class="w-25 col-form-label">Pilih Barang</label>
                        <select name="kategori" id="kategori" class="w-75 form-control kategori" style="width: 75%;">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($listKategori as $row)
                            <option value="{{ $row->id_kategori }}" {{ $row->id_kategori == $kategori ? 'selected' : '' }}>
                                {{ $row->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Pilih Merk</label>
                        <select name="barang" id="barang" class="w-75 form-control barang" style="width: 75%;">
                            @if (!$barang)
                            <option value="">-- Pilih Merk --</option>
                            @else
                            <option value="{{ $barang }}">{{ $data->where('id_snc', $barang)->first()->snc_nama }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="d-flex mt-2">
                        <label class="w-25 col-form-label">Pilih Stok</label>
                        <select id="status" name="status" class="form-control border-dark rounded w-75">
                            <option value="">-- Seluruh Status --</option>
                            <option value="true" <?php echo $status == 'true' ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="false" <?php echo $status == 'false' ? 'selected' : ''; ?>>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('snaco.show') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-undo"></i> Muat
                    </a>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="editModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('snaco.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 text-center mt-5">
                            <img id="modal-foto" src="https://cdn-icons-png.flaticon.com/512/679/679821.png" alt="Foto Barang" class="img-fluid">

                            <div class="btn btn-warning btn-block btn-sm mt-1 btn-file border-dark">
                                <i class="fas fa-paperclip"></i> Upload Foto
                                <input type="file" name="foto" class="previewImg" data-preview="modal-foto" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <label class="col-md-12 text-secondary">Informasi Barang</label>

                                <div class="col-md-3 col-form-label my-1">ID</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <input type="text" id="input-id" class="form-control" name="id_snc" readonly>
                                </div>

                                <div class="col-md-3 col-form-label my-1">Barang</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <select name="snc_kategori" id="input-kategori" class="form-control">
                                        @foreach ($listKategori as $row)
                                        <option value="{{ $row->id_kategori }}">{{ $row->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 col-form-label my-1">Merk</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <input type="text" id="input-barang" class="form-control" name="snc_nama">
                                </div>

                                <div class="col-md-3 col-form-label my-1">Deskripsi</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <input type="text" id="input-deskripsi" class="form-control" name="snc_deskripsi">
                                </div>

                                <div class="col-md-3 col-form-label my-1">Harga</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <input type="text" id="input-harga" class="form-control number" name="snc_harga">
                                </div>

                                <div class="col-md-3 col-form-label my-1">Satuan</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <select name="snc_satuan" id="input-satuan" class="form-control">
                                        @foreach ($listSatuan as $row)
                                        <option value="{{ $row->id_satuan }}">{{ $row->satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 col-form-label my-1">Keterangan</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <input type="text" id="input-keterangan" class="form-control number" name="snc_keterangan">
                                </div>

                                <div class="col-md-3 col-form-label my-1">Status</div>
                                <div class="col-md-1 col-form-label my-1">:</div>
                                <div class="col-md-8 my-1">
                                    <select name="snc_status" id="input-status" class="form-control">
                                        <option value="true">Tersedia</option>
                                        <option value="false">Tidak Tersedia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-upload" action="{{ route('snaco.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fileInput">Upload File</label>
                        <div class="input-group">
                            <input type="file" class="form-control h-100" id="fileInput" name="file" required>
                        </div>
                        <small id="fileName" class="form-text text-muted"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmUpload(event)">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(document).ready(function() {
        $('.kategori, .barang').select2();

        $(document).on('change', '.kategori', function() {
            var kategoriId = $(this).val();

            $.ajax({
                url: '/snaco/detail/barang/' + kategoriId,
                type: 'GET',
                success: function(data) {
                    var barangSelect = $('#barang');

                    barangSelect.empty();
                    $.each(data, function(key, val) {
                        barangSelect.append('<option value="' + val.id + '" data-satuan="' + val.satuan + '">' + val.text + '</option>');
                    });

                    barangSelect.select2();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $(document).on('change', '.barang', function() {
            var selectedOption = $(this).find('option:selected');
            var satuan = selectedOption.data('satuan');

            $('#satuan').val(satuan || '');
        });
    });
</script>

<script>
    $(document).ready(function() {
        let kategori = $('#kategori').val();
        let barang = $('#barang').val();
        let status = $('#status').val();
        let userRole = '{{ Auth::user()->role_id }}';

        // Muat tabel saat halaman pertama kali dimuat
        loadTable(kategori, barang, status);

        function loadTable(kategori, barang, status) {
            $.ajax({
                url: `{{ route('snaco.selectAll') }}`,
                method: 'GET',
                data: {
                    kategori: kategori,
                    barang: barang,
                    status: status
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
                            if (item.role == 1) {
                                actionButton = `
                                    <a href="#" class="btn btn-default btn-xs bg-warning border-dark rounded" onclick="showModal('${item.id}')">
                                        <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                    </a>
                                `;
                            }
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no}</td>
                                    <td class="align-middle">${item.aksi} ${actionButton}</td>
                                    <td class="align-middle">${item.foto}</td>
                                    <td class="align-middle">${item.kategori}</td>
                                    <td class="align-middle text-left">${item.barang}</td>
                                    <td class="align-middle text-left">${item.deskripsi}</td>
                                    <td class="align-middle">${item.maksimal}</td>
                                    <td class="align-middle">${item.harga}</td>
                                    <td class="align-middle">${item.stok}</td>
                                    <td class="align-middle">${item.satuan}</td>
                                    <td class="align-middle">${item.status}</td>
                                    <td class="align-middle d-none">${item.id}</td>
                                    <td class="align-middle d-none">${item.fileFoto}</td>
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

<script>
    function confirmUpload(event) {
        event.preventDefault();

        const form = document.getElementById('form-upload');
        const requiredInputs = form.querySelectorAll('input[required]:not(:disabled)');

        let allInputsValid = true;

        requiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                allInputsValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        if (allInputsValid) {
            Swal.fire({
                title: 'Upload',
                text: '',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Proses...',
                        text: 'Mohon menunggu.',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Ada input yang diperlukan yang belum diisi.',
                icon: 'error'
            });
        }
    }
</script>

@endsection
@endsection
