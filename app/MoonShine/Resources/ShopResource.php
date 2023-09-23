<?php

namespace App\MoonShine\Resources;

use Faker\Provider\Text;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;

use MoonShine\Fields\BelongsToMany;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class ShopResource extends Resource
{
    public static string $model = Store::class;

    public static string $title = 'Stores';
    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected bool $showInModal = true;


    public function fields(): array
    {
        return [
            \MoonShine\Fields\Text::make('Название', 'name'),
            \MoonShine\Fields\Text::make('Адрес','address'),
            \MoonShine\Fields\Text::make('Описание', 'description'),
            BelongsToMany::make('Пользователы', 'users')->hideOnIndex()
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return ['name'];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
