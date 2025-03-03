<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Report;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Text;


class AnswerPage extends Page
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
        return 'Отзывы';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $userId = request()->input('user_id') ?? null;

        $assignments = Assignment::query()
            ->where('to_user_id', '=', $userId)
            ->select(['id'])
            ->get()
            ->pluck('id')
            ->toArray();

        $answers = Answer::query()
            ->whereIn('assignment_id', $assignments)
            ->get();

        $result = [];
        /** @var Answer $answer */
        foreach ($answers as $answer) {
            $result[] = [
                'event' => $answer->event->name,
                'question' => $answer->question->question,
                'answer' => $answer->answer,
            ];
        }
        return [
            TableBuilder::make()
                ->items($result)
                ->fields([
                    Text::make('Мероприятие', 'event'),
                    Text::make('Вопрос', 'question'),
                    Text::make('Ответ', 'answer'),
                ])
        ];
    }
}
