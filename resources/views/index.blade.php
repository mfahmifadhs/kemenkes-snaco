<!DOCTYPE html>
<html lang="en">

<head>
    <title>SNACO</title>
    <meta charset="UTF-8">
    <meta name="description" content="Siporsat Kemenkes RI">
    <meta name="keywords" content="Siporsat Kemenkes RI">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icon Title -->
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logo-kemenkes-icon.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap.min.css') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Main Css -->
    <link rel="stylesheet" href="{{ asset('dist/css/home.css') }}" />


    <!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header section -->
    <header class="header-section clearfix">
        <div class="container-fluid">
            <a href="index.html" class="site-logo">
                <img src="{{ asset('dist/img/biro-umum.png') }}" width="350" height="120" alt="">
            </a>
        </div>
    </header>
    <!-- Header section end -->


    <!-- Hero section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 hero-text">
                    <h2 class="mb-1">SNACO</h2>
                    <h4><small>
                        <i>Snack Corner Management System</i> <br>
                        Biro Umum Kementerian Kesehatan
                    </small></h4>
                    <a href="{{ route('login') }}" class="site-btn sb-gradients mt-4">Masuk</a>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('dist/img/tentang.png') }}" class="laptop-image" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- Hero section end -->


    <!-- About section -->
    <section class="about-section spad mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-6 about-text">
                    <h2>SNACOMS</h2>
                    <h5>Sistem Informasi Pengelolaan Operasional Perkantoran Terpusat</h5>
                    <p>SIPORSAT merupakan aplikasi yang dikembangkan oleh unit kerja Biro Umum Kemenkes RI, untuk membantu
                        dalam pengelolaan operasional perkantoran secara terpusat meliputi pengadaan maupun pemeliharaan barang.
                        Dengan SIPORSAT, membantu dalam proses pengajuan usulan pengeadaan dan pemeliharaan barang.</p>
                </div>
                <div class="about-img col-lg-6">
                    <img src="{{ asset('dist/img/tentang.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- About section end -->

    <!-- Footer section -->
    <footer class="footer-section">
        <div class="container">
            <div class="row spad">
                <div class="col-md-8 col-lg-12 footer-widget text-center">
                    <img src="{{ asset('dist/img/biro-umum.png') }}" width="350" height="120" alt="">
                    <p><i>
                        Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Jakarta 12950 <br>
                        Telepon : (021) 5201590</i>
                    </p>
                    <span>
                        Snaco Corner Management System
                    </span>
                </div>
                <div class="col-md-6 col-lg-12 footer-widget pl-lg-5 pl-3 text-center mt-4">
                    <div class="social">
                        <a href="https://www.facebook.com/KementerianKesehatanRI/" class="facebook"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.instagram.com/kemenkes_ri/" class="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://twitter.com/kemenkesri" class="twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-lg-6 store-links text-center text-lg-left pb-3 pb-lg-0">
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved | Biro Umum Kemenkes
                    </div>
                    <div class="col-lg-6 text-center text-lg-right">
                        <ul class="footer-nav">
                            <li><a href="#">www.roum.kemkes.go.id</a></li>
                            <li><a href="#">roum@kemkes.go.id</a></li>
                            <li><a href="#">(021) 511392872</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!--====== Javascripts & Jquery ======-->
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('dist/js/home.js') }}"></script>
</body>

</html>
