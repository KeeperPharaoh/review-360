<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Assignment;

use App\Models\ReviewMethod;
use App\Models\User;
use App\MoonShine\Resources\ReviewMethodResource;
use App\MoonShine\Resources\UserResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Fields\ID;
use Throwable;


class AssignmentFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(
                'Методология',
                'ReviewMethod',
                formatted: static fn(ReviewMethod $model) => $model->getName(),
                resource: ReviewMethodResource::class,
            )
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

            BelongsTo::make(
                'Кто оценивает',
                'FromUser',
                formatted: static fn(User $model) => $model->first_name . ' ' . $model->last_name,
                resource: UserResource::class,
            )
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'first_name', 'last_name'])),

            BelongsTo::make(
                'Кого',
                'ToUser',
                formatted: static fn(User $model) => $model->first_name  . ' ' . $model->last_name,
                resource: UserResource::class,
            )
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'first_name', 'last_name'])),
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
