<div class="container-fluid border-bottom px-4">
    <button class="header-toggler" type="button"
        onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
        style="margin-inline-start: -14px;">
        <i class="fas fa-bars fa-lg"></i>
    </button>
    <ul class="header-nav d-none d-lg-flex">
        {{-- <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Settings</a></li> --}}
    </ul>
    <ul class="header-nav ms-auto">
        {{-- <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-bell fa-lg"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-list fa-lg"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-envelope-open fa-lg"></i></a></li> --}}
    </ul>

    <ul class="header-nav">
        <li class="nav-item py-1">
            <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
        </li>
        <li class="nav-item dropdown">
            <button class="btn" type="button" data-coreui-toggle="dropdown">
                <i id="theme-icon" class="fas fa-sun"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                <li>
                    <button class="dropdown-item d-flex align-items-center active" type="button"
                        data-coreui-theme-value="light">
                        <i class="fas fa-sun fa-lg me-3"></i>
                        Light
                    </button>
                </li>
                <li>
                    <button class="dropdown-item d-flex align-items-center" type="button"
                        data-coreui-theme-value="dark">
                        <i class="fas fa-moon fa-lg me-3"></i>
                        Dark
                    </button>
                </li>
                <li>
                    <button class="dropdown-item d-flex align-items-center" type="button"
                        data-coreui-theme-value="auto">
                        <i class="fas fa-circle-half-stroke fa-lg me-3"></i>
                        Auto
                    </button>
                </li>
            </ul>
        </li>
        <li class="nav-item py-1">
            <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
        </li>
        <li class="nav-item dropdown"><a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-md">
                    @php
                    $image = auth()->user()->image ? asset('storage/images/' . auth()->user()->image->type . '/' .
                    auth()->user()->image->filename) :
                    asset('/static_image/user.jpg');
                    @endphp
                    <img class="avatar-img" src="{{ $image }}" alt="">
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                    Account
                </div>
                <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-user me-2"></i> {{
                    auth()->user()->name }}</a>
                <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-envelope-open me-2"></i> {{
                    auth()->user()->email
                    }}</a>
                {{-- <a class="dropdown-item" href="#"><i class="fas fa-tasks me-2"></i> Tasks<span
                        class="badge badge-sm bg-danger ms-2">42</span></a> --}}
                {{-- <a class="dropdown-item" href="#"><i class="fas fa-comments me-2"></i> Comments<span
                        class="badge badge-sm bg-warning ms-2">42</span></a> --}}
                {{-- <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2">Settings</div>
                --}}
                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-cog me-2"></i> Profile</a>
                {{-- <a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a>
                <a class="dropdown-item" href="#"><i class="fas fa-credit-card me-2"></i> Payments<span
                        class="badge badge-sm bg-secondary ms-2">42</span></a>
                <a class="dropdown-item" href="#"><i class="fas fa-file-alt me-2"></i> Projects<span
                        class="badge badge-sm bg-primary ms-2">42</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"><i class="fas fa-lock me-2"></i> Lock Account</a> --}}
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </form>
            </div>
        </li>
    </ul>
</div>