<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
    <span class="brand-text font-weight-light">Avito Admin</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Панель</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.users') }}" class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Пользователи</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>