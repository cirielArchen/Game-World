<a class="nav-link" href="{{ route('home.mainPage') }}">
    <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
    Panel
</a>

<div class="sb-sidenav-menu-heading">Konto</div>
<nav class="sb-sidenav-menu-nested">
    <a class="nav-link" href="{{ route('me.profile') }}">Mój profil</a>
    <a class="nav-link" href="{{ route('me.games.list') }}">Gry</a>
</nav>

<div class="sb-sidenav-menu-heading">Gry</div>
<nav class="sb-sidenav-menu-nested">
    <a class="nav-link" href="{{ route('games.dashboard') }}">Dashboard</a>
    <a class="nav-link" href="{{ route('games.list') }}">Katalog</a>
</nav>

@can('admin-level')
    <div class="sb-sidenav-menu-heading">Admin Panel</div>
    <nav class="sb-sidenav-menu-nested">
        <a class="nav-link" href="{{ route('get.users') }}">Użytkownicy</a>
    </nav>
@endcan
