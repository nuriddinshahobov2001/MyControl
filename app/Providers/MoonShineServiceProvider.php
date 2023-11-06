<?php

namespace App\Providers;

use App\MoonShine\Resources\ShopResource;
use App\MoonShine\Resources\StoresResource;

use App\MoonShine\Resources\UserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuItem::make('Пользователы', new UserResource())->icon('users'),
//            MenuItem::make('Магазины', new ShopResource())->icon('users'),
        ]);
    }
}
