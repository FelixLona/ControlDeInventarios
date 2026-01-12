<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AulaResource\Pages;
use App\Filament\Resources\AulaResource\RelationManagers;
use App\Models\Aula;
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
use Filament\Forms\Components\Select;

class AulaResource extends Resource
{
    protected static ?string $model = Aula::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Ubicaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                TextInput::make('nombre')
                ->columnSpanFull()
                    ->required()
                    ->maxLength(100)
                    ->label('Nombre'),

                Textarea::make('descripcion')
                    ->nullable()
                    ->columnSpanFull()
                    ->label('Descripción'),
                Select::make('id_edificio')
                    ->label('Edificio')
                    ->relationship('edificio', 'nombre')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->searchable()->sortable(),
                TextColumn::make('descripcion')->limit(50),
                TextColumn::make('edificio.nombre')
                    ->label('Edificio')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListAulas::route('/'),
            'create' => Pages\CreateAula::route('/create'),
            'edit' => Pages\EditAula::route('/{record}/edit'),
        ];
    }
}
