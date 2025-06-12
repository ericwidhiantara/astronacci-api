<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap4.css') }}" />

    @yield('css')

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!--[if lt IE 9]>
    <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('assets/js/respond.min.js') }}"></script>
    <![endif]-->

</head>
<body>

<div class="main-wrapper">

    @include('layouts.partials.header')

    @include('layouts.partials.sidebar')

   @yield('content')

</div>

<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/js/feather.min.js') }}"></script>

<script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>

@yield('js')

<script src="{{ asset('assets/js/script.js') }}"></script>

<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>

<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>

<script>
    function logoutFunc() {
        swal({
            icon: 'warning',
            title: 'Logout',
            text: 'Anda yakin ingin logout ?',
            buttons: ["Tidak", "Ya"],
            dangerMode: true,
        })
            .then(isClose => {
                if (isClose) {
                    document.getElementById('logout-form').submit();
                    swal({
                        title: 'BERHASIL!',
                        text: 'Logout Berhasil!',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false,
                        showCancelButton: false,
                        buttons: false,
                    })
                }
            });
    }
</script>

<script>
    //active select2
    $(document).ready(function () {
        $('select').select2({
            theme: 'bootstrap4',
            width: 'style',
        });
    });

    //flash message
    @if(session()->has('success'))
    swal({
        type: "success",
        icon: "success",
        title: "BERHASIL!",
        text: "{{ session('success') }}",
        timer: 1500,
        showConfirmButton: false,
        showCancelButton: false,
        buttons: false,
    });
    @elseif(session()->has('error'))
    swal({
        type: "error",
        icon: "error",
        title: "GAGAL!",
        text: "{{ session('error') }}",
        timer: 1500,
        showConfirmButton: false,
        showCancelButton: false,
        buttons: false,
    });
    @endif
</script>

</body>

</html>
