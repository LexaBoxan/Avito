@extends('layouts.app')

@section('title', 'Создать объявление')

@section('content')
<hgroup style="margin-bottom: 2rem;">
    <h2>Новое объявление</h2>
    <h3>Заполните поля ниже</h3>
</hgroup>

@if(session('success'))
    <article style="background-color: #e6ffed; padding: 1rem; border-radius: 5px;">
        {{ session('success') }}
    </article>
@endif

@if($errors->any())
    <article style="background-color: #ffe6e6; padding: 1rem; border-radius: 5px;">
        <ul style="margin: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </article>
@endif

<form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label for="title">Заголовок</label>
    <input type="text" id="title" name="title" placeholder="Например: Продам велосипед" value="{{ old('title') }}" required>

    <label for="description">Описание</label>
    <textarea id="description" name="description" rows="5" placeholder="Опишите товар подробнее..." required>{{ old('description') }}</textarea>

    <label for="price">Цена, ₽</label>
    <input type="number" id="price" name="price" step="0.01" placeholder="Например: 1500" value="{{ old('price') }}" required>

    <label for="images">Фотографии</label>
    <input type="file" name="images[]" id="images" multiple accept="image/*">


    <button type="submit" class="contrast" style="margin-top: 1rem;">Опубликовать</button>
</form>
@endsection
