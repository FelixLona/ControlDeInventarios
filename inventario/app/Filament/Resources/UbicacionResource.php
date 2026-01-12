<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UbicacionResource\Pages;
use App\Filament\Resources\UbicacionResource\RelationManagers;
use App\Models\Ubicacion;
use App\Models\Edificio;
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

class UbicacionResource extends Resource
{
    protected static ?string $model = Ubicacion::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Ubicacion';
    protected static ?string $navigationGroup = 'Ubicaciones';

    public static function getModelLabel(): string
    {
        return 'Ubicaciones';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('nombre')->required()->maxLength(100)->label('Nombre')->columnSpanFull(),
                Textarea::make('descripcion')->nullable()->columnSpanFull(),



                Forms\Components\Select::make('id_edificio')
                    ->label('Edificio')
                    ->columnSpanFull()
                    ->required()
                    ->options(Edificio::pluck('nombre', 'id_edificio'))->searchable()
                    ->reactive() //al cambiar el valor se puede actualizar dinámicamente otro campo que dependa de edificio
                    ->disabled(fn(callable $get) => filled($get('id_aula')))
                    ->afterStateUpdated(fn(callable $set) => $set('id_aula', null)), //el campo id_aula se reinicia al cambiar edificio


                Forms\Components\Select::make('id_aula')
                    ->label(('Aula'))
                    ->columnSpanFull()
                    ->relationship('aula', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(function (callable $get) {

                        if (!$get('id_edificio')) return [];
                        return Aula::where('id_edificio', $get('id_edificio'))->pluck('nombre', 'id_aula');
                    })
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->searchable()->sortable(),
                TextColumn::make('descripcion')->limit(50),
                TextColumn::make('aula.nombre')->label('Aula')->searchable()->sortable(),
                TextColumn::make('aula.edificio.nombre')->label('Edificio')->searchable()->sortable(),
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
