<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Answer;
use App\Models\Event;
use App\Models\Question;
use App\MoonShine\BasePages\Page;
use DateTime;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\MenuManager\Attributes\SkipMenu;
use MoonShine\UI\Components\Layout\Body;

#[SkipMenu]
class Dashboard extends Page
{
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

    protected function components(): iterable
    {
        $events = Event::query()
            ->where('company_id', $this->getCompany()->getId())
            ->get();

        $lineChartMetricData = [];

        foreach ($events as $event) {
            $questions = Question::query()
                ->where('company_id', $this->getCompany()->getId())
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
            $lineChartMetricData[(new DateTime($event->end_at))->format('Y-m-d')] =
                number_format($answers->count() ? $total / $answers->count() : 0, 2);
        }

        return [
            Body::make([
                LineChartMetric::make('Рейтинг')
                    ->line([
                        'Оценка' => $lineChartMetricData
                    ])->columnSpan(10),
            ]),
        ];
    }
}
