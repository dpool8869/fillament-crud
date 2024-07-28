<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EployeeResource\Pages;
use App\Filament\Resources\EployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Eployee;
use App\Models\State;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EployeeResource extends Resource
{
    protected static ?string $model = Eployee::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    // Customzing the label in the panel
    protected static ?string $navigationLabel = "Employee";

    protected static ?string $modelLabel = "Employees";

    // We add a new group panel and put the new group panel in this
    protected static ?string $navigationGroup = "System Management";

    // Global Search for Employees
    protected static ?string $recordTitleAttribute = "first_name";

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->first_name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            "first_name",
            "middle_name",
            "last_name",
            "country.name"
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            "Country"   => $record->country->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(["country"]);    
    }

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
                Forms\Components\Section::make("User Name")
                    ->description("Put the user name details in here")
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required(),
                        Forms\Components\TextInput::make('middle_name')
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->required(),
                    ])->columns(3),
                Forms\Components\Section::make("User Address")
                    ->description("Put the User Adderss Details in here")
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->required(),
                        Forms\Components\TextInput::make('zip_code')
                            ->required(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->required(),
                       
                        
                    ])->columns(3)
                ,
                Forms\Components\Section::make("User Enrollment")
                    ->description("Put the User Enrollment Details in here")
                    ->schema([
                        Forms\Components\Select::make('country_id')
                        ->relationship(name: "country", titleAttribute: "name")
                        ->preload()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Set $set) { 
                            $set("state_id", null);
                            $set("city_id", null);
                        })
                        ->required(),
                        Forms\Components\DatePicker::make('date_hired')
                        ->required(),
                        Forms\Components\Select::make('state_id')
                        ->label("State")
                        ->options(fn(Get $get): Collection => State::query()
                                                            ->where("country_id", $get("country_id"))
                                                            ->pluck("name", "id"))
                        ->preload()
                        ->searchable()
                        ->afterStateUpdated(function (Set $set) {
                            $set("city_id", null);
                        })
                        ->live()
                        ->required(),
                        Forms\Components\Select::make('city_id')
                        ->label("City")
                        ->options(fn(Get $get): Collection => City::query()
                                                            ->where("state_id", $get("state_id"))
                                                            ->pluck("name", "id"))
                        ->preload()
                        ->live()
                        ->searchable()
                        ->required(),
                        Forms\Components\Select::make('department_id')
                        ->relationship(name: "department", titleAttribute: "name")
                        ->preload()
                        ->searchable()
                        ->required()
                        ->columnSpanFull()
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
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
            ])
            ->filters([
                // 
                SelectFilter::make("Department")
                    ->relationship("department", "name")
                    ->searchable()
                    ->preload()
                    ->label("Filter By Department"),
                    Filter::make('created_at')
                        ->form([
                            DatePicker::make('created_from'),
                            DatePicker::make('created_until'),
                        ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['created_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            )
            ->when(
                $data['created_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            );
        })->indicateUsing(function (array $data): array {
            $indicators = [];
     
            if ($data['created_from'] ?? null) {
                $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                    ->removeField('created_from');
            }
     
            if ($data['created_until'] ?? null) {
                $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                    ->removeField('created_until');
            }
     
            return $indicators;
        })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->successNotification(
                    Notification::make()
                    ->danger()
                    ->title("Employee Destroyed")
                    ->body("The Employee deleted Successfully")
                ),
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
            Section::make("Username Info")->schema([
                TextEntry::make("first_name")->label("First Name"),
                TextEntry::make("middle_name")->label("Middle Name"),
                TextEntry::make("last_name")->label("Last Name"),
            ])->columns(3),
            Section::make("Address Info")->schema([
                TextEntry::make("address")->label("State Name"),
                TextEntry::make("zip_code")->label("Zip Code"),
            ])->columns(2),
            Section::make("Enrollment Info")->schema([
                TextEntry::make("country.name")->label("Country Name"),
                TextEntry::make("state.name")->label("State Name"),
                TextEntry::make("city.name")->label("City Name"),
                TextEntry::make("department.name")->label("Department Name"),
                TextEntry::make("date_hired")->label("Date of Enrollment"),
            ])->columns(2)
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEployees::route('/'),
            'create' => Pages\CreateEployee::route('/create'),
            // 'view' => Pages\ViewEployee::route('/{record}'),
            'edit' => Pages\EditEployee::route('/{record}/edit'),
        ];
    }
}
