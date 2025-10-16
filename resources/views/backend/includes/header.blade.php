<div class="container-fluid px-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-house-chimney"></i>
                </a>
            </li>
            <li class="breadcrumb-item active">
                <span>
                    {{ !empty($title) ? $title : ucfirst(explode('.', Route::currentRouteName())[0]) }}
                </span>
            </li>
            {{-- @if (!empty($menuData))
                @foreach ($menuData as $menu)
                    @php
                        $routesArray = optional($menu->subMenu)->pluck('route')->toArray() ?? [];
                        $isActive = in_array(Route::current()->getName(), $routesArray);
                    @endphp
                    @if ($isActive)
                        <li class="breadcrumb-item">{{ $menu->label }}</li>
                        @if (!empty($menu->subMenu))
                            @foreach ($menu->subMenu as $subMenu)
                                @if (Route::current()->getName() == $subMenu->route)
                                    <li class="breadcrumb-item active">
                                        <strong>
                                            {{ $subMenu->label }}
                                        </strong>
                                    </li>
                                    @break
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
            @endif --}}
        </ol>
    </nav>
</div>