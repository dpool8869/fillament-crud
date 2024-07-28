<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\RelationManagers\EmployeesRelationManager;
use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Filament\Resources\StateResource\RelationManagers\EmployeesRelationManager as RelationManagersEmployeesRelationManager;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;


class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

    // Customzing the label in the panel
    protected static ?string $navigationLabel = "State";

    // Notification Badge for Country Panel
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // // Notification Badge Color for Country Panel
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? "warning" : "success";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('country_id')
                    ->relationship(name: "country", titleAttribute: "name")
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->label("Country Name")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort("country.name", "desc")
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {

        return $infolist->schema([
            Section::make("State Info")->schema([
                TextEntry::make("country.name")->label("Country Name"),
                TextEntry::make("name")->label("State Name"),
            ])
        ]);
        // return $infolist
        //     ->schema([
        //         TextEntry::make("country.name")->label("Country Name"),
        //         TextEntry::make("name")->label("State Name"),
        //     ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagersEmployeesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'view' => Pages\ViewState::route('/{record}'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
