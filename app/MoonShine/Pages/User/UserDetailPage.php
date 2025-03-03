<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\User;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\Question;
use App\Models\User;
use App\MoonShine\Pages\Event\AnswerPage;
use App\MoonShine\Pages\Event\ReportPage;
use App\MoonShine\Pages\Event\UserMetaPage;
use http\Message\Body;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\ActionGroup;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Content;
use MoonShine\UI\Components\Layout\Div;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\Head;
use MoonShine\UI\Components\Layout\Html;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\Title;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\Textarea;
use Throwable;
use MoonShine\Apexcharts\Components\LineChartMetric;

class UserDetailPage extends DetailPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        /** @var User $user */
        $user = $this->getResource()->getItem();
        $events = Event::query()
            ->where('end_at', '<=', now())
            ->get();
        $lineChartMetricData = [];

        foreach ($events as $event) {
            $assignments = Assignment::query()
                ->where('to_user_id', '=', $user->id)
                ->select(['id'])
                ->get()
                ->pluck('id');
            $questions = Question::query()
                ->where('target', '=', 'employee')
                ->whereIn('answer_type', ['number_5', 'number_10'])
                ->get()
                ->pluck('id');

            $answers = Answer::query()
                ->where('event_id', '=', $event->id)
                ->whereIn('question_id', $questions)
                ->whereIn('assignment_id', $assignments)
                ->select(['answer'])
                ->get();
            $total = 0;
            foreach ($answers as $answer) {
                $total += (int) $answer->answer;
            }
            $lineChartMetricData[(new \DateTime($event->end_at))->format('Y-m-d')] = number_format($answers->count() ? $total / $answers->count() : 0, 2);
        }

        return [
            Html::make([
                Title::make( $user->first_name . ' ' . $user->last_name . ':'),
                Title::make( 'Отдел: ' . ' ' . $user->team->name),
                Title::make( 'Должность: ' . ' ' . $user->position->name),

                Grid::make([
                    Content::make([
                        ActionButton::make('Посмотреть отзывы',
                            $this->getResource()->getPageUrl(AnswerPage::class) . '?user_id=' . $user->id),
                    ]),
                    Title::make('<div> </div>'),
                    Content::make([
                        ActionButton::make('Посмотреть отчеты', $this->getResource()->getPageUrl(ReportPage::class) . '?user_id=' . $user->id),
                    ]),

                    Title::make('<div> </div>'),
                    Content::make([
                        ActionButton::make('Посмотреть заметки', $this->getResource()->getPageUrl(UserMetaPage::class) . '?user_id=' . $user->id),
                    ]),

                    Title::make('<div> </div>'),
                    Content::make([
                        Modal::make(
                            'Вопросы для One-to-One',
                            '',
                            ActionButton::make('Сгенерировать вопросы для one-to-one', '#'),
                            asyncUrl: '/api/one-to-one?user_id=' . $user->id
                        ),]),
                ], 4),

                Title::make('<br>'),

                Head::make([
                    LineChartMetric::make('Ср. Оценка')
                        ->line([
                            'Count' => $lineChartMetricData
                        ])->columnSpan(5),
                ]),
            ]),

            FormBuilder::make(
                action: '/api/user-meta',
                method: FormMethod::POST,
                fields: [
                    Hidden::make('_method')->setValue('post'),
                    Hidden::make('user_id')->setValue($user->id),
                    Textarea::make('Добавить заметку', 'text')
                ],
                values: ['text' => 'Текст', 'user_id' => $user->id]
            ),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        /** @var User $user */
        $user = $this->getResource()->getItem();
        return [
            Content::make([
                Box::make('Общая характеристика', [$user->general_report])
            ]),
        ];
    }
}
