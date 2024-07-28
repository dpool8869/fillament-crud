<?php

namespace App\Filament\Resources\EployeeResource\Pages;

use App\Filament\Resources\EployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEployee extends ViewRecord
{
    protected static string $resource = EployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
