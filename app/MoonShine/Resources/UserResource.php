<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\MoonShine\Pages\Event\AnswerPage;
use App\MoonShine\Pages\Event\ReportPage;
use App\MoonShine\Pages\Event\UserMetaPage;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\MoonShine\Pages\User\UserIndexPage;
use App\MoonShine\Pages\User\UserFormPage;
use App\MoonShine\Pages\User\UserDetailPage;

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
        return [];
    }
}
