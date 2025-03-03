<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\MoonshineUser;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
#[\MoonShine\MenuManager\Attributes\SkipMenu]

class Dashboard extends Page
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
        return 'О компании';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
	{
		return [];
	}
}
