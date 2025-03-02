<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\User;

use App\Models\Company;
use App\Models\Position;
use App\Models\Team;
use App\MoonShine\Resources\CompanyResource;
use App\MoonShine\Resources\PositionResource;
use App\MoonShine\Resources\TeamResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Throwable;


class UserFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Flex::make([
                Text::make(__('moonshine::ui.resource.name'), 'first_name')->required(),
                Text::make('Фамилия', 'last_name')->required(),
                Text::make('Отчество', 'mid_name'),
            ]),
            Flex::make([
                Text::make('Номер телефона', 'phone_number'),
                Text::make('Telegram', 'telegram_username'),
            ]),

            Flex::make([
                BelongsTo::make(
                    'Отдел',
                    'Team',
                    formatted: static fn(Team $model) => $model->getName(),
                    resource: TeamResource::class,
                )
                    ->reactive()
                    ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

                BelongsTo::make(
                    'Должность',
                    'Position',
                    formatted: static fn(Position $model) => $model->getName(),
                    resource: PositionResource::class,
                )
                    ->reactive()
                    ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),
            ]),
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
