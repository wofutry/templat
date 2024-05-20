@extends('layouts.admin')

@section('title')
Welcome to Laravel
@endsection

@section('content')
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Welcome to {{ env('APP_NAME') }}</h3>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card card-outline card-success">
                <div class="card-header">
                </div>
                <div class="card-body">
                    @if (session()->has('success'))
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Success</h4>
                        <p>{{ session('success') }}</p>
                    </div>
                    @else
                    <p>Welcome to our site.</p>
                    @endif
                </div>
            </div>
        </section>
    </div>
    @include('components.footer')
</div>
@endsection