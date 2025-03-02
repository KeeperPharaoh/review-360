<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\MoonShine\Pages\Event\EventIndexPage;
use App\MoonShine\Pages\Event\EventFormPage;
use App\MoonShine\Pages\Event\EventDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
 * @extends ModelResource<Event, EventIndexPage, EventFormPage, EventDetailPage>
 */
class EventResource extends ModelResource
{
    protected string $model = Event::class;

    protected string $title = 'Events';
    
    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            EventIndexPage::class,
            EventFormPage::class,
            EventDetailPage::class,
        ];
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
