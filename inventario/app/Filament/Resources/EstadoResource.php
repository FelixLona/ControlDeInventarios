<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstadoResource\Pages;
use App\Filament\Resources\EstadoResource\RelationManagers;
use App\Models\Estado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class EstadoResource extends Resource
{
    protected static ?string $model = Estado::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Estados';
    protected static ?string $navigationGroup = 'Relacionados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                ->schema([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(100)
                    ->label('Nombre del estado'),

                Forms\Components\Textarea::make('descripcion')
                    ->nullable()
                    ->label('Descripción'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->searchable(),
                TextColumn::make('descripcion')->limit(50)->searchable(),
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
            'index' => Pages\ListEstados::route('/'),
            'create' => Pages\CreateEstado::route('/create'),
            'edit' => Pages\EditEstado::route('/{record}/edit'),
        ];
    }
}
