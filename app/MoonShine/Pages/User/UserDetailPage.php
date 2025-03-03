<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\User;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\Question;
use App\Models\User;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Content;
use MoonShine\UI\Components\Modal;
use Throwable;
use MoonShine\Apexcharts\Components\LineChartMetric;

class UserDetailPage extends DetailPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        /** @var User $user */
        $user = $this->getResource()->getItem();
        $events = Event::query()
            ->where('end_at', '<=', now())
            ->get();
        $lineChartMetricData = [];

        foreach ($events as $event) {
            $assignments = Assignment::query()
                ->where('to_user_id', '=', $user->id)
                ->select(['id'])
                ->get()
                ->pluck('id');
            $questions = Question::query()
                ->where('target', '=', 'employee')
                ->whereIn('answer_type', ['number_5', 'number_10'])
                ->get()
                ->pluck('id');

            $answers = Answer::query()
                ->where('event_id', '=', $event->id)
                ->whereIn('question_id', $questions)
                ->whereIn('assignment_id', $assignments)
                ->select(['answer'])
                ->get();
            $total = 0;
            foreach ($answers as $answer) {
                $total += $answer->answer;
            }
            $lineChartMetricData[(new \DateTime($event->end_at))->format('Y-m-d')] =  $answers->count() ? $total / $answers->count() : 0;
        }

        return [
            LineChartMetric::make('Ср. Оценка')
                ->line([
                    'Count' => $lineChartMetricData
                ])->columnSpan(5),

            Modal::make(
                'Заголовок',
                'Содержимое',
            )->name('my-modal'),

            ActionButton::make('Сгенерировать вопросы для one-to-one')->toggleModal('my-modal'),
            ActionButton::make('Добавить заметку')->toggleModal('my-modal'),
            ActionButton::make('Обновить отчет об сотрудники')->toggleModal('my-modal'),
            ActionButton::make('Показать динамику ответов')->toggleModal('my-modal'),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            Content::make([
                Box::make('Общая характеристика', ['Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.'])
            ]),
        ];
    }
}
