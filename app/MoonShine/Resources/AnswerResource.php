<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Answer;
use App\MoonShine\Pages\Answer\AnswerIndexPage;
use App\MoonShine\Pages\Answer\AnswerFormPage;
use App\MoonShine\Pages\Answer\AnswerDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
 * @extends ModelResource<Answer, AnswerIndexPage, AnswerFormPage, AnswerDetailPage>
 */
class AnswerResource extends ModelResource
{
    protected string $model = Answer::class;

    protected string $title = 'Ответы';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            AnswerIndexPage::class,
            AnswerFormPage::class,
            AnswerDetailPage::class,
        ];
    }

    /**
     * @param Answer $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
