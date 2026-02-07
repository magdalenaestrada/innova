<nav
    class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" id="notif-dropdown-link">
                <i class="far fa-bell"></i>
                @if ($notificacionesContratos['total'] > 0)
                    <span id="notif-badge"
                        class="badge badge-danger navbar-badge">{{ $notificacionesContratos['total'] }}</span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">Notificaciones</span>
                <div id="notif-items">
                    @if ($notificacionesContratos['por_vencer'] > 0)
                        <a href="{{ route('lqclientes.index') }}?estado=por_vencer" class="dropdown-item">
                            <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                            Hay {{ $notificacionesContratos['por_vencer'] }}
                            contrato{{ $notificacionesContratos['por_vencer'] > 1 ? 's' : '' }} por vencer
                            <span class="float-right text-muted text-sm">Revisar</span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif

                    @if ($notificacionesContratos['vencidos'] > 0)
                        <a href="{{ route('lqclientes.index') }}?estado=vencido" class="dropdown-item">
                            <i class="fas fa-times-circle text-danger mr-2"></i>
                            Hay {{ $notificacionesContratos['vencidos'] }}
                            contrato{{ $notificacionesContratos['vencidos'] > 1 ? 's' : '' }}
                            vencido{{ $notificacionesContratos['vencidos'] > 1 ? 's' : '' }}
                            <span class="float-right text-muted text-sm">Revisar</span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif

                    @if ($notificacionesContratos['total'] == 0)
                        <a href="#" class="dropdown-item text-muted">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            No hay notificaciones
                        </a>
                    @endif
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('lqclientes.index') }}" class="dropdown-item dropdown-footer">
                    Ver clientes
                </a>
            </div>
        </li>


        @if (Auth::user())
            @if (config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        @if (config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
