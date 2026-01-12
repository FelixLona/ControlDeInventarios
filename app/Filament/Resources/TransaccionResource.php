<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaccionResource\Pages;
use App\Filament\Resources\TransaccionResource\RelationManagers;
use App\Models\Transaccion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\ProductoSku;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use App\Exports\TransaccionesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaccionResource extends Resource
{
    protected static ?string $model = Transaccion::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Transacciones';
    public static function getModelLabel(): string
{
    return 'Transacciones'; 
}
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_producto_sku')
                ->relationship('producto', 'nombre')
                ->required()
                ->label('Producto'),

            Forms\Components\Select::make('id_usuario')
                ->relationship('usuario', 'name')
                ->required()
                ->label('Responsable'),

            Forms\Components\Select::make('tipo')
                ->options([
                    'entrada' => 'Entrada',
                    'salida' => 'Salida',
                ])
                ->required(),

            Forms\Components\TextInput::make('cantidad')
                ->numeric()
                ->required(),

            Forms\Components\Textarea::make('descripcion')
                ->nullable(),

            Forms\Components\TextInput::make('ubicacion')
                ->label('Ubicación actual')
                ->nullable(),
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
            Tables\Columns\TextColumn::make('usuario.name')->label('Realizado por')->searchable(),
            Tables\Columns\TextColumn::make('tipo')->label('Tipo')->searchable(),
            Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime()->searchable(),
            Tables\Columns\TextColumn::make('producto.nombre')->label('Producto'),
            //Tables\Columns\TextColumn::make('campo_modificado')->label('Campo'),
            Tables\Columns\TextColumn::make('descripcion')->label('Se realizo')->searchable(),
            //Tables\Columns\TextColumn::make('valor_anterior')->label('Anterior'),
            // Valor anterior
            TextColumn::make('valor_anterior')
                ->label('Anterior')
                ->formatStateUsing(function ($state, $record) {
                    if ($record->campo_modificado === 'id_ubicacion') {
                        return \App\Models\Ubicacion::find($state)?->nombre ?? 'Sin ubicación';
                    }

                    if ($record->campo_modificado === 'id_estado') {
                        return \App\Models\Estado::find($state)?->nombre ?? 'Sin estado';
                    }

                    if ($record->campo_modificado === 'id_responsable') {
                        return \App\Models\Responsable::find($state)?->nombre ?? 'Sin responsable';
                    }

                    return $state;
                }),
            //Tables\Columns\TextColumn::make('valor_nuevo')->label('Nuevo'),
            // Valor nuevo
            TextColumn::make('valor_nuevo')
                ->label('Nuevo')
                ->formatStateUsing(function ($state, $record) {
                    if ($record->campo_modificado === 'id_ubicacion') {
                        return \App\Models\Ubicacion::find($state)?->nombre ?? 'Sin ubicación';
                    }

                    if ($record->campo_modificado === 'id_estado') {
                        return \App\Models\Estado::find($state)?->nombre ?? 'Sin estado';
                    }

                    if ($record->campo_modificado === 'id_responsable') {
                        return \App\Models\Responsable::find($state)?->nombre ?? 'Sin responsable';
                    }

                    return $state;
                }),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('exportarAExcel')
    ->label('Exportar a Excel')
    ->icon('heroicon-m-arrow-down-tray')
    ->action(function (Collection $records) {
        return Excel::download(new class($records) implements FromCollection, WithHeadings, WithMapping
        {
            protected $records;

            public function __construct(Collection $records)
            {
                $this->records = $records;
            }

            // Define cómo mapear cada registro
            public function map($row): array
            {
                return [
                    $row->producto->nombre ?? 'Producto desconocido',
                    match ($row->campo_modificado) {
                        'id_ubicacion' => 'Ubicación',
                        'id_estado' => 'Estado',
                        'id_responsable' => 'Responsable',
                        default => $row->campo_modificado,
                    },
                    $row->valor_anterior_nombre ?? $row->valor_anterior,
                    $row->valor_nuevo_nombre ?? $row->valor_nuevo,
                    $row->usuario->name ?? 'Sin responsable',
                    \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i'),
                ];
            }

            // Define los encabezados del archivo Excel
            public function headings(): array
            {
                return [
                    'Producto',
                    'Campo Modificado',
                    'Valor Anterior',
                    'Valor Nuevo',
                    'Realizado por',
                    'Fecha y Hora',
                ];
            }

            // Devuelve la colección para exportar
            public function collection()
            {
                return $this->records;
            }
        }, 'transacciones_export_' . now()->format('Y-m-d_H:i') . '.xlsx');
    })
    ->deselectRecordsAfterCompletion(true),
                ]),
            ]);
    }
    

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canCreate(): bool
{
    return false;
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaccions::route('/'),
            'create' => Pages\CreateTransaccion::route('/create'),
            'edit' => Pages\EditTransaccion::route('/{record}/edit'),
        ];
    }
}
