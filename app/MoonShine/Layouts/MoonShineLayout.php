<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\Models\MoonshineUser;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When
};
use MoonShine\Laravel\Resources\MoonShineUserResource;
use MoonShine\Laravel\Resources\MoonShineUserRoleResource;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\CompanyResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\TeamResource;
use App\MoonShine\Resources\PositionResource;
use App\MoonShine\Resources\ReviewMethodResource;
use App\MoonShine\Resources\QuestionResource;
use App\MoonShine\Resources\EventResource;
use App\MoonShine\Resources\AssignmentResource;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        /** @var MoonshineUser $user */
        $user = Auth::user();

        if ($user->moonshine_user_role_id == 1) {
            return [
                MenuItem::make('Компании', CompanyResource::class),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    MoonShineUserResource::class
                ),
                MenuItem::make('Assignments', AssignmentResource::class),
        ];
        } else {
            return [
                MenuItem::make('Сотрудники', UserResource::class),
                MenuItem::make('Мероприятия', EventResource::class),

                MenuGroup::make('Настройка отделов', [
                    MenuItem::make('Отделы', TeamResource::class),
                    MenuItem::make('Должности', PositionResource::class),
                ]),
                MenuGroup::make('Настройка оценивания', [
                    MenuItem::make('Методологии', ReviewMethodResource::class),
                    MenuItem::make('Вопросы', QuestionResource::class),
                ]),
            ];
        }
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
