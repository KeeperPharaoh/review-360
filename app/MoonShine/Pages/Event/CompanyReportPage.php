<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;


class CompanyReportPage extends Page
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
        return 'Отчет по компании';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $eventId = request()->input('event_id') ?? null;

        $answers = Answer::query()
            ->where('event_id', '=', $eventId)
            ->where('question_id', 3)
            ->get();

        $result = [];
        $kek = 1;
        foreach ($answers as $answer) {
            $answerTwo = Answer::query()
                ->where('event_id', '=', $eventId)
                ->where('question_id', 4)
                ->where('user_id', '=', $answer->user_id)
                ->first();


            $result[] = [
                'id'  => $kek,
                'rate' => $answer->answer,
                'report' => (string)$answerTwo?->answer,
            ];
            $kek++;
        }

        return [
            TableBuilder::make()
                ->items($result)
                ->fields([
                    ID::make('ID', 'id')->sortable(),
                    Text::make('Оценка', 'rate'),
                    Text::make('Отчет', 'report'),
                ])
        ];
    }
}
