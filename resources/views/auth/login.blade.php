@extends('layouts.app')

@section('content')
    <div class="login-wrapper">
        <div class="container">
            <img class="img-fluid logo-dark mb-2" src="{{ asset('assets/img/logo.png') }}" alt="Logo">
            <div class="loginbox">
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Login</h1>
                        <p class="account-subtitle">Untuk mengakses semua menu</p>
                        <form  method="POST" action="{{ route('login') }}" class="needs-validation"
                               novalidate="">
                            @csrf
                            <div class="form-group">
                                <label for="email"
                                       class="form-control-label @error('email') is-invalid @enderror">Email</label>
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror" name="email"
                                       placeholder="Masukkan Alamat Email" value="{{ old('email') }}" tabindex="1"
                                       required autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password"
                                       class="form-control-label @error('password') is-invalid @enderror">Password</label>
                                <div class="pass-group">
                                    <input id="password" type="password" class="form-control" name="password"
                                           placeholder="Masukkan Password" tabindex="2" required>

                                    <span class="fas fa-eye toggle-password"></span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="cb1">
                                            <label class="custom-control-label" for="cb1">Ingat Saya</label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-right">
                                        <a class="forgot-link" href="#">Lupa Password ?</a>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-block btn-primary" type="submit">Login</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
