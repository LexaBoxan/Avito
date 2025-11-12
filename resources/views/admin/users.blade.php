@extends('layouts.admin')
@section('title', 'Пользователи')
@section('content')

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
  <div class="card-header"><h3 class="card-title">Список пользователей</h3></div>
  <div class="card-body">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Имя</th>
          <th>Email</th>
          <th>Роль</th>
          <th>Регистрация</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @if($user->role === 'admin')
                <span class="badge badge-success">Администратор</span>
                @elseif($user->role === 'moderator')
                <span class="badge badge-info">Модератор</span>
                @else
                <span class="badge badge-secondary">Пользователь</span>
                @endif
            </td>
            <td>{{ $user->created_at->format('d.m.Y') }}</td>
            <td>
              <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                    @csrf
                    <select name="role" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Пользователь</option>
                        <option value="moderator" {{ $user->role === 'moderator' ? 'selected' : '' }}>Модератор</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Администратор</option>
                    </select>
                </form>

              <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')">Удалить</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection