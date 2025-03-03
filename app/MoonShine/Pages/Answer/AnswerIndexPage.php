<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Answer;

use App\Models\Question;
use App\Models\User;
use App\MoonShine\Resources\QuestionResource;
use App\MoonShine\Resources\UserResource;
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
            BelongsTo::make(
                 'Кого оценили',
                'User',
                formatted: static fn(User $model) => $model->first_name . ' ' . $model->last_name,
                resource: UserResource::class,
            ),
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
