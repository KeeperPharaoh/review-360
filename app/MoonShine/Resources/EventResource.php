<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\MoonShine\Pages\Event\CompanyReportPage;
use App\MoonShine\Pages\Event\GeneralReportPage;
use App\MoonShine\Pages\Event\ReportPage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Event;
use App\MoonShine\Pages\Event\EventIndexPage;
use App\MoonShine\Pages\Event\EventFormPage;
use App\MoonShine\Pages\Event\EventDetailPage;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
 * @extends ModelResource<Event, EventIndexPage, EventFormPage, EventDetailPage>
 */
class EventResource extends ModelResource
{
    protected string $model = Event::class;

    protected string $title = 'Мероприятия';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            EventIndexPage::class,
            EventFormPage::class,
            EventDetailPage::class,
            GeneralReportPage::class,
            ReportPage::class,
            CompanyReportPage::class,
        ];
    }

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder->where('company_id', '=', Auth::user()->company_id);
    }

    protected function search(): array
    {
        return ['name'];
    }

    /**
     * @param Event $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
