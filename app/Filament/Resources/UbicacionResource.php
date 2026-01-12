<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UbicacionResource\Pages;
use App\Filament\Resources\UbicacionResource\RelationManagers;
use App\Models\Ubicacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class UbicacionResource extends Resource
{
    protected static ?string $model = Ubicacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Ubicacion';
    protected static ?string $navigationGroup = 'Relacionados';

    public static function getModelLabel(): string
{
    return 'Ubicaciones'; 
}

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Card::make()
            ->schema([
            TextInput::make('nombre')->required()->maxLength(100)->label('clave'),
            Textarea::make('descripcion')->nullable()->placeholder('Concepto, Edificio y Area'),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('nombre')->searchable(),
            TextColumn::make('descripcion')->limit(50),
        ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUbicacions::route('/'),
            'create' => Pages\CreateUbicacion::route('/create'),
            'edit' => Pages\EditUbicacion::route('/{record}/edit'),
        ];
    }
}
