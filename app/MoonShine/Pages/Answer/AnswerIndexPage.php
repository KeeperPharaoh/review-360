<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Answer;

use App\Models\Question;
use App\MoonShine\Resources\QuestionResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Throwable;

class AnswerIndexPage extends IndexPage
{
    protected bool $isAsync = false;

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Кого оценили', 'target_name'),

            BelongsTo::make(
                'Вопрос',
                'Question',
                formatted: static fn(Question $model) => $model->question,
                resource: QuestionResource::class,
            ),
            Text::make('Ответ', 'answer'),
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
