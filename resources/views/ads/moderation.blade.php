@extends('layouts.moderation')

@section('title', 'Модерация объявлений')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Модерация объявлений</h2>
            <p class="text-muted">Проверяйте новые объявления и решайте, стоит ли их публиковать.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($ads->count())
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Заголовок</th>
                    <th>Цена</th>
                    <th>Автор</th>
                    <th>Создано</th>
                    <th>Превью</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ads as $ad)
                    <tr>
                        <td>{{ $ad->id }}</td>
                        <td>{{ $ad->title }}</td>
                        <td>{{ number_format($ad->price, 0, '.', ' ') }} ₽</td>
                        <td>{{ $ad->user->name }}</td>
                        <td>{{ $ad->created_at->format('d.m.Y') }}</td>
                        <td>
                            @php($cover = $ad->coverImagePath())
                            @if($cover)
                                <img src="{{ asset($cover) }}" alt="Превью" style="max-width: 160px; border-radius: 6px;">
                            @else
                                <img src="https://via.placeholder.com/160x110?text=Объявление" alt="Превью" style="max-width: 160px; border-radius: 6px;">
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('moderation.approve', $ad) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button class="btn btn-sm btn-success" onclick="return confirm('Опубликовать объявление?')">Одобрить</button>
                            </form>
                            <form action="{{ route('moderation.reject', $ad) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Отклонить объявление?')">Отклонить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $ads->links() }}
        </div>
    @else
        <div class="alert alert-info">Новых объявлений на модерации нет.</div>
    @endif
</div>
@endsection