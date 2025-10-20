@extends('layouts.guest')

@section('title', 'Вход')

@section('content')
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="/" class="h1"><b>Avito</b>Clone</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Войти в аккаунт</p>

      <form action="{{ route('login.attempt') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
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

        <div class="row">
          <div class="col-8">
            <a href="{{ route('register') }}">Нет аккаунта?</a>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Войти</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
