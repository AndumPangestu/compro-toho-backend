<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('img/logo-toho.png') }}" class="img-fluid" alt="">
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Management</div>

    <!-- Banner -->
    <li class="nav-item {{ request()->routeIs('banners.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('banners.index') }}">
            <i class="fas fa-fw fa-image"></i>
            <span>Banner</span>
        </a>
    </li>

    <!-- Articles with Submenu -->
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('articles.index') || request()->routeIs('article-categories.index') ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseArticles"
            aria-expanded="{{ request()->routeIs('articles.index') || request()->routeIs('article-categories.index') ? 'true' : 'false' }}"
            aria-controls="collapseArticles">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>Articles</span>
        </a>
        <div id="collapseArticles"
            class="collapse {{ request()->routeIs('articles.index') || request()->routeIs('article-categories.index') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="collapse-inner">
                <a class="collapse-item {{ request()->routeIs('articles.index') ? 'active' : '' }}"
                    href="{{ route('articles.index') }}">All Articles</a>
                <a class="collapse-item {{ request()->routeIs('article-categories.index') ? 'active' : '' }}"
                    href="{{ route('article-categories.index') }}">Article Categories</a>
            </div>
        </div>
    </li>

    <!-- User Management with Submenu -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('users.index') || request()->routeIs('admins.index') || (auth()->user()->role === 'superadmin' && request()->routeIs('superadmins.index')) ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseUsers"
            aria-expanded="{{ request()->routeIs('users.index') || request()->routeIs('admins.index') || (auth()->user()->role === 'superadmin' && request()->routeIs('superadmins.index')) ? 'true' : 'false' }}"
            aria-controls="collapseUsers">
            <i class="fas fa-fw fa-users"></i>
            <span>User Management</span>
        </a>
        <div id="collapseUsers"
            class="collapse {{ request()->routeIs('users.index') || request()->routeIs('admins.index') || (auth()->user()->role === 'superadmin' && request()->routeIs('superadmins.index')) ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="collapse-inner">
                <a class="collapse-item {{ request()->routeIs('admins.index') ? 'active' : '' }}"
                    href="{{ route('admins.index') }}">Admin</a>

                @if (auth()->user()->role === 'superadmin')
                    <a class="collapse-item {{ request()->routeIs('superadmins.index') ? 'active' : '' }}"
                        href="{{ route('superadmins.index') }}">Super Admin</a>
                @endif
            </div>
        </div>
    </li>


    <!-- Partner -->
    <li class="nav-item {{ request()->routeIs('partners.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('partners.index') }}">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Partner</span>
        </a>
    </li>

    <!-- Team -->
    <li class="nav-item {{ request()->routeIs('teams.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('teams.index') }}">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Team</span>
        </a>
    </li>
    <!-- Service -->
    <li class="nav-item {{ request()->routeIs('services.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('services.index') }}">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Service</span>
        </a>
    </li>

    <!-- FAQ -->
    <li class="nav-item {{ request()->routeIs('faqs.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('faqs.index') }}">
            <i class="fas fa-fw fa-question-circle"></i>
            <span>FAQ</span>
        </a>
    </li>
    <!-- Office Location -->
    <li class="nav-item {{ request()->routeIs('office-locations.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('office-locations.index') }}">
            <i class="fas fa-fw fa-question-circle"></i>
            <span>Office Location</span>
        </a>
    </li>

    <!-- Social Media -->
    <li class="nav-item {{ request()->routeIs('social-media.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('social-media.index') }}">
            <i class="fas fa-fw fa-question-circle"></i>
            <span>Social Media</span>
        </a>
    </li>

    <!-- Donations with Submenu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('donations.index') || request()->routeIs('donation-categories.index') ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseDonations"
            aria-expanded="{{ request()->routeIs('donations.index') || request()->routeIs('donation-categories.index') ? 'true' : 'false' }}"
            aria-controls="collapseDonations">
            <i class="fas fa-fw fa-donate"></i>
            <span>Donations</span>
        </a>
        <div id="collapseDonations"
            class="collapse {{ request()->routeIs('donations.index') || request()->routeIs('donation-categories.index') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="collapse-inner">
                <a class="collapse-item {{ request()->routeIs('donations.index') ? 'active' : '' }}"
                    href="{{ route('donations.index') }}">Donation List</a>
                <a class="collapse-item {{ request()->routeIs('donation-categories.index') ? 'active' : '' }}"
                    href="{{ route('donation-categories.index') }}">Donation Categories</a>
            </div>
        </div>
    </li> --}}

    <!-- Testimonials -->
    <li class="nav-item {{ request()->routeIs('testimonials.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('testimonials.index') }}">
            <i class="fas fa-fw fa-comment-alt"></i>
            <span>Testimonials</span>
        </a>
    </li>

    {{--
    <!-- Transactions -->
    <li class="nav-item {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('transactions.index') }}">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Transactions</span>
        </a>
    </li>


    <!-- Reports with Submenu -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('annual-reports.index') || request()->routeIs('financial-reports.index') || request()->routeIs('monthly-reports.index') || request()->routeIs('reports.index') ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseReports"
            aria-expanded="{{ request()->routeIs('annual-reports.index') || request()->routeIs('financial-reports.index') || request()->routeIs('monthly-reports.index') || request()->routeIs('reports.index') ? 'true' : 'false' }}"
            aria-controls="collapseReports">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Reports</span>
        </a>
        <div id="collapseReports"
            class="collapse {{ request()->routeIs('annual-reports.index') || request()->routeIs('financial-reports.index') || request()->routeIs('monthly-reports.index') || request()->routeIs('reports.index') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="collapse-inner">
                <a class="collapse-item {{ request()->routeIs('annual-reports.index') ? 'active' : '' }}"
                    href="{{ route('annual-reports.index') }}">Annual Reports</a>
                <a class="collapse-item {{ request()->routeIs('financial-reports.index') ? 'active' : '' }}"
                    href="{{ route('financial-reports.index') }}">Financial Reports</a>
                <a class="collapse-item {{ request()->routeIs('monthly-reports.index') ? 'active' : '' }}"
                    href="{{ route('monthly-reports.index') }}">Monthly Reports</a>
                <a class="collapse-item {{ request()->routeIs('reports.index') ? 'active' : '' }}"
                    href="{{ route('reports.index') }}">General Reports</a>
            </div>
        </div>
    </li>

    <!-- Broadcasts -->
    <li class="nav-item {{ request()->routeIs('broadcasts.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('broadcasts.index') }}">
            <i class="fas fa-fw fa-broadcast-tower"></i>
            <span>Broadcasts</span>
        </a>
    </li> --}}


    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
