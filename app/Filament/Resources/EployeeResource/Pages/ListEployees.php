<?php

namespace App\Filament\Resources\EployeeResource\Pages;

use App\Filament\Resources\EployeeResource;
use App\Models\Eployee;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEployees extends ListRecords
{
    protected static string $resource = EployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Filtering according [week, month, year]
        return [
            "All"   => Tab::make(),
            "This Week" => Tab::make()->modifyQueryUsing(fn (Builder $query) =>
                $query->where("date_hired", '>=', now()->subWeek())
            )
            ->badge(Eployee::query()->where("date_hired", ">=", now()->subWeek())->count()),
            "This Month" => Tab::make()->modifyQueryUsing(fn (Builder $query) =>
                $query->where("date_hired", '>=', now()->subMonth())
            )
            ->badge(Eployee::query()->where("date_hired", ">=", now()->subMonth())->count()),
            "This year" => Tab::make()->modifyQueryUsing(fn (Builder $query) =>
                $query->where("date_hired", '>=', now()->subYear())
            )
            ->badge(Eployee::query()->where("date_hired", ">=", now()->subYear())->count()),
        ];
    }
}
