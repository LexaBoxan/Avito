<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Avito Clone')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Пользовательский CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">Avito</a>

            <form class="d-flex ms-auto me-3" role="search" action="{{ route('home') }}">
                <input class="form-control me-2" type="search" name="q" value="{{ request('q') }}" placeholder="Поиск объявлений..." aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Найти</button>
            </form>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('ads.mine') }}">Мои объявления</a></li>
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Админка</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('moderation.index') }}">Модерация</a></li>
                    @elseif(auth()->user()->isModerator())
                        <li class="nav-item"><a class="nav-link" href="{{ route('moderation.index') }}">Модерация</a></li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-link nav-link">Выйти</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Войти</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Регистрация</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="text-center mt-5 mb-3 text-muted">
        <small>&copy; {{ date('Y') }} Avito Clone</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
