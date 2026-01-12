<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EdificioResource\Pages;
use App\Models\Edificio;
use Filament\Resources\Resource;
use Filament\Forms\Form;             
use Filament\Tables\Table;           
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class EdificioResource extends Resource
{
    protected static ?string $model = Edificio::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Edificios';
    protected static ?string $navigationGroup = 'Ubicaciones';

    public static function getModelLabel(): string
    {
        return 'Edificios';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull()
                            ->label('Nombre'),

                        Textarea::make('descripcion')
                            ->nullable()
                            ->columnSpanFull()
                            ->label('Descripción'),
                    ]),
            ]);
    }

    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Nombre')->sortable()->searchable(),
                TextColumn::make('descripcion')->label('Descripcion')->limit(50),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEdificios::route('/'),
            'create' => Pages\CreateEdificio::route('/create'),
            'edit'   => Pages\EditEdificio::route('/{record}/edit'),
        ];
    }
}
