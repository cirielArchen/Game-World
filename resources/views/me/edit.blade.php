@extends('layout.main')

@section('content')
    <div class="card mt-3">
        <h5 class="card-header">{{ $user->name }}</h5>
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

            @if ($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded mx-auto d-block mt-3" height="300px">
            @else
                <img src="{{ asset('images/avatar.png') }}" class="rounded mx-auto d-block mt-3" height="300px">
            @endif

            <form name="update" action="{{ route('me.update') }}" method="POST" role="form" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <!-- X-XSRF-TOKEN -->
                <div class="row">
                    <div class="form-group">
                        <label for="avatar" class="form-label">Wybierz avatar...</label>
                        <input
                            type="file"
                            class="form-control-file @error('avatar') is-invalid @enderror"
                            name="avatar"
                            value="avatar"
                        />
                        @error('avatar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="defaultAvatar"
                            value="defaultAvatar"
                            id="defaultAvatar"
                        />
                        <label class="form-check-label" name="defaultAvatar" for="defaultAvatar">
                            Domyślny avatar
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                    />
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                    />
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone</label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                    />
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
