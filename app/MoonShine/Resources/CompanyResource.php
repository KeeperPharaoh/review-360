<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Company;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Switcher;

#[Icon('building-office')]
/**
 * @extends ModelResource<Company>
 */
class CompanyResource extends ModelResource
{
    protected string $model = Company::class;

    protected string $title = 'Компании';
    protected string $column = 'name';

    protected bool $createInModal = true;

    protected bool $detailInModal = true;

    protected bool $editInModal = true;

    protected bool $cursorPaginate = true;

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(__('moonshine::ui.resource.name'), 'name'),
            Switcher::make(__('moonshine::ui.resource.is_active'), 'is_active'),
            Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                ->format("d.m.Y")
                ->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make()->sortable(),
                Text::make(__('moonshine::ui.resource.role_name'), 'name')
                    ->required(),
                Switcher::make(__('moonshine::ui.resource.is_active'), 'is_active'),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required'],
        ];
    }

    protected function search(): array
    {
        return [
            'name',
        ];
    }
}
