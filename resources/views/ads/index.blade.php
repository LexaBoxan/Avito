@extends('layouts.app')

@section('title', 'Объявления')

@section('content')
<h2>Все объявления</h2>

@if($ads->count())
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
        @foreach($ads as $ad)
        <article style="border: 1px solid #eee; padding: 1rem; border-radius: 8px;">
            @if ($ad->image)
                <img src="{{ asset($ad->image) }}" alt="Фото" style="width: 100%;">
            @else
                <img src="https://via.placeholder.com/300x200?text=Нет+фото" alt="Нет фото" style="width: 100%;">
            @endif


            <h3>{{ $ad->title }}</h3>
            <p><strong>Цена:</strong> {{ $ad->price }} ₽</p>
            <p style="font-size: 0.9rem; color: #777;">{{ $ad->created_at->format('d.m.Y H:i') }}</p>

            {{-- Опционально: ссылка на деталку --}}
            {{-- <a href="{{ route('ads.show', $ad) }}" class="secondary">Подробнее</a> --}}
        </article>
        @endforeach
    </div>

    <div style="margin-top: 2rem;">
        {{ $ads->links() }}
    </div>
@else
    <p>Пока нет опубликованных объявлений.</p>
@endif
@endsection
