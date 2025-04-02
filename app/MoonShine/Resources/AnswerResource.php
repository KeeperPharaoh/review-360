<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Answer;
use App\Models\Event;
use App\Models\Question;
use App\MoonShine\Pages\Answer\AnswerIndexPage;
use App\MoonShine\Pages\Answer\AnswerFormPage;
use App\MoonShine\Pages\Answer\AnswerDetailPage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Fields\Select;

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

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        $companyId = Auth::user()->company_id;

        $eventIds = Event::query()
            ->where('company_id', '=', $companyId)
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();
        if (request()->has('event_id') && request()->input('event_id')) {
            $eventIds = [request()->input('event_id')];
        }
        $questionIds = Question::query()
            ->where('company_id', '=', $companyId)
            ->where('answer_type', '!=', 'text')
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        return $builder
            ->from('answers as answers') // Определяем 'a1' как основную таблицу
            ->whereIn('answers.event_id', $eventIds)
            ->whereIn('answers.question_id', $questionIds)
            ->leftJoin('answers as a2', function ($join) {
                $join->on('answers.assignment_id', '=', 'a2.assignment_id')
                    ->on('answers.event_id', '=', 'a2.event_id')
                    ->whereColumn('answers.question_id', '!=', 'a2.question_id')
                    ->orOn(function ($query) {
                        $query->whereNull('answers.assignment_id')
                            ->whereNull('a2.assignment_id')
                            ->on('answers.event_id', '=', 'a2.event_id')
                            ->on('answers.user_id', '=', 'a2.user_id')
                            ->whereColumn('answers.question_id', '!=', 'a2.question_id');
                    });
            })
            ->select([
                'answers.id',
                'answers.target_name',
                'answers.answer as answer_1',
                'a2.answer as answer_2',
            ]);
    }

    protected function search(): array
    {
        return [];
    }

//    protected function filters(): iterable
//    {
//        return [
//            Select::make('Кого оценили', 'target')->options([
//                'company' => 'Компанию',
//                'team' => 'Сотрудника',
//            ])->onApply(function ($query, $field) {
//                if ($field === 'company') {
//                    $questions = Question::query()
//                        ->where('company_id', '=', Auth::user()->company_id)
//                        ->where('target', '=', 'company')
//                        ->select('id')
//                        ->get()
//                        ->pluck('id')
//                        ->toArray();
//
//                    $query->whereIn('answers.question_id', $questions);
//                } elseif ($field === 'team') {
//                    $questions = Question::query()
//                        ->where('company_id', '=', Auth::user()->company_id)
//                        ->where('target', '=', 'employee')
//                        ->select('id')
//                        ->get()
//                        ->pluck('id')
//                        ->toArray();
//
//                    $query->whereIn('answers.question_id', $questions);
//                }
//            })->nullable(),
//
//            BelongsTo::make(
//                'Сотрудник',
//                'User',
//                formatted: static fn(\App\Models\User $model) => $model->first_name . ' ' . $model->last_name,
//                resource: UserResource::class,
//            )->nullable(),
//            BelongsTo::make(
//                'Мероприятие',
//                'Event',
//                formatted: static fn(Event $model) => $model->name,
//                resource: EventResource::class,
//            )->nullable(),
//            BelongsTo::make(
//                'Вопрос',
//                'Question',
//                formatted: static fn(Question $model) => $model->question,
//                resource: QuestionResource::class,
//            )->nullable(),
//        ];
//    }
}
