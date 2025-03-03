<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\ReviewMethod;
use App\Models\User;
use App\MoonShine\Pages\Answer\AnswerDetailPage;
use App\MoonShine\Resources\ReviewMethodResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
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
        $progress = Assignment::query()
                ->where('review_method_id', '=', $event->review_method_id)
                ->count() * 2;

        $progress += (User::query()->count() * 2);

        $value = Answer::query()
            ->where('event_id', '=', $event->id)
            ->count();
        if ($value >= $progress) {
            $progress = $value;
        }
        return [
            ValueMetric::make('Завершен')
                ->value($value)
                ->progress($progress),
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
