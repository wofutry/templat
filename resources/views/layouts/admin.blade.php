<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    {{-- DataTable --}}
    <link rel="stylesheet" href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    {{-- SweetAlert --}}
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    {{-- Select2 --}}
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css?v=3.2.0') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
@if (Auth::check())

<body class="hold-transition sidebar-mini text-sm layout-navbar-fixed sidebar-mini-md sidebar-mini-xs">
    @else

    <body class="layout-top-nav text-sm">
        @endif
        <div class="wrapper">

            @include('components.navbar')
            @if (Auth::check())
            @include('components.sidebar')
            @endif

            <div class="content-wrapper">
                <div class="content-header">
                    @if (Auth::check())
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">@yield('title')</h1>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">@yield('title')</h1>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="content">
                    @if (Auth::check())
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                    @else
                    <div class="container">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title m-0">Featured</h5>
                            </div>
                            <div class="card-body">
                                @if(session()->has('success'))
                                <div class="alert alert-success border-left-danger" role="alert">
                                    {{ session()->get('success') }}
                                </div>
                                @endif
                                <h6 class="card-title">Special title treatment</h6>
                                <p class="card-text">With supporting text below as a natural lead-in to additional
                                    content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            @include('components.control-sidebar')

            @include('components.footer')
        </div>

        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        {{-- DataTable --}}
        <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('vendor/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('vendor/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('vendor/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('vendor/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

        {{-- SweetAlert --}}
        <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

        {{-- Select2 --}}
        <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>

        <script src="{{ asset('js/adminlte.min.js?v=3.2.0') }}"></script>
        <script src="{{ asset('js/master.js') }}"></script>
        <script>
            const token = "{{ csrf_token() }}";

            function setMenu(url= '', url_1 = '', url_2 = '', url_3 = '') {
                if (url_1 != '') {
                    url += '/' + url_1;
                }
                if (url_2 != '') {
                    url += '/' + url_2;
                }
                if (url_3 != '') {
                    url += '/' + url_3;
                }
                // for sidebar menu entirely but not cover treeview
                $("ul.nav-sidebar a")
                    .filter(function() {
                        return this.href == url;
                    })
                    .addClass("active");

                // for treeview
                $("ul.nav-treeview a")
                    .filter(function() {
                        return this.href == url;
                    })
                    .parentsUntil(".nav-sidebar > .nav-treeview")
                    .addClass("menu-open")
                    .prev("a")
                    .addClass("active");
            }

            const url = "{{ URL::to('/') }}";
            const url_segment1 = "{{ Request::segment(1) }}";
            const url_segment2 = "{{ Request::segment(2) }}";
            const url_segment3 = "{{ Request::segment(3) }}";
            setMenu(url, url_segment1, url_segment2, url_segment3);
        </script>
        @stack('js')
    </body>

</html>