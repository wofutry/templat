@extends('layouts.auth')

@section('title')
Forgot Password
@endsection

@section('content')
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo/logo.svg') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title fs-2">Forgot Password.</h1>
                <p class="auth-subtitle fs-6 mb-2">Input your email and we will send you reset password link.</p>
                @if ($errors->any())
                <div class="alert alert-danger border-left-danger text-sm" role="alert">
                    <ul class="pl-4 my-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(session()->has('status'))
                <div class="alert alert-success border-left-danger text-sm" role="alert">
                    We already sent your reset password link in email, please check inbox / spam, password reset link
                    will
                    expire in 60 minutes.
                </div>
                @endisset
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Your Email">
                        <div class="form-control-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block shadow-lg mt-3">Request New Password</button>
                </form>
                <div class="text-center mt-3 text-sm fs-6">
                    <p class="text-gray-600">
                        Remember your account?
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
@stop