<?php

namespace App\Filament\Resources\EployeeResource\Pages;

use App\Filament\Resources\EployeeResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEployee extends EditRecord
{
    protected static string $resource = EployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    // Flash Message customzing
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->warning()
            ->title("Employee Updated")
            ->body("The Employee Updated Successfully");
    }
}
