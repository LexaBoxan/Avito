@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<h2 class="mb-4">Объявления</h2>

<div class="container mt-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($ads as $ad)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if ($ad->images->count())
                        <img src="{{ asset($ad->images->first()->path) }}" class="card-img-top" alt="Фото">
                    @else
                        <img src="https://via.placeholder.com/400x200?text=Нет+фото" class="card-img-top" alt="Нет фото">
                    @endif


                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate">{{ $ad->title }}</h5>
                        <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">{{ $ad->description }}</p>

                        <p class="mt-auto fw-bold">{{ number_format($ad->price, 2, ',', ' ') }} ₽</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<!-- Пагинация -->
<div class="mt-4">
    {{ $ads->links() }}
</div>
@endsection
