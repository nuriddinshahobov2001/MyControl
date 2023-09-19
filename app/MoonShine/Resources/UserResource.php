<?php

namespace App\MoonShine\Resources;

use Faker\Guesser\Name;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Fields\Phone;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class UserResource extends Resource
{
	public static string $model = User::class;

	public static string $title = 'Users';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Text::make('Имя', 'name' ),
            Text::make('Фамилие', 'lastname'),
            Text::make('Логин', 'login'),
            Text::make('Пароль', 'password'),
            Phone::make('Телефон', 'phone'),
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
