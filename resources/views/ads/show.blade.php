@extends('layouts.app')

@section('title', $ad->title)

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        @if($ad->images->count() > 0)
            @if($ad->images->count() === 1)
                <div class="ratio ratio-4x3 bg-light rounded shadow-sm overflow-hidden">
                    <img src="{{ asset($ad->images->first()->path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $ad->title }}">
                </div>
            @else
                @php($carouselId = 'adGallery' . $ad->id)
                <div id="{{ $carouselId }}" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($ad->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="ratio ratio-4x3 bg-light overflow-hidden">
                                    <img src="{{ asset($image->path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $ad->title }} — фото {{ $index + 1 }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Назад</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Вперёд</span>
                    </button>
                    <div class="carousel-indicators">
                        @foreach($ad->images as $index => $image)
                            <button type="button"
                                    data-bs-target="#{{ $carouselId }}"
                                    data-bs-slide-to="{{ $index }}"
                                    class="{{ $index === 0 ? 'active' : '' }}"
                                    @if($index === 0) aria-current="true" @endif
                                    aria-label="Фото {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="ratio ratio-4x3 bg-light rounded shadow-sm d-flex align-items-center justify-content-center">
                <span class="text-muted">Фотографии отсутствуют</span>
            </div>
        @endif
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">{{ $ad->title }}</h1>
                <div class="display-6 fw-semibold mb-3">{{ number_format($ad->price, 0, ',', ' ') }} ₽</div>

                <dl class="row small text-muted mb-4">
                    <dt class="col-sm-4">Опубликовано</dt>
                    <dd class="col-sm-8">{{ $ad->created_at->format('d.m.Y H:i') }}</dd>
                    <dt class="col-sm-4">Продавец</dt>
                    <dd class="col-sm-8">{{ $ad->user->name }}</dd>
                    <dt class="col-sm-4">Статус</dt>
                    <dd class="col-sm-8">
                        @switch($ad->status)
                            @case('published') Опубликовано @break
                            @case('moderation') На модерации @break
                            @case('rejected') Отклонено @break
                        @endswitch
                    </dd>
                </dl>

                <h2 class="h5">Описание</h2>
                <div class="ad-description fs-6">
                    {!! $ad->description !!}
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Вернуться к списку</a>
                    @auth
                        @if(auth()->id() === $ad->user_id)
                            <span class="badge bg-primary align-self-center">Это ваше объявление</span>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection