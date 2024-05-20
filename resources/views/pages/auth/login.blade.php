@extends('layouts.auth')

@section('title')
Sign In
@endsection

@section('content')
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo/logo.svg') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title fs-2">Sign In.</h1>
                <p class="auth-subtitle fs-6 mb-2">Sign in with your data that you entered during registration.</p>
                @if ($errors->any())
                <div class="alert alert-danger border-left-danger" role="alert">
                    <ul class="pl-4 my-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(session()->has('success'))
                <div class="alert alert-success border-left-danger" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                @if(session()->has('verified'))
                <div class="alert alert-success border-left-danger" role="alert">
                    Your account has been active.
                </div>
                @endif
                <form action="{{ route('login.authenticate') }}" method="post">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}">
                        <div class="form-control-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="form-control-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                    {{-- <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label text-gray-600" for="flexCheckDefault">
                            Keep me logged in
                        </label>
                    </div> --}}
                    <button class="btn btn-primary btn-block shadow-lg mt-3">Log in</button>
                </form>
                <div class="text-center mt-3 text-sm fs-6">
                    <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}"
                            class="font-bold">Sign
                            up</a>.</p>
                    <p><a class="font-bold" href="{{ route('password.request') }}">Forgot password?</a>.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>

</div>
@stop