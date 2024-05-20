<footer class="main-footer">
    @if (!Auth::check())
    <div class="container">
        @endif
        <div class="float-right d-none d-sm-inline">
            Anything you want
        </div>

        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
        reserved.
        @if (!Auth::check())
    </div>
    @endif
</footer>