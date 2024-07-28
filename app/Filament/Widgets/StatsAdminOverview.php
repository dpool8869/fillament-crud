<?php

namespace App\Filament\Widgets;

use App\Models\Eployee;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget

{
    
    
    protected function getStats(): array
    {
        return [
            Stat::make("Users", User::query()->count())
                ->description('All the Users from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Teams', Team::query()->count())
                ->description('All the Teams from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Employees', Eployee::query()->count())
                ->description('All the Employees from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
