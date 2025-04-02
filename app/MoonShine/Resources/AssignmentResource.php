<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;
use App\MoonShine\Pages\Assignment\AssignmentIndexPage;
use App\MoonShine\Pages\Assignment\AssignmentFormPage;
use App\MoonShine\Pages\Assignment\AssignmentDetailPage;

use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
 * @extends ModelResource<Assignment, AssignmentIndexPage, AssignmentFormPage, AssignmentDetailPage>
 */
class AssignmentResource extends ModelResource
{
    protected string $model = Assignment::class;

    protected string $title = 'Матрица оценок';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            AssignmentIndexPage::class,
            AssignmentFormPage::class,
        ];
    }

    /**
     * @param Assignment $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
