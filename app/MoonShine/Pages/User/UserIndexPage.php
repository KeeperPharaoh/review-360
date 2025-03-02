<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\User;

use App\Models\Position;
use App\Models\Team;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\PositionResource;
use App\MoonShine\Resources\TeamResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Models\MoonshineUserRole;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use Throwable;


class UserIndexPage extends IndexPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
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
                ->creatable()
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

            BelongsTo::make(
                'Должность',
                'Position',
                formatted: static fn(Position $model) => $model->getName(),
                resource: PositionResource::class,
            )
                ->creatable()
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

            Text::make(__('moonshine::ui.resource.name'), 'first_name'),
            Text::make('Фамилия', 'last_name'),
            Text::make('Номер телефона', 'phone_number'),
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
