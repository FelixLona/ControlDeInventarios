<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResponsableResource\Pages;
use App\Filament\Resources\ResponsableResource\RelationManagers;
use App\Models\Responsable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class ResponsableResource extends Resource
{
    protected static ?string $model = Responsable::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Relacionados';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nombre')->required()->maxLength(100),
            TextInput::make('correo')->email()->nullable(),
            TextInput::make('telefono')->nullable()->maxLength(20),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('nombre')->searchable(),
            TextColumn::make('correo')->searchable(),
            TextColumn::make('telefono')->searchable(),
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
            'index' => Pages\ListResponsables::route('/'),
            'create' => Pages\CreateResponsable::route('/create'),
            'edit' => Pages\EditResponsable::route('/{record}/edit'),
        ];
    }
}
