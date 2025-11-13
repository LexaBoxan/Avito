@extends('layouts.app')

@section('title', 'Объявления')

@section('content')
<h2 class="mb-4">Свежие объявления</h2>
@if(request('q'))
    <p class="text-muted">Результаты по запросу «{{ request('q') }}»</p>
@endif

@if($ads->count())
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($ads as $ad)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="ratio ratio-4x3 bg-light rounded-top overflow-hidden">
                        @php($cover = $ad->coverImagePath())
                        @if($cover)
                            <img src="{{ asset($cover) }}" class="w-100 h-100 object-fit-cover" alt="{{ $ad->title }}">
                        @else
                            <img src="https://via.placeholder.com/600x450?text=Объявление" class="w-100 h-100 object-fit-cover" alt="Обложка объявления">
                        @endif
                    </div>
                    <div class="card-body position-relative d-flex flex-column">
                        <h5 class="card-title text-truncate">{{ $ad->title }}</h5>
                        <p class="card-text text-muted flex-grow-1" style="max-height: 4.5em; overflow: hidden;">{!! Str::limit(strip_tags($ad->description), 100) !!}</p>
                        <div class="fw-semibold">{{ number_format($ad->price, 0, ',', ' ') }} ₽</div>
                        <small class="text-muted">{{ $ad->created_at->format('d.m.Y H:i') }}</small>
                        <a href="{{ route('ads.show', $ad) }}" class="stretched-link" aria-label="Перейти к объявлению {{ $ad->title }}"></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $ads->links() }}
    </div>
@else
    <div class="alert alert-info">Пока нет опубликованных объявлений.</div>
@endif
@endsection
