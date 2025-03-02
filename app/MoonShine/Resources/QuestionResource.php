<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Position;
use App\Models\ReviewMethod;
use App\Models\Team;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;

/**
 * @extends ModelResource<Question>
 */
class QuestionResource extends ModelResource
{
    protected string $model = Question::class;

    protected string $title = 'Вопросы';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(
                'Методология',
                'ReviewMethod',
                formatted: static fn(ReviewMethod $model) => $model->getName(),
                resource: ReviewMethodResource::class,
            )
                ->creatable()
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

            Select::make('Кого оценивает', 'target')->options([
                'company' => 'Компанию',
                'team' => 'Команду',
                'employee' => 'Сотрудника',
                'other' => 'Себя',
            ]),
            Select::make('Тип ответа', 'answer_type')->options([
                'text' => 'Текст',
                'number_5' => 'По 5 бальной шкале',
                'number_10' => 'По 10 бальной шкале',
            ]),
            Text::make('Вопрос', 'question'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),

                BelongsTo::make(
                    'Методология',
                    'ReviewMethod',
                    formatted: static fn(ReviewMethod $model) => $model->getName(),
                    resource: ReviewMethodResource::class,
                )
                    ->reactive()
                    ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),

                Select::make('Кого оценивает', 'target')->options([
                    'company' => 'Компанию',
                    'team' => 'Команду',
                    'employee' => 'Сотрудника',
                    'other' => 'Себя',
                ]),

                Select::make('Тип ответа', 'answer_type')->options([
                    'text' => 'Текст',
                    'number_5' => 'По 5 бальной шкале',
                    'number_10' => 'По 10 бальной шкале',
                ]),

                Text::make('Вопрос', 'question'),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
        ];
    }

    /**
     * @param Question $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'question' => ['required'],
            'answer_type' => ['required'],
            'target' => ['required'],
        ];
    }
}
