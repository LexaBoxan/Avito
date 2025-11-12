<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('moderation.index') }}" class="nav-link">Модерация</a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <span class="nav-link">{{ auth()->user()->name }}</span>
    </li>
    <li class="nav-item">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-link nav-link" style="display:inline; padding: 0;">Выйти</button>
      </form>
    </li>
  </ul>
</nav>