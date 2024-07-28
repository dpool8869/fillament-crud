<?php

namespace App\Filament\Resources\EployeeResource\Pages;

use App\Filament\Resources\EployeeResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEployee extends CreateRecord
{
    protected static string $resource = EployeeResource::class;

    // Flash Message customzing
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title("Employee Created")
            ->body("The Employee Created Successfully");
    }


}
