<aside class="main-sidebar sidebar-light-lightblue elevation-4">

    <a href="index3.html" class="brand-link">
        <img src="{{ asset('img/AdminLTELogo.png') }}" alt="App Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <div class="sidebar">

        <div class="form-inline mt-2 mb-2 pb-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sm form-control-sidebar" type="search" placeholder="Search Menu"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sm btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-collapse-hide-child nav-flat" data-widget="treeview"
                role="menu" data-accordion="false">

                @foreach ($sidebars as $sidebar)
                <li class="nav-item">
                    <a href="{{ url($sidebar->slug) }}" class="nav-link">
                        <i class="nav-icon fa fa-folder"></i>
                        <p>
                            {{ $sidebar->name }}
                            @if (sizeof($sidebar->children)>0)
                            <i class="right fas fa-angle-left"></i>
                            @endif
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($sidebar->children as $children)
                        <li class="nav-item">
                            <a href="{{ url($children->slug) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ $children->name }}</p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </nav>

    </div>

</aside>