<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu bg-white text-dark">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/icones/Engeativos Logo C.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/icones/Engeativos Logo C.png') }}" alt="" height="50">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/icones/Engeativos Logo C.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/icones/Engeativos Logo C.png') }}" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link menu-link" href="/" aria-expanded="false">
                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                </li>


                @foreach ($modulos_permitidos as $module)
                
                <li class="nav-item">

                    @if (count($module['submodulos']) > 0)
                    <a class="nav-link" data-bs-toggle="collapse" href="#{{ $module['url_amigavel'] }}" aria-expanded="false" aria-controls="{{ $module['url_amigavel'] }}">
                        <i class="{{ $module['icone'] }} "></i>
                        <span class="menu-title">{{ $module['titulo'] }}</span>
                        <i class="menu-arrow"></i>

                    </a>

                    @else
                    <a class="nav-link" href="{{ env('URL_APP_ADMIN') }}{{ $module['url_amigavel'] }}">
                        <i class="{{ $module['icone'] }} menu-icon"></i>
                        <span class="menu-title">{{ $module['titulo'] }}</span>

                    </a>
                    @endif

                   
                    @if (count($module['submodulos']) > 0)

                    <div class="collapse" id="{{ $module['url_amigavel'] }}">
                        <ul class="nav nav-sm flex-column">
                            @foreach ($module['submodulos'] as $sub)

                            <li class="nav-item"> <a class="nav-link" href="{{ url($sub['url_amigavel']) }}">{{ $sub['titulo'] }}</a></li>



                            @endforeach
                        </ul>
                    </div>
                    @endif
                </li>
                @endforeach

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>