<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadoResource\Pages;
use App\Filament\Resources\EmpleadoResource\RelationManagers;
use App\Models\Empleado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput\Mask;


class EmpleadoResource extends Resource
{
    protected static ?string $model = Empleado::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Relacionados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                ->schema([
                    Textinput::make('nombre')
                    ->label('Nombre')
                    ->required()->maxLength(100),
                    TextInput::make('correo')->email()->nullable(),
                    Forms\Components\TextInput::make('telefono')->nullable()
                    ->mask('00-0000-0000') // Aplica la máscara visual
                    ->maxLength(12) // Incluye guiones: ej. 55-1234-5678
                    ->placeholder('00-0000-0000')
                    ->dehydrateStateUsing(function ($state) {
                        return preg_replace('/[^0-9]/', '', $state); // Solo guarda los números
                    })
                    ->numeric(), // Opcional, para evitar letras en el input
                ])
                
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
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleado::route('/create'),
            'edit' => Pages\EditEmpleado::route('/{record}/edit'),
        ];
    }
}
