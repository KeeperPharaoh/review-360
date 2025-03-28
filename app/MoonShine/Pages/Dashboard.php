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

        $lineChartMetricData['2024-08-05'] = 8.64;
        $lineChartMetricData['2024-12-16'] = 8.42;

        return [
            Grid::make([

                \MoonShine\UI\Components\Layout\Body::make([
                LineChartMetric::make('Рейтинг')
                    ->line([
                        'Оценка' => $lineChartMetricData
                    ])->columnSpan(10),
            ]),
            ]),
            Grid::make([
                ValueMetric::make('Сотрудников')
                    ->value(fn() => 27)
                    ->columnSpan(3),

                ValueMetric::make('Команд')
                    ->value(fn() => 6)
                    ->columnSpan(3),
            ]),

            Grid::make([
                ValueMetric::make('Отчетов')
                    ->value(fn() => 52)
                    ->columnSpan(3),

                ValueMetric::make('Мероприятий')
                    ->value(fn() => Event::count())
                    ->columnSpan(3),
            ]),
        ];
    }
}
