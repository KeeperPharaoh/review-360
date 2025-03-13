<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\ReviewMethod;
use App\Models\User;
use App\MoonShine\Resources\ReviewMethodResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Content;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
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
            ActionButton::make('Посмотреть общий отчет', $this->getResource()->getPageUrl(GeneralReportPage::class) . '?event_id=' . $this->getResource()->getItem()->id),
            ActionButton::make('Посмотреть отчет по сотрудникам', $this->getResource()->getPageUrl(ReportPage::class) . '?event_id=' . $this->getResource()->getItem()->id),
            ActionButton::make('Посмотреть ответы по компании', $this->getResource()->getPageUrl(CompanyReportPage::class) . '?event_id=' . $this->getResource()->getItem()->id),
            ActionButton::make('Посмотреть ответы', env('APP_URL') . '/admin/resource/answer-resource/answer-index-page'),
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

        $userTotal = Assignment::query()
            ->where('review_method_id', '=', $event->review_method_id)
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
        $users = User::all();

        foreach ($users as $user) {
            $answers = Answer::query()
                ->where('user_id', '=', $user->id)
                ->where('question_id', '=', 1)
                ->count();
            $assignments = Assignment::query()
                ->where('from_user_id', '=', $user->id)
                ->count();

            if ($answers == 0) {
                $nothing++;
            } elseif ($answers == $assignments) {
                $full++;
            } else {
                $part++;
            }
        }

        return [
            Content::make([
                Title::make('Оценка компании'),
                ValueMetric::make('из ' . $companyTotal . ' ответов')
                    ->value($companyValue)
                    ->progress($companyTotal),
            ]),
            Content::make([
                Title::make('Оценка сотрудников'),
                ValueMetric::make('из ' . $userTotal . ' ответов')
                    ->value($userProgress)
                    ->progress($userTotal),
            ]),
            Content::make([
                Title::make(''),
                DonutChartMetric::make('Проходимость')
                    ->values(['Полностью' => $full, 'Частично' => $part, 'Не приступали' => $nothing])
                    ->columnSpan(User::query()->count()),
            ]),
            ...parent::mainLayer()
        ];
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
