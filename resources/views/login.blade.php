<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SNACO</title>

    <!-- Icon Title -->
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logo-kemenkes-icon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box col-md-6">
        <!-- /.login-logo -->
        <div class="form-group first">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @endif
            @if ($message = Session::get('failed'))
            <div class="alert alert-danger">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @endif
        </div>
        <div class="card" style="border-radius: 20px;">
            <div class="card-header text-center mt-4">
                <a href="{{ url('/') }}" class="my-5">
                    <img src="{{ asset('dist/img/snacoms.png') }}" class="img-fluid w-75" alt="">
                    <h1 class="text-primay font-weight-bold">
                    </h1>
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('post.login', 'masuk.post')) }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text rounded-left">
                                <span class="fas fa-users"></span>
                            </div>
                        </div>
                        <input type="text" name="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="input-group mb-3" id="password">
                        <div class="input-group-append">
                            <div class="input-group-text rounded-left">
                                <a type="button" onclick="lihatPassword()"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="social-auth-links text-center mt-2 mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            Masuk
                        </button>
                    </div>
                </form>

                <p class="mb-1">
                    <a href="#">Lupa password ?</a>
                </p>
                <p class="mb-0">
                    <a href="https://wa.me/6285772652563" class="text-center">Bantuan</a>
                </p>
            </div>
            <div class="card-footer text-center">
                <img src="{{ asset('dist/img/biro-umum.png') }}" class="img-fluid" width="150">
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>

    <!-- Lihat Password -->
    <script type="text/javascript">
        function lihatPassword() {
            var x = document.getElementById("password");
            if ($('#password input').attr("type") == "password") {
                $('#password input').attr('type', 'text');
                $('#password i').addClass("fa-eye-slash");
                $('#password i').removeClass("fa-eye");
            } else {
                $('#password input').attr('type', 'password');
                $('#password i').removeClass("fa-eye-slash");
                $('#password i').addClass("fa-eye");
            }
        }

        $('#reload').click(function() {
            $.ajax({
                type: 'GET',
                url: 'captcha-reload',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
</body>

</html>
