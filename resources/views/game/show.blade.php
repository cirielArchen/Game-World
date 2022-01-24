@extends('layout.main')

@section('content')
<div class="card">
    @if(!empty($game))
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h5 class="card-header">
            {{ $game->name }}
            @if ($userHasGame)
            <form class="float-right m-0" method="post" action="{{ route('me.games.remove') }}">
                @method('delete')
                @csrf
                <div class="form-row">
                    <input type="hidden" name="gameId" value="{{ $game->id }}">
                    <button type="submit" class="btn btn-primary mb-2">Usuń z listy gier</button>
                </div>
            </form>
            @else
                <form class="float-right m-0" method="post" action="{{ route('me.games.add') }}">
                    @csrf
                    <div class="form-row">
                        <input type="hidden" name="gameId" value="{{ $game->id }}">
                        <button type="submit" class="btn btn-primary mb-2">Dodaj do mojej listy</button>
                    </div>
                </form>
            @endif
        </h5>
        <div class="card-body">
            <ul>
                <li>Id: {{ $game->id }}</li>
                <li>Nazwa: {{ $game->name }}</li>
                <li>Wydawca: {{ $game->publishers->implode('name', ', ') ?? 'brak' }}</li>
                <li>Gatunek:{{ $game->genres->implode('name', ', ') ?? 'brak'}}</li>
            </ul>
            <div class="my-4">
                <h4>Krótki opis</h3>
                <div class="mx-2">{!! $game->short_description !!}</div>
            </div>

            <div class="my-4">
                <h4>Opis</h3>
                <div class="mx-2">{!! $game->description !!}</div>
            </div>

            <div class="my-4">
                <h4>About</h3>
                <div class="mx-2">{!! $game->about !!}</div>
            </div>

            <a href="{{ route('games.list') }}" class="btn btn-light">Lista gier</a>
        </div>
    @else
        <h5 class="card-header">Brak danych do wyświetlenia</h5>
    @endif
</div>
@endsection
