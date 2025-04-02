<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\Question;
use App\Models\Team;
use App\Models\User;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Alert;
use MoonShine\UI\Components\Components;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Content;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
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
            } elseif ($answer->answer >= 7) {
                $passives++;
            } else {
                $detractors++;
            }
        }

        $question = Question::query()
            ->where('target', '=', 'employee')
            ->where('answer_type', '=', 'number_5')
            ->first();

        $promotersEmployee = 0;
        $passivesEmployee = 0;
        $detractorsEmployee = 0;
        $users = User::all();

        foreach ($users as $user) {
            $assignment = Assignment::query()
                ->where('to_user_id', '=', $user->id)
                ->select(['id'])
                ->get()
                ->pluck('id')
                ->toArray();

            $answers =  Answer::query()
                ->where('question_id', '=', $question->id)
                ->whereIn('assignment_id', $assignment)
                ->where('answer', '!=', 'Не взаимодействуем')
                ->get();

            $total = 0;
            foreach ($answers as $answer) {
                try {
                    $total += $answer->answer;
                } catch (\Throwable $e) {
                }
            }

            $mid = $total / $answers->count();
            if ($mid >= 4) {
                $promotersEmployee++;
            } elseif ($mid >= 3) {
                $passivesEmployee++;
            } else {
                $detractorsEmployee++;
            }
        }


        $answers = Answer::query()->where('question_id', '=', 3)->get();
        $total = 0;
        foreach ($answers as $answer) {
            $total += $answer->answer;
        }

        $avgCompany = number_format($total / $answers->count(), 2);
        $enps = ($promoters - $detractors) / ($promoters + $detractors + $passives) * 100;
        $enps = number_format($enps, 2);

        return [
            Modal::make(
                'Данная функция еще недоступна',
            )->name('my-modal'),

            Modal::make(
                'Сравнение',
                '',
                ActionButton::make('Сравнить с другими', '#'),
                asyncUrl: '/api/one-to-one?event_id=' . $eventId
            ),

            Title::make('<br>'),

            Grid::make([
                ValueMetric::make('ENPS')
                    ->value(fn() => $enps)
                    ->columnSpan(6),

                ValueMetric::make('Ср. оценка компании')
                    ->value(fn() => $avgCompany)
                    ->columnSpan(6),
            ]),

            Title::make('<br>'),

            Grid::make([
                DonutChartMetric::make('Оценка компании')
                    ->values(['Промоутеры' => $promoters, 'Нейтралы' => $passives, 'Негативы' => $detractors])
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
