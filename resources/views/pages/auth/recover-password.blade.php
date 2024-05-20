@extends('layouts.auth')

@section('title')
Reset Password
@endsection

@section('content')
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo/logo.svg') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title fs-2">Reset Password.</h1>
                <p class="auth-subtitle fs-6 mb-2">You are only one step a way from your new password, recover your
                    password now.</p>
                @if ($errors->any())
                <div class="alert alert-danger border-left-danger text-sm" role="alert">
                    <ul class="pl-4 my-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="text" class="form-control" placeholder="Email" value="{{ $email }}" readonly>
                        <div class="form-control-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="text" name="password" class="form-control" placeholder="Password">
                        <div class="form-control-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="text" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password">
                        <div class="form-control-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block shadow-lg mt-3">Change password</button>
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
@stop