@extends('layouts.app')

@section('title', 'Создать объявление')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h3 mb-3">Новое объявление</h2>
                <p class="text-muted mb-4">Заполните все поля и добавьте несколько хороших фотографий, чтобы объявление выглядело привлекательнее.</p>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Заголовок</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Например: Продам велосипед" value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea id="description" name="description" rows="5" class="form-control" placeholder="Опишите товар подробнее..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Цена, ₽</label>
                            <input type="number" id="price" name="price" class="form-control" min="0" step="0.01" placeholder="Например: 1500" value="{{ old('price') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="images" class="form-label">Фотографии</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                            <div class="form-text">До 10 изображений в форматах JPG, PNG, GIF или WebP.</div>
                        </div>
                    </div>

                    <div class="d-grid d-sm-flex justify-content-sm-end gap-3 mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Отмена</a>
                        <button type="submit" class="btn btn-primary">Отправить на модерацию</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
