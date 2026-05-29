<aside class="midi-sidebar">
    <div class="midi-brand">
        <img src="{{ asset('images/logo_midi.png') }}" alt="Logo Midi">
        <div>
            <h5>PT MIDI UTAMA</h5>
            <p>INDONESIA TBk</p>
        </div>
    </div>

    <nav class="midi-side-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard.lelang.detail') || (request()->routeIs('dashboard') && request('mode') !== 'riwayat' && !request()->routeIs('dashboard.penawaran-saya') && !request()->routeIs('dashboard.info-blacklist')) ? 'active' : '' }}"><i class="far fa-clone"></i> Dashboard</a>
        <!-- <a href="{{ route('tkd.index') }}" class="{{ request()->routeIs('tkd.index') ? 'active' : '' }}"><i class="far fa-file-alt"></i> Daftar Lelang</a> 
        <a href="{{ route('dashboard.penawaran-saya') }}" class="{{ request()->routeIs('dashboard.penawaran-saya') ? 'active' : '' }}"><i class="far fa-handshake"></i> Penawaran Saya</a> -->
        <a href="{{ route('dashboard', ['mode' => 'riwayat']) }}" class="{{ request()->routeIs('dashboard') && request('mode') === 'riwayat' ? 'active' : '' }}"><i class="far fa-clock"></i> Riwayat Lelang</a>
        <a href="{{ route('dashboard.info-blacklist') }}" class="{{ request()->routeIs('dashboard.info-blacklist') ? 'active' : '' }}"><i class="far fa-ban"></i> Info Blacklist</a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="far fa-user"></i> Profil Saya</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="far fa-sign-out-alt"></i> Logout</a>
    </nav>
</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>