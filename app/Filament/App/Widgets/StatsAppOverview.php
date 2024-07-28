<?php

namespace App\Filament\App\Widgets;

use App\Models\Department;
use App\Models\Eployee;
use App\Models\Team;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAppOverview extends BaseWidget
{
    protected static ?int $sort =2;
    protected int | string | array $columnSpan = "full";
    protected function getStats(): array
    {
        
        return [
            Stat::make("Users", Team::find(Filament::getTenant())->first()->members()->count())
                ->description('All the Users from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Departments', Department::query()->count())
                ->description('All the Teams from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Employees', Eployee::query()->count())
                ->description('All the Employees from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
