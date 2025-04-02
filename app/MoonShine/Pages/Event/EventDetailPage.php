<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\Question;
use App\Models\ReviewMethod;
use App\Models\User;
use App\MoonShine\Resources\ReviewMethodResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use App\MoonShine\BasePages\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Content;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\Title;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Text;
use Throwable;

class EventDetailPage extends DetailPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            BelongsTo::make(
                'Методология',
                'ReviewMethod',
                formatted: static fn(ReviewMethod $model) => $model->getName(),
                resource: ReviewMethodResource::class,
            )
                ->creatable()
                ->valuesQuery(static fn(Builder $q) => $q->select(['id', 'name'])),
            Text::make('Название', 'name'),
            Date::make('Дата начала', 'start_at')->format('Y-m-d'),
            Date::make('Дата конца', 'end_at')->format('Y-m-d'),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ActionButton::make('Посмотреть отчет по сотрудникам', $this->getResource()->getPageUrl(ReportPage::class) . '?event_id=' . $this->getResource()->getItem()->id),
            ActionButton::make('Посмотреть ответы по компании', $this->getResource()->getPageUrl(CompanyReportPage::class) . '?event_id=' . $this->getResource()->getItem()->id),
            ActionButton::make('Посмотреть ответы', env('APP_URL') . '/admin/resource/answer-resource/answer-index-page' . '?event_id=' . $this->getResource()->getItem()->id),
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        /** @var Event $event */
        $event = $this->getResource()->getItem();

        [$companyTotal, $userTotal, $companyValue, $userProgress, $full, $part, $nothing] = $this->getProgressCompany($event);
        [$enps, $avgCompany, $promoters, $passives, $detractors] = $this->getReport($event);

        return [
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
                DonutChartMetric::make('Проходимость')
                    ->values(['Полностью' => $full, 'Частично' => $part, 'Не приступали' => $nothing])
                    ->columnSpan(6),
            ]),

            Content::make([
                Box::make('Отчет о компании', [$event->report])
            ]),
            Content::make([
                Box::make('Общий отчет о состояние отделов', [$event->team_report])
            ]),

//            Content::make([
//                Title::make('Оценка компании'),
//                ValueMetric::make('из ' . $companyTotal . ' ответов')
//                    ->value($companyValue)
//                    ->progress($companyTotal),
//            ]),
//            Content::make([
//                Title::make('Оценка сотрудников'),
//                ValueMetric::make('из ' . $userTotal . ' ответов')
//                    ->value($userProgress)
//                    ->progress($userTotal),
//            ]),
            ...parent::mainLayer()
        ];
    }

    protected function getProgressCompany(Event $event): array
    {
        $companyId = $event->company_id;

        $userTotal = Assignment::query()
            ->where('review_method_id', '=', $event->review_method_id)
            ->whereHas('toUser', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->count();
        $userProgress = Answer::query()
            ->where('event_id', '=', $event->id)
            ->where('question_id', '=', 1)
            ->count();
        if ($userProgress >= $userTotal) {
            $userTotal = $userProgress;
        }
        $companyTotal = User::query()->where('company_id', $event->company_id)->count();
        $companyValue = Answer::query()
            ->where('event_id', '=', $event->id)
            ->where('question_id', '=', 3)
            ->count();

        $full = 0;
        $part = 0;
        $nothing = 0;
        $users = User::query()
            ->where('company_id', $companyId)
            ->get();

        foreach ($users as $user) {
            $answers = Answer::query()
                ->where('user_id', '=', $user->id)
                ->where('question_id', '=', 1)
                ->count();
            $assignments = Assignment::query()
                ->where('from_user_id', '=', $user->id)
                ->whereHas('toUser', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->count();

            if ($answers == 0) {
                $nothing++;
            } elseif ($answers == $assignments) {
                $full++;
            } else {
                $part++;
            }
        }

        return [$companyTotal, $userTotal, $companyValue, $userProgress, $full, $part, $nothing];
    }

    protected function getReport(Event $event): array
    {
        $companyId = $event->company_id;

        $question = Question::query()
            ->where('company_id', '=', $companyId)
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


        $answers = Answer::query()
            ->where('event_id', '=', $event->id)
            ->where('question_id', '=', 3)
            ->get();
        $total = 0;
        foreach ($answers as $answer) {
            $total += $answer->answer;
        }

        $avgCompany = number_format($total / $answers->count(), 2);
        $enps = ($promoters - $detractors) / ($promoters + $detractors + $passives) * 100;
        $enps = number_format($enps, 2);

        return [$enps, $avgCompany, $promoters, $passives, $detractors];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
