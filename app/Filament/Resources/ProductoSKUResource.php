<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoSKUResource\Pages;
use App\Filament\Resources\ProductoSKUResource\RelationManagers;
use App\Models\ProductoSku;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;

class ProductoSKUResource extends Resource
{
    protected static ?string $model = ProductoSKU::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $navigationLabel= 'Productos';
    protected static ?string $navigationGroup = 'Relacionados';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Card::make()
            ->schema([
            TextInput::make('nombre')->required()->maxLength(100),
            Textarea::make('descripcion')->nullable(),
            
            TextInput::make('codigo_lpn')
                ->label('Codigo General'),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('codigo_lpn')->label('Codigo general')->searchable(),
            TextColumn::make('nombre')->sortable()->searchable(),
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
                    // Acción personalizada: Asignar responsable
                BulkAction::make('asignarResponsable')
                ->label('Asignar responsable')
                ->icon('heroicon-m-user-plus')
                ->action(function (Collection $records, array $data) {
                    foreach ($records as $record) {
                        $record->inventario()->update(['id_usuario' => $data['id_usuario']]);
                    }
                })
                ->form([
                    Forms\Components\Select::make('id_usuario')
                        ->relationship('user', 'name')
                        ->required()
                        ->label('Selecciona un responsable'),
                ])
                ->deselectRecordsAfterCompletion(true),
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
            'index' => Pages\ListProductoSKUS::route('/'),
            'create' => Pages\CreateProductoSKU::route('/create'),
            'edit' => Pages\EditProductoSKU::route('/{record}/edit'),
        ];
    }
}
