@extends('layouts.app')

@section('title', 'Мои объявления')

@section('content')
<h2 class="mb-4">Мои объявления</h2>

@if($ads->count())
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        @foreach($ads as $ad)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="ratio ratio-4x3 bg-light rounded-top overflow-hidden">
                        @php($cover = $ad->coverImagePath())
                        @if($cover)
                            <img src="{{ asset($cover) }}" class="w-100 h-100 object-fit-cover" alt="{{ $ad->title }}">
                        @else
                            <img src="https://via.placeholder.com/600x450?text=Нет+фото" class="w-100 h-100 object-fit-cover" alt="Нет фото">
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $ad->title }}</h5>
                        <p class="mb-2 text-muted" style="max-height: 4.5em; overflow: hidden;">{!! Str::limit(strip_tags($ad->description), 100) !!}</p>
                        <div class="fw-semibold mb-2">{{ number_format($ad->price, 0, ',', ' ') }} ₽</div>
                        <p class="mb-1"><strong>Статус:</strong>
                            @switch($ad->status)
                                @case('published') <span class="text-success">Опубликовано</span> @break
                                @case('moderation') <span class="text-warning">На модерации</span> @break
                                @case('rejected') <span class="text-danger">Отклонено</span> @break
                            @endswitch
                        </p>
                        <small class="text-muted">{{ $ad->created_at->format('d.m.Y H:i') }}</small>
                        <a href="{{ route('ads.show', $ad) }}" class="stretched-link mt-auto align-self-start">Открыть объявление</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $ads->links() }}
    </div>
@else
    <div class="alert alert-info">У вас пока нет объявлений. <a href="{{ route('ads.create') }}" class="alert-link">Создать?</a></div>
@endif
@endsection
