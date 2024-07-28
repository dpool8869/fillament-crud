<?php

namespace App\Filament\Resources\CityResource\RelationManagers;

use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('first_name')
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
        ])
        ->filters([
            //
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }
}
