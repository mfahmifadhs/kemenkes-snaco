<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SNACOMS | KEMENKES RI</title>

    <!-- Icon Title -->
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logo-kemenkes-icon.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <style>
        body {
            font-family: Arial;
        }

        @media print {

            .table-container {
                page-break-before: auto;
                page-break-inside: auto;
            }

            .footer-container {
                page-break-inside: avoid;
            }

            .footer-container .row {
                page-break-inside: avoid;
            }

            .footer-container h3,
            .footer-container img {
                page-break-inside: avoid;
            }

            table {
                page-break-before: auto;
                page-break-inside: auto;
                border-collapse: collapse;
                width: 100%;
            }

            thead {
                display: table-header-group;
                border: 2px solid;
            }

            tbody {
                display: table-row-group;
                border: 2px solid;
            }

            tr {
                page-break-inside: auto;
                border: 2px solid;
            }

            thead .th {
                border: 2px solid !important;
            }

            tbody .td {
                border: 2px solid !important;
            }
        }
    </style>
</head>

<body>
    <div class="card mx-5">
        <div class="card-body">
            <img src="{{ asset('dist/img/header-46593.png') }}" alt="">
        </div>
        <div class="card-body no-break">
            <div class="text-center text-uppercase">
                <h3><b>Berita Acara Serah Terima</b></h3>
                <h4>Nomor : {{ $data->nomor_usulan }}</h4>
            </div>
        </div>
        <div class="card-body h3 no-break">
            <div class="row">
                <div class="col-8">
                    <div class="row">
                        <div class="col-3">Hal</div>
                        <div class="col-8">: Permintaan Snack Corner</div>
                    </div>
                </div>
                <div class="col-4">
                    {{ Carbon\Carbon::parse($data->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                </div>
            </div>
            <div class="row mt-3 ls-base">
                <div class="col-2">Nama</div>
                <div class="col-9">: {{ $data->user->pegawai->nama_pegawai }}</div>
                <div class="col-2">Jabatan</div>
                <div class="col-9">: {{ $data->user->pegawai->jabatan->jabatan }} {{ $data->user->pegawai->timker->tim_kerja }}</div>
                <div class="col-2">Unit Kerja</div>
                <div class="col-9">: {{ $data->user->pegawai->uker->unit_kerja }} | {{ $data->user->pegawai->uker->utama->unit_utama }}</div>
            </div>
        </div>
        <!-- ========================= UKT & GDN ============================ -->
        @if (in_array($data->form_id, [1,2]))
        <div class="card-body">
            <div class="table-container">
                <table class="table table-bordered border border-dark">
                    <thead class="h4 text-center">
                        <tr>
                            <th class="th" style="width: 5%;">No</th>
                            <th class="th" style="width: 30%;">Judul</th>
                            <th class="th">Uraian</th>
                            <th class="th" style="width: 20%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="h4">
                        @foreach ($data->detail as $row)
                        <tr class="bg-white">
                            <td class="text-center td">{{ $loop->iteration }}</td>
                            <td class="td">{{ $row->gdn ? $row->gdn->nama_perbaikan .',' : '' }} {!! $row->judul !!}</td>
                            <td class="td">{!! nl2br($row->uraian) !!}</td>
                            <td class="td">{!! nl2br($row->keterangan) !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ========================== PERMINTAAN ================================= -->
        <div class="card-body h4" style="overflow-y: auto; max-height: 50vh;">
            <label>Uraian Permintaan</label>
            <div class="table-responsive">
                <table id="table" class="table table-bordered border border-dark h4">
                    <thead class="text-center">
                        <tr>
                            <th class="th">No</th>
                            <th class="th">Nama Barang</th>
                            <th class="th">Jumlah</th>
                            <th class="th">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->usulanSnc as $row)
                        <tr class="bg-white">
                            <td class="td text-center">{{ $loop->iteration }}</td>
                            <td class="td">{{ $row->snc->snc_nama.' '.$row->snc->snc_deskripsi }}</td>
                            <td class="td text-center">{{ $row->jumlah_permintaan }}</td>
                            <td class="td text-center">{{ $row->snc->satuan->satuan }} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-body">
            <div class="footer-container">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <h3 class="lh-base">
                            Demikian {{ $data->form_id == 3 ? 'berita acara serah terima' : 'surat usulan' }}
                            ini kami sampaikan. Atas perhatian dan kerjasamanya diucapkan terima kasih
                        </h3>
                    </div>
                    @if ($data->status_persetujuan == 'true')
                    <div class="col-5">
                        <h3>Disetujui oleh,</h3>
                        <h3>{{ $data->verif->jabatan->jabatan }} {{ $data->verif->timker->tim_kerja }}</h3>
                        <h3 class="my-3"><img src="{{ \App\Helpers\QrCodeHelper::generateQrCode('https://snacoms.kemkes.go.id//surat/'. $data->otp_2 .'/'. $data->kode_usulan) }}" width="150" alt="QR Code"></h3>
                        <h3>{{ $data->verif->nama_pegawai }}</h3>
                    </div>
                    @else
                    <div class="col-5"></div>
                    @endif
                    <div class="col-2"></div>
                    <div class="col-5">
                        <h3>Diusulkan oleh,</h3>
                        <h3>{{ $data->user->pegawai->jabatan->jabatan }} {{ $data->user->pegawai->timker->tim_kerja }}</h3>
                        <h3 class="my-3"><img src="{{ \App\Helpers\QrCodeHelper::generateQrCode('https://snacoms.kemkes.go.id//surat/'. $data->otp_1 .'/'. $data->kode_usulan) }}" width="150" alt="QR Code"></h3>
                        <h3>{{ $data->user->pegawai->nama_pegawai }}</h3>
                    </div>
                </div>
                @if ($data->status_proses == 'selesai')
                <div class="row mt-5">
                    <div class="col-md-12">
                        <h3 class="lh-base">
                            {{ Carbon\Carbon::parse($data->tanggal_ambil)->isoFormat('DD MMMM Y') }}
                        </h3>
                    </div>
                    @if ($data->nama_penerima)
                    <div class="col-5">
                        <h3>Diserahkan oleh,</h3>
                        <h3>Petugas Gudang</h3>
                        <h3 class="my-3"><img src="{{ \App\Helpers\QrCodeHelper::generateQrCode('https://snacoms.kemkes.go.id//surat/'. $data->otp_4 .'/'. $data->kode_usulan) }}" width="150" alt="QR Code"></h3>
                        <h3>Desya Prima</h3>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-5">
                        <h3>Diterima oleh,</h3>
                        <h3>{{ $data->user->pegawai->uker->unit_kerja }}</h3>
                        <h3 class="my-3"><img src="{{ \App\Helpers\QrCodeHelper::generateQrCode('https://snacoms.kemkes.go.id//surat/'. $data->otp_3 .'/'. $data->kode_usulan) }}" width="150" alt="QR Code"></h3>
                        <h3>{{ $data->nama_penerima }}</h3>
                    </div>
                    @endif

                    <div class="col-md-12 mt-5">
                        <img src="{{ asset('dist/img/noted-surat.png')}}" class="img-fluid" alt="">
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</body>

<script>
    window.addEventListener("load", window.print());
</script>

</html>
