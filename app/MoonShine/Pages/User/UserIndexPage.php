<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\User;

use App\Models\Position;
use App\Models\Team;
use App\Models\User;
use App\MoonShine\Resources\PositionResource;
use App\MoonShine\Resources\TeamResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use App\MoonShine\BasePages\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Throwable;

class UserIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(
                'Отдел',
                'Team',
                formatted: static fn(Team $model) => $model->getName(),
                resource: TeamResource::class,
            )
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

            BelongsTo::make(
                'Должность',
                'Position',
                formatted: static fn(Position $model) => $model->getName(),
                resource: PositionResource::class,
            )
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

            Text::make('Имя', 'first_name')->setNameIndex('first_name'),
            Text::make('Фамилия', 'last_name')->sortable(),
            Text::make('Номер телефона', 'phone_number'),
            Text::make('Telegram', 'telegram_username'),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
