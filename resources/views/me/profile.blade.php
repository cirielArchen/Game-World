@extends('layout.main')

@section('content')
    <div class="card mt-3">
        <h5 class="card-header">{{ $user->name }}</h5>
        <div class="card-body">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded mx-auto d-block mt-3 user-avatar">
            @else
                <img src="{{ asset('images/avatar.png') }}" class="rounded mx-auto d-block mt-3 user-avatar">
            @endif
            <ul class="mt-3">
                <li>Nazwa: {{ $user->name }}</li>
                <li>Email: {{ $user->email }}</li>
                <li>Telefon: {{ $user->phone }}</li>
            </ul>

            <a href=" {{ route('me.edit') }}" class="btn btn-light">Edytuj dane</a>
        </div>
@endsection
