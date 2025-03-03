<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\UserMeta;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Text;


class UserMetaPage extends Page
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
        return 'Заметки';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
	{
        $userId = request()->input('user_id') ?? null;

        $metas = UserMeta::query()
            ->where('user_id', '=', $userId)
            ->get();

        $result = [];
        foreach ($metas as $meta) {
            $result[] = [
                'text'       => $meta->text,
                'created_at' => $meta->created_at,
            ];
        }
        return [
            TableBuilder::make()
                ->items($result)
                ->fields([
                    Text::make('Заметка', 'text'),
                    Date::make('Дата создания', 'created_at')->format('d.m.Y H:i:s'),
                ])
        ];
	}
}
