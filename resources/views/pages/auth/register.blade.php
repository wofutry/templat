@extends('layouts.auth')

@section('title')
Create Account
@endsection

@section('content')
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo/logo.svg') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title fs-2">Sign Up.</h1>
                <p class="auth-subtitle fs-6 mb-2">Input your data to register to our website.</p>
                @if ($errors->any())
                <div class="alert alert-danger border-left-danger text-sm" role="alert">
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
                <form action="{{ route('register.user') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_user_level" value="2">
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Full Name"
                            value="{{ old('name') }}">
                        <div class="form-control-icon">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}">
                        <div class="form-control-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="password" name="password" class="form-control" placeholder="Your password">
                        <div class="form-control-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repeat your password">
                        <div class="form-control-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block shadow-lg mt-3">Log in</button>
                </form>
                <div class="text-center mt-3 text-sm fs-6">
                    <p class="text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-bold">Sign In</a>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>

</div>
@endsection