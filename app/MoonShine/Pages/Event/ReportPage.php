<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
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
                $assignments = Assignment::query()
                    ->where('to_user_id', '=', $report->user_id)
                    ->select(['id'])
                    ->get()
                    ->pluck('id');

                $answers = Answer::query()
                    ->where('event_id', '=', $event->id)
                    ->where('question_id', 1)
                    ->whereIn('assignment_id', $assignments)
                    ->select(['answer'])
                    ->get();
                $total = 0;
                foreach ($answers as $answer) {
                    $total += (int) $answer->answer;
                }

                $result[] = [
                    'fio' => $report->user->first_name . ' ' . $report->user->last_name,
                    'avg' => number_format($answers->where('answer', '!=', 'Не взаимодействуем')->count() ? $total / $answers->where('answer', '!=', 'Не взаимодействуем')->count() : 0, 2),
                    'report' => $report->text,
                ];
            }

            return [
                TableBuilder::make()
                    ->items($result)
                    ->fields([
                        Text::make('ФИО', 'fio'),
                        Text::make('Ср. оценка', 'avg'),
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
