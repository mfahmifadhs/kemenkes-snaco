<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SNACOMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icon Title -->
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logo-kemenkes-icon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('dist/plugins/select2/css/select2.css') }}">
    <!-- Swal -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @yield('css')
</head>

<!-- <body class="hold-transition sidebar-mini sidebar-collapse"> -->

<body class="hold-transition sidebar-mini sidebar-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader">
            <div class="loader"></div>
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user-circle"></i>
                        <b>{{ Auth::user()->pegawai->nama_pegawai }}</b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">
                            <p class="text-capitalize">
                                {{ Auth::user()->pegawai->nama_pegawai }}
                            </p>
                        </span>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('unit-kerja/profil/user/'. Auth::user()->id) }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="{{ asset('dist/img/logo-kemenkes-icon.png') }}" alt="Sistem Informasi Pergudangan" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SNACOMS</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar mt-3">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Beranda</p>
                            </a>
                        </li>
                        @if (Auth::user()->role_id != 4)
                        <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-line-chart"></i>
                                <p>
                                    Laporan
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('snaco.report') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Grafik</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('snaco.report.stok') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Stok</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if (Auth::user()->role_id != 3)
                        <li class="nav-item">
                            <a href="{{ route('snaco.dashboard') }}" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-store"></i>
                                <p>Snack Corner</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-header"><i>Administrasi</i></li>
                        <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Daftar Usulan
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('usulan.show', 'snc') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usulan</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('usulan.show.item') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Detail</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if (Auth::user()->role_id != 4)
                        <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-boxes-packing"></i>
                                <p>
                                    Barang Masuk
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('snaco.stok.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pembelian</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('stok.show.item') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Detail</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-dolly"></i>
                                <p>
                                    Barang Keluar
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('kegiatan.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kegiatan</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('kegiatan.show.item') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Detail</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header"><i>Barang</i></li>
                        <li class="nav-item">
                            <a href="{{ route('snaco.show') }}" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-boxes-stacked"></i>
                                <p>Daftar Barang</p>
                            </a>
                        </li>
                        @if (Auth::user()->role_id != 4)
                        <li class="nav-item">
                            <a href="{{ route('jenis-snaco.show') }}" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-boxes-stacked"></i>
                                <p>Daftar Jenis Barang</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->role_id == 1)
                        <li class="nav-header"><i>Pengaturan</i></li>
                        <li class="nav-item">
                            <a href="{{ route('user.show') }}" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-user"></i>
                                <p>User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pegawai.show') }}" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Pegawai</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
            <br>
        </div>


        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">Moys</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('dist/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('dist/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dist/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('dist/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- ChartJS -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Include SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Chart -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/0.7.0/chartjs-plugin-datalabels.min.js"></script>

    @if (Session::has('failed'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ Session::get("failed") }}',
        });
    </script>
    @endif

    @if (Session::has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ Session::get("success") }}',
        });
    </script>
    @endif

    @yield('js')
    <script>
        $(function() {
            var url = window.location.href;

            // Untuk single sidebar menu
            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).addClass('active');

            // Untuk sidebar menu dan treeview (buka menu jika salah satu sub-menu aktif)
            $('ul.nav-treeview a').filter(function() {
                return this.href == url;
            }).each(function() {
                $(this)
                    .addClass('active') // Tambah class active ke link yang cocok
                    .closest('.nav-treeview') // Cari elemen ul.nav-treeview terdekat
                    .css('display', 'block') // Tampilkan sub-menu
                    .closest('.nav-item') // Ambil elemen li.nav-item terdekat (parent)
                    .addClass('menu-is-opening menu-open') // Tambahkan class yang diinginkan
                    .children('a').addClass('active'); // Tambahkan class active ke parent menu utama
            });
        });

        $(document).ready(function() {
            $('.number').on('input', function() {
                // Menghapus karakter selain angka (termasuk tanda titik koma sebelumnya)
                var value = $(this).val().replace(/[^0-9]/g, '');
                // Format dengan menambahkan titik koma setiap tiga digit
                var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                $(this).val(formattedValue);
            });
        });
    </script>

    <script>
        // Password
        $(document).ready(function() {
            $("#eye-icon-pass").click(function() {
                var password = $("#password");
                var icon = $("#eye-icon-pass");
                if (password.attr("type") == "password") {
                    password.attr("type", "text");
                    icon.removeClass("fas fa-eye").addClass("fas fa-eye-slash");
                } else {
                    password.attr("type", "password");
                    icon.removeClass("fas fa-eye-slash").addClass("fas fa-eye");
                }
            });
        });
    </script>
</body>

</html>
