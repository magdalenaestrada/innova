<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span id="notif-badge"
              class="badge badge-danger navbar-badge"
              style="display:none;">0</span>
    </a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">Notificaciones</span>

        <div id="notif-items"></div>

        <div class="dropdown-divider"></div>
        <a href="{{ route('lqclientes.index') }}" class="dropdown-item dropdown-footer">
            Ver clientes
        </a>
    </div>
</li>
