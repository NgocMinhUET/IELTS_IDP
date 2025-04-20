<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
use App\Models\Category;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('ユーザー数', User::count())
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    '@click' => "window.location.href = '/admin/users'",
                ]),
            Stat::make('カテゴリ', Category::count())->extraAttributes([
                'class' => 'cursor-pointer',
                '@click' => "window.location.href = '/admin/categories'",
            ]),
        ];
    }
}
