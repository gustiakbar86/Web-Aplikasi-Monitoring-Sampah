<!-- Sidebar -->
<aside class="col-md-3 d-none d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            @auth
                @if(auth()->user()->role == 1)
                    <!-- SuperAdmin Menu -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('superadmin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.laporan.*') ? 'active' : '' }}" 
                           href="{{ route('superadmin.laporan.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>Laporan
                        </a>
                    </li>
                @elseif(auth()->user()->role == 2)
                    <!-- Admin Menu -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" 
                           href="{{ route('admin.laporan.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>Laporan
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </div>
</aside>
