<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Answer;
use App\Models\Event;
use App\Models\Question;
use App\Models\Report;
use App\Models\Team;
use App\Models\User;
use App\Models\UserMeta;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;

#[\MoonShine\MenuManager\Attributes\SkipMenu]
class Dashboard extends Page
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
        return 'О компании';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $events = Event::all();
        $lineChartMetricData = [];

        foreach ($events as $event) {
            $questions = Question::query()
                ->where('target', '=', 'company')
                ->whereIn('answer_type', ['number_10'])
                ->get()
                ->pluck('id');

            $answers = Answer::query()
                ->where('event_id', '=', $event->id)
                ->whereIn('question_id', $questions)
                ->select(['answer'])
                ->get();

            $total = 0;
            foreach ($answers as $answer) {
                try {
                $total += $answer->answer;

                } catch (\Throwable) {

                }
            }
            $lineChartMetricData[(new \DateTime($event->end_at))->format('Y-m-d')] =
                number_format($answers->count() ? $total / $answers->count() : 0, 2);
        }

        return [
            \MoonShine\UI\Components\Layout\Body::make([
                LineChartMetric::make('Рейтинг')
                    ->line([
                        'Оценка' => $lineChartMetricData
                    ])->columnSpan(10),
            ]),

            Grid::make([
                ValueMetric::make('Сотрудников')
                    ->value(fn() => User::query()->count())
                    ->columnSpan(6),

                ValueMetric::make('Команд')
                    ->value(fn() => Team::query()->count())
                    ->columnSpan(6),
            ]),

            Grid::make([
                ValueMetric::make('Мероприятий')
                    ->value(fn() => Event::count())
                    ->columnSpan(6),

                ValueMetric::make('Отчетов')
                    ->value(fn() => Report::count() + UserMeta::query()->count())
                    ->columnSpan(6),
            ]),
        ];
    }
}
