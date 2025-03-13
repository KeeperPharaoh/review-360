<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\Report;
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
                $assignments = Assignment::query()
                    ->where('to_user_id', '=', $report->user_id)
                    ->select(['id'])
                    ->get()
                    ->pluck('id');

                $answers = Answer::query()
                    ->where('event_id', '=', $event->id)
                    ->where('question_id', 1)
                    ->whereIn('assignment_id', $assignments)
                    ->where('answer', '!=', 'Не взаимодействуем')
                    ->select(['answer'])
                    ->get()
                    ->pluck('answer')
                    ->toArray();

                $total = 0;
                foreach ($answers as $answer) {
                    $total += (int)$answer;
                }

                $result[] = [
                    'fio' => $report->user->first_name . ' ' . $report->user->last_name,
                    'avg' => number_format(count($answers) ? $total / count($answers) : 0, 2),
                    'median' => number_format($this->median($answers), 2),
                    'report' => $report->text,
                ];
            }

            return [
                TableBuilder::make()
                    ->items($result)
                    ->fields([
                        Text::make('ФИО', 'fio'),
                        Text::make('Ср. оценка', 'avg'),
                        Text::make('Медиана', 'median'),
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

    protected function median(array $numbers): float
    {
        if (empty($numbers)) {
            return 0;
        }

        sort($numbers);
        $count = count($numbers);
        $middle = floor($count / 2);

        if ($count % 2) {
            return (float)$numbers[$middle];
        } else {
            return (float)($numbers[$middle - 1] + $numbers[$middle]) / 2;
        }
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Title', 'title'),
        ];
    }
}
