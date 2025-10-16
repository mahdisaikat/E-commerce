<div class="sidebar sidebar-dark sidebar-fixed border-end {{ $configurations['collapse_sidebar'] === 'yes' ? 'sidebar-narrow-unfoldable' : '' }}"
    id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <img class="sidebar-brand-full" width="200" height="30" src="{{ $configurations['app_logo_link'] }}"
                alt="{{ $configurations['app_name'] }}" />
            <img class="sidebar-brand-narrow" width="30" height="30" src="{{ $configurations['app_favicon_link'] }}"
                alt="{{ $configurations['app_name'] }}" />
            {{-- <svg class="sidebar-brand-full" width="88" height="32" alt="CoreUI Logo">
                <use xlink:href="/backend/assets/brand/coreui.svg#full"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="32" height="32" alt="CoreUI Logo">
                <use xlink:href="/backend/assets/brand/coreui.svg#signet"></use>
            </svg> --}}
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark"
            aria-label="Close"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
    </div>

    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        @foreach ($menuData as $sidemenu)
        @if (!empty($sidemenu->route))
        {{-- Single menu item --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is($sidemenu->route . '*') ? 'active' : '' }}"
                href="{{ route($sidemenu->route) }}">
                <i class="nav-icon {{ $sidemenu->icon ?? 'fa-regular fa-circle-dot' }}"></i>
                &nbsp;<span>{{ $sidemenu->label }}</span>
            </a>
        </li>
        @else
        {{-- Menu with submenus --}}
        @php
        $isActive = false;
        foreach ($sidemenu->SubMenu as $submenu) {
        if (Route::is($submenu->route . '*')) {
        $isActive = true;
        break;
        }
        }
        @endphp

        <li class="nav-group {{ $isActive ? 'show' : '' }}">
            <a class="nav-link nav-group-toggle {{ $isActive ? '' : 'collapsed' }}" href="#">
                <i class="nav-icon {{ $sidemenu->icon ?? 'fa-regular fa-circle-dot' }}"></i>
                &nbsp;<span>{{ $sidemenu->label }}</span>
            </a>
            <ul class="nav-group-items {{ $isActive ? 'show' : 'compact' }}">
                @foreach ($sidemenu->SubMenu as $submenu)
                @if (in_array($submenu->permission_id, $userPermissions))
                <li>
                    <a class="nav-link {{ Route::is($submenu->route . '*') ? 'active' : '' }}"
                        href="{{ route($submenu->route) }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        <span>{{ $submenu->label }}</span>
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
        </li>
        @endif
        @endforeach
    </ul>

    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>