<?php

namespace App\Filament\Widgets;

use App\Models\Eployee;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminEmployees extends BaseWidget
{
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Eployee::query())
            ->defaultSort("created_at", "desc")
            ->columns([
                Tables\Columns\TextColumn::make("first_name"),
                Tables\Columns\TextColumn::make("last_name"),
                Tables\Columns\TextColumn::make("country.name"),
            ]);
    }
}
