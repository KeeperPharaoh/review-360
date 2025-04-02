<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Position;
use App\Models\Team;
use App\MoonShine\Pages\Event\AnswerPage;
use App\MoonShine\Pages\Event\ReportPage;
use App\MoonShine\Pages\Event\UserMetaPage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\User;
use App\MoonShine\Pages\User\UserIndexPage;
use App\MoonShine\Pages\User\UserFormPage;
use App\MoonShine\Pages\User\UserDetailPage;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
 * @extends ModelResource<User, UserIndexPage, UserFormPage, UserDetailPage>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Сотрудники';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            UserIndexPage::class,
            UserFormPage::class,
            UserDetailPage::class,
            ReportPage::class,
            AnswerPage::class,
            UserMetaPage::class,
        ];
    }

    /**
     * @param User $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [

        ];
    }

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder->where('company_id', '=', Auth::user()->company_id);
    }

    protected function search(): array
    {
        return ['first_name', 'mid_name', 'last_name'];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make(
                'Отдел',
                'Team',
                formatted: static fn(Team $model) => $model->getName(),
                resource: TeamResource::class,
            )
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name']))->nullable(),

            BelongsTo::make(
                'Должность',
                'Position',
                formatted: static fn(Position $model) => $model->getName(),
                resource: PositionResource::class,
            )
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name']))->nullable(),
        ];
    }
}
