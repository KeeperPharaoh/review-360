<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\CompanyResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\TeamResource;
use App\MoonShine\Resources\PositionResource;
use App\MoonShine\Resources\ReviewMethodResource;
use App\MoonShine\Resources\QuestionResource;
use App\MoonShine\Resources\EventResource;
use App\MoonShine\Resources\AssignmentResource;
use App\MoonShine\Pages\Event;
use App\MoonShine\Pages\Event\GptPage;
use App\MoonShine\Pages\Event\ReportPage;
use App\MoonShine\Resources\AnswerResource;
use App\MoonShine\Pages\Event\GeneralReportPage;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        // $config->authEnable();

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                CompanyResource::class,
                UserResource::class,
                TeamResource::class,
                PositionResource::class,
                ReviewMethodResource::class,
                QuestionResource::class,
                EventResource::class,
                AssignmentResource::class,
                AnswerResource::class,
            ])
            ->pages([
                ...$config->getPages(),
                ReportPage::class,
                GeneralReportPage::class,
            ])
        ;
    }
}
