@extends('layout.main')

@section('content')
    <div class="card mt-3">
        <div class="card">
            <div class="card-header"><i class="fas fa-table mr-1"></i>Moje gry</div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Lp</th>
                            <th>Tytuł</th>
                            <th>Typ</th>
                            <th>Ocena</th>
                            <th>Twoja ocena</th>
                            <th>Opcje</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Lp</th>
                            <th>Tytuł</th>
                            <th>Typ</th>
                            <th>Ocena</th>
                            <th>Twoja ocena</th>
                            <th>Opcje</th>
                        </tr>
                        </tfoot>
                        <tbody>
                            @foreach($games ?? [] as $game)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $game->name }}</td>
                                    <td>{{ $game->genres->implode('name', ', ') ?? 'brak' }}</td>
                                    <td>{{ $game->score ?? 'brak' }}</td>
                                    <td>
                                        <form class="m-0" method="post" action="{{ route('me.games.rate') }}">
                                            @csrf
                                            <div class="form-row">
                                                <input type="hidden" name="gameId" value="{{ $game->id }}">
                                                <div class="col-auto">
                                                    <input
                                                        class="form-control mb-2"
                                                        placeholder="ocena"
                                                        type="number"
                                                        max="100"
                                                        min="1"
                                                        name="rate"
                                                        value="{{ $game->pivot->rate }}"
                                                    />
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-primary mb-2">Oceń</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('games.show', ['game' => $game->id]) }}">Szczegóły</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $games->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
