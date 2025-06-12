<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from kanakku.dreamguystech.com/template-html/forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 02 Aug 2021 11:57:41 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Reset Kata Sandi &mdash; {{env('APP_NAME')}}</title>

    <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}">

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome/css/all.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <!--[if lt IE 9]>
    <script src="{{asset('assets/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('assets/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body>

<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <img class="img-fluid logo-dark mb-2" src="{{asset('assets/img/logo.png')}}" alt="Logo">
            <div class="loginbox">
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Reset Password</h1>
                        <p class="account-subtitle">Silakan masukan password baru</p>

                        <form action="{{ route('reset.password.post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email" tabindex="1"
                                       value={{ $email }} required autofocus>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Kata Sandi Baru</label>
                                <input id="password" type="password" class="form-control pwstrength"
                                       data-indicator="pwindicator" name="password" tabindex="2" required>
                                <div id="pwindicator" class="pwindicator">
                                    <div class="bar"></div>
                                    <div class="label"></div>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Konfirmasi Kata Sandi</label>
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" tabindex="2" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-0">
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Reset Password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('assets/js/jquery-3.5.1.min.js')}}"></script>

<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

<script src="{{asset('assets/js/feather.min.js')}}"></script>

<script src="{{asset('assets/js/script.js')}}"></script>
</body>

</html>
