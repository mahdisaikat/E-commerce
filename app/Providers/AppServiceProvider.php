<?php

namespace App\Providers;

use App\Models\Configuration;
use App\Models\Sidebar;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Grant all permissions to systemadmin role
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('systemadmin')) {
                return true; // Allow all abilities if the user is a systemadmin
            }
        });

        // Define the menu-access gate
        Gate::define('menu-access', function ($user, $permission) {
            return $user->hasPermissionTo($permission);
        });
        
        if (config('app.env') === 'local')
        {
            Schema::defaultStringLength(191);
        }

        // Always share empty defaults so Blade won't throw undefined variable error
        View::share([
            'userPermissions' => [],
            'menuData' => collect(),
            'sidebar' => collect(),
        ]);

        // Share configurations (if table exists)
        $this->shareConfigurations();

        Event::listen(Authenticated::class, function ()
        {
            $this->shareUserSpecificData();
        });

        if (Auth::check())
        {
            $this->shareUserSpecificData();
        }
    }

    /**
     * Share configurations with all views.
     */
    protected function shareConfigurations(): void
    {
        if (!Schema::hasTable('configurations'))
        {
            View::share('configurations', collect());
            return;
        }

        $configurations = Cache::remember('configurations', config('session.lifetime', 120), function ()
        {
            return Configuration::where('status', 1)->get()->pluck('value', 'key');
        });

        View::share('configurations', $configurations);
    }

    /**
     * Share user-specific data (permissions and Sidebar) with views.
     */

    protected function shareUserSpecificData(): void
    {
        // Always default to empty
        $shared = [
            'menuData' => collect(),
            'userPermissions' => [],
        ];

        if (Auth::check())
        {
            $user = Auth::user();
            $cacheKey = "user_menu_data_{$user->id}";

            $shared = Cache::remember($cacheKey, config('session.lifetime', 120), function () use ($user)
            {
                $permissions = $user->hasRole('systemadmin')
                    ? Cache::rememberForever('all_permission_ids', fn() => Permission::pluck('id')->toArray())
                    : $user->getAllPermissions()->pluck('id')->toArray();

                $menuData = Sidebar::with([
                    'subMenu' => function ($q) use ($permissions)
                    {
                        $q->where('status', 1)
                            ->orderBy('serial', 'asc')
                            ->whereIn('permission_id', $permissions);
                    }
                ])
                    ->where('status', 1)
                    ->whereNull('parent_id')
                    ->orderBy('serial', 'asc')
                    ->whereIn('permission_id', $permissions)
                    ->orWhereHas('subMenu', fn($sub) => $sub->whereIn('permission_id', $permissions))
                    ->get();

                return [
                    'menuData' => $menuData,
                    'userPermissions' => $permissions,
                ];
            });
        }

        View::share($shared);
    }

}
