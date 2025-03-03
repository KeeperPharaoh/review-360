<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Event;
use App\Models\Report;
use App\Models\UserMeta;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Text;


class ReportPage extends Page
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
        return 'Отчет по сотрудникам';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $eventId = request()->input('event_id') ?? null;
        if ($eventId) {
            $event = Event::query()->findOrFail($eventId);

            $result = [];
            $reports = Report::query()
                ->where('event_id', '=', $event->id)
                ->get();

            /** @var Report $report */
            foreach ($reports as $report) {
                $result[] = [
                    'fio' => $report->user->first_name . ' ' . $report->user->first_name,
                    'report' => $report->text,
                ];
            }

            return [
                TableBuilder::make()
                    ->items($result)
                    ->fields([
                        Text::make('ФИО', 'fio'),
                        Text::make('Отчет', 'report'),
                    ])
            ];
        } else {
            $userId = request()->input('user_id') ?? null;

            $reports = Report::query()
                ->where('user_id', '=', $userId)
                ->get();

            $result = [];

            foreach ($reports as $report) {
                $result[] = [
                    'report' => $report->text,
                ];
            }
            return [
                TableBuilder::make()
                    ->items($result)
                    ->fields([
                        Text::make('Отчет', 'report'),
                    ])
            ];
        }
    }
}
