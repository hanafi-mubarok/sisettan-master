<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name') }} </title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- CSS Libraries -->
    @stack('customStyle')
    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="sidebar">
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                </form>
                <ul class="navbar-nav navbar-right">
                    @php
                        $currentUser = auth()->user();
                        $currentRole = strtolower((string) ($currentUser->role_name ?? ''));
                        $currentUserId = (int) ($currentUser->id ?? 0);

                        $notifications = collect();

                        if ($currentUser) {
                            $notificationsQuery = \Illuminate\Support\Facades\DB::table('notification');

                            if ($currentRole === 'user') {
                                $notificationsQuery->where('role', 'user')
                                    ->where('user_id', $currentUserId);
                            } else {
                                $notificationsQuery->where(function ($query) use ($currentRole, $currentUserId) {
                                    $query->where('role', $currentRole)
                                        ->orWhere('user_id', $currentUserId);
                                });
                            }

                            $notifications = $notificationsQuery
                                ->orderByRaw('CASE WHEN role = ? THEN 0 WHEN user_id = ? THEN 1 ELSE 2 END', [$currentRole, $currentUserId])
                                ->orderByDesc('created_at')
                                ->get();
                        }

                        $unreadNotificationCount = $notifications->where('is_read', 0)->count();

                        $formatNotificationTimestamp = function ($notification) {
                            $timestamp = $notification->read_at ?: $notification->created_at;

                            return $timestamp
                                ? \Illuminate\Support\Carbon::parse($timestamp)->format('d M Y H:i')
                                : '-';
                        };
                    @endphp

                    <li class="dropdown mr-2">
                        <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg nav-link-user position-relative">
                            <i class="far fa-bell"></i>
                            @if ($unreadNotificationCount > 0)
                                <span class="badge badge-danger" style="position: absolute; top: 6px; right: 2px; font-size: 10px;">
                                    {{ $unreadNotificationCount }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-list dropdown-menu-lg">
                            <div class="dropdown-header">Notifikasi</div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                @forelse ($notifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                        class="dropdown-item {{ $notification->is_read ? '' : 'dropdown-item-unread' }}">
                                        <div class="dropdown-item-icon bg-primary text-white">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div class="dropdown-item-desc">
                                            <strong>{{ $notification->judul }}</strong>
                                            <div class="text-muted text-small">{{ $notification->detail }}</div>
                                            <div class="text-muted text-small">
                                                {{ $notification->is_read ? 'Dibaca' : 'Belum dibaca' }}
                                                - {{ $formatNotificationTimestamp($notification) }}
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="dropdown-item text-center text-muted">Tidak ada notifikasi</div>
                                @endforelse
                            </div>
                        </div>
                    </li>

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar">
                <x-sidebar title="Test" />
                {{-- @include('layouts.sidebar') --}}
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
            <footer class="main-footer">
                @include('layouts.footer')
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="/assets/js/stisla.js"></script>

    <!-- JS Libraies -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="/assets/js/page/modules-sweetalert.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Template JS File -->
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/custom.js"></script>

    <!-- Page Specific JS File -->
    @stack('customScript')
</body>

</html>
