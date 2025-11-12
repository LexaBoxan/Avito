@extends('layouts.guest')

@section('title', 'Регистрация')

@section('content')
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="/" class="h1"><b>Avito</b>Clone</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Создайте новый аккаунт</p>

      <form action="{{ route('register.store') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Имя" value="{{ old('name') }}" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Пароль" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" class="form-control" placeholder="Повторите пароль" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <a href="{{ route('login') }}">Уже есть аккаунт?</a>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection