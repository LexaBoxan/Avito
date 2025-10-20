@extends('layouts.app')

@section('title', 'Мои объявления')

@section('content')
<h2>Мои объявления</h2>

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
            <p style="margin-bottom: 0.5rem;"><strong>Цена:</strong> {{ $ad->price }} ₽</p>
            <p style="margin-bottom: 0.5rem;"><strong>Статус:</strong> 
                @switch($ad->status)
                    @case('published') <span style="color:green;">Опубликовано</span> @break
                    @case('moderation') <span style="color:orange;">На модерации</span> @break
                    @case('rejected') <span style="color:red;">Отклонено</span> @break
                @endswitch
            </p>
            <p style="font-size: 0.9rem; color: #777;">{{ $ad->created_at->format('d.m.Y H:i') }}</p>
        </article>
        @endforeach
    </div>

    <div style="margin-top: 2rem;">
        {{ $ads->links() }}
    </div>
@else
    <p>У вас пока нет объявлений. <a href="{{ route('ads.create') }}">Создать?</a></p>
@endif
@endsection
