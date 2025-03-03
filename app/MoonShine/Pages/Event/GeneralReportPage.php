<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Event;
use App\Models\Question;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Alert;
use MoonShine\UI\Components\Components;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Content;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\Title;

class GeneralReportPage extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Общий отчет';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $eventId = request()->input('event_id') ?? 1;
        $event = Event::query()->findOrFail($eventId);

        $question = Question::query()
            ->where('target', '=', 'company')
            ->where('answer_type', '=', 'number_10')
            ->first();

        $answers = Answer::query()
            ->where('question_id', '=', $question->id)
            ->where('event_id', '=', $event->id)
            ->get();
        $promoters = 0;
        $passives = 0;
        $detractors = 0;

        foreach ($answers as $answer) {
            if ($answer->answer >= 9) {
                $promoters++;
            } elseif ($answer->answer >= 6) {
                $passives++;
            } else {
                $detractors++;
            }
        }

        $question = Question::query()
            ->where('target', '=', 'employee')
            ->where('answer_type', '=', 'number_5')
            ->first();
        $answers = Answer::query()
            ->where('question_id', '=', $question->id)
            ->where('event_id', '=', $event->id)
            ->limit(41)
            ->get();

        $promotersEmployee = 0;
        $passivesEmployee = 0;
        $detractorsEmployee = 0;

        foreach ($answers as $answer) {
            if ($answer->answer >= 5) {
                $promotersEmployee++;
            } elseif ($answer->answer >= 4) {
                $passivesEmployee++;
            } else {
                $detractorsEmployee++;
            }
        }

        return [

            Modal::make(
                'Данная функция еще недоступна',
            )->name('my-modal'),

            Modal::make(
                'Сравнить с другими',
                '',
                ActionButton::make('Сгенерировать вопросы для one-to-one', '#'),
                asyncUrl: '/api/one-to-one?event_id=' . $eventId
            ),

            Grid::make([
                DonutChartMetric::make('Оценка компании')
                    ->values(['Промоутеры' => $promoters, 'Нейтралы' => $passives, 'Негативы' => $detractors])
                    ->columnSpan(6),
                DonutChartMetric::make('Сотрудники')
                    ->values(['Промоутеры' => $promotersEmployee, 'Нейтралы' => $passivesEmployee, 'Негативы' => $detractorsEmployee])
                    ->columnSpan(6),
            ]),


            Content::make([
                Box::make('Отчет о компании', [$event->report])
            ]),
            Content::make([
                Box::make('Общий отчет о состояние отделов', [$event->team_report])
            ])
        ];
    }
}
