<?php

namespace App\Filament\Resources;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\FacturaResource\Pages;
use App\Filament\Resources\FacturaResource\RelationManagers;
use App\Models\Factura;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;


class FacturaResource extends Resource
{
    protected static ?string $model = Factura::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('no_factura')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull()
                    ->label('Numero de Factura'),
                TextInput::make('razon_social')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull()
                    ->label('Razon Social'),
                FileUpload::make('documento')
                    ->label('Archivo PDF')
                    //->disk('public')
                    ->columnSpanFull()
                    ->required()
                    ->directory('pdfs') // Ruta donde se va a guardar storage/app/public/pdfs
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048)
                    ->downloadable()
                    ->openable(),

                TextInput::make('tipo')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull()
                    ->label('Tipo'),
                TextArea::make('observaciones')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull()
                    ->label('Observaciones'),
                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_factura')->label('No. Factura')->sortable()->searchable(),
                TextColumn::make('razon_social')->label('Razon Social'),
                TextColumn::make('documento')->label('Documento')->url(fn($record) => asset('storage/' . $record->documento))
                    ->openUrlInNewTab(),
                TextColumn::make('tipo')->label('Tipo'),
                TextColumn::make('observaciones')->label('Observaciones'),
                TextColumn::make('fecha')->label('Fecha'),

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
            'index' => Pages\ListFacturas::route('/'),
            'create' => Pages\CreateFactura::route('/create'),
            'edit' => Pages\EditFactura::route('/{record}/edit'),
        ];
    }
}
