<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\Models\MoonshineUser;
use App\MoonShine\Resources\AnswerResource;
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
            ];
        } else {
            return [
                MenuItem::make('Сотрудники', UserResource::class, 'user-group'),
                MenuItem::make('Мероприятия', EventResource::class, 'calendar'),
                MenuItem::make('Ответы', AnswerResource::class, 'question-mark-circle'),

                MenuGroup::make('Настр. Отделов', [
                    MenuItem::make('Отделы', TeamResource::class, 'building-office-2'),
                    MenuItem::make('Должности', PositionResource::class, 'users'),
                ], 'squares-2x2'),
                MenuGroup::make('Настр. Оценивания', [
                    MenuItem::make('Методологии', ReviewMethodResource::class, 'queue-list'),
                    MenuItem::make('Вопросы', QuestionResource::class, 'question-mark-circle'),
//                    MenuItem::make('Матрица оценок', AssignmentResource::class, 'cube-transparent'),
                ], 'squares-2x2'),
            ];
        }
    }

    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
