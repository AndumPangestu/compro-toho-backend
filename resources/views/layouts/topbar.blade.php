<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <ul class="navbar-nav ml-auto">
        <!-- Notifikasi -->
        <li class="nav-item mx-2">
            <a class="nav-link" href="{{ route('notifications.index') }}">
                <i class="fas fa-bell fa-fw"></i>
                @php
                $unreadCount = Auth::user()->unreadNotifications->count();
                @endphp
                @if ($unreadCount > 0)
                <span class="badge badge-danger badge-counter">{{ $unreadCount }}</span>
                @endif
            </a>
        </li>


        <!-- Profile -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}" alt="User Profile">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <p class="dropdown-item">
                    {{ Auth::user()->name }}
                </p>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
