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
                        <div class="card-tools">
                            <a href="" class="btn btn-default btn-sm text-dark" data-toggle="modal" data-target="#modalFilter">
                                <i class="fas fa-filter"></i> Filter
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <label class="text-sm">Total Barang : <span id="totalBarang"></span></label>
                            <table id="table-data" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 5%;">Foto</th>
                                        <th style="width: 10%;" class="{{ Auth::user()->role_id == 4 ? 'd-none' : '' }}">Unit Kerja</th>
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

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-filter"></i> Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="GET" action="{{ route('usulan.show.item') }}">
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
                    <div class="form-group">
                        <label class="text-sm">Pilih Jenis Barang</label>
                        <select id="kategori" name="kategori" class="form-control form-control-sm border-dark rounded">
                            <option value="">Semua Jenis Barang</option>
                            @foreach($kategoriList as $row)
                            <option value="{{ $row->id_kategori }}" <?php echo $kategori == $row->id_kategori ? 'selected' : '' ?>>
                                {{ $row->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-sm">Pilih Barang</label>
                        <select id="barang" name="barang" class="form-control form-control-sm border-dark rounded barang" style="width: 100%;">
                            <option value="{{ $snaco ? $snaco->id_snc : '' }}">
                                {{ $snaco ? $snaco->snc_nama.' '.$snaco?->snc_deskripsi : '' }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('usulan.show.item') }}" class="btn btn-danger btn-sm">
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
    $(document).ready(function() {
        let userRole = '{{ Auth::user()->role_id }}';
        let uker = $('#uker').val();
        let kategori = $('#kategori').val();
        let barang = $('#barang').val();

        loadTable(uker, kategori, barang);

        function loadTable(uker, kategori, barang) {
            $.ajax({
                url: `{{ route('usulan.show.item.selectAll') }}`,
                method: 'GET',
                data: {
                    uker: uker,
                    kategori: kategori,
                    barang: barang
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
                        $.each(response.data, function(index, item) {
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
                                    <td class="align-middle text-left {{ Auth::user()->role_id == 4 ? 'd-none' : '' }}">${item.uker}</td>
                                    <td class="align-middle">${item.kode}</td>
                                    <td class="align-middle">${item.kategori}</td>
                                    <td class="align-middle text-left">${item.barang}</td>
                                    <td class="align-middle text-left">${item.deskripsi}</td>
                                    <td class="align-middle">${item.jumlah}</td>
                                    <td class="align-middle">${item.satuan}</td>
                                </tr>
                            `);
                        });

                        $('#totalBarang').text(response.totalBarang);

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
    $(document).ready(function() {
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $(".barang").select2({
            placeholder: "Cari Barang...",
            allowClear: true,
            ajax: {
                url: "{{ route('snaco.selectAll') }}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response.map(function(item) {
                            return {
                                id: item.id,
                                text: item.id ? item.id + ' - ' + item.barang : item.barang
                            };
                        })
                    };
                },
                cache: true
            }
        })

        $(".barang").each(function() {
            let selectedId = $(this).find("option:selected").val();
            let selectedText = $(this).find("option:selected").text();

            if (selectedId) {
                let newOption = new Option(selectedText, selectedId, true, true);
                $(this).append(newOption).trigger('change');
            }
        });
    });
</script>

@endsection
@endsection
