<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Models\Inventario;
use App\Models\ProductoSku;
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
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Forms\Components\Actions;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Repeater;
use App\Models\ArticuloInventario;
use Illuminate\Support\Facades\DB;
use App\Exports\InventariosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Subgrupo;
use App\Models\Grupo;
use App\Models\Clase;
use App\Models\clase as ModelsClase;
use App\Models\Subclase;
use App\Models\Empleado;
use App\Models\Factura;
use App\Models\Ubicacion;



class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Inventario';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('num_activo')
                            ->label(__('Número de Activo'))
                            ->nullable(),
                        Forms\Components\Select::make('id_producto_sku')
                            ->relationship('producto', 'nombre')
                            ->label(__('Producto'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('cantidad')
                            ->numeric()
                            ->required(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_ubicacion')
                                    ->label(__('Ubicación'))
                                    ->options(
                                        Ubicacion::with(['aula.edificio'])->get()
                                            ->mapWithKeys(function ($ubicacion) {
                                                return [
                                                    $ubicacion->id_ubicacion =>
                                                    $ubicacion->nombre . ' - Aula ' .
                                                        $ubicacion->aula->nombre . ' - Edificio ' .
                                                        $ubicacion->aula->edificio->nombre
                                                ];
                                            })
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Forms\Components\Select::make('id_estado')
                                    ->relationship('estado', 'nombre')
                                    ->label(__('Estado'))
                                    ->searchable()
                                    ->nullable(),
                            ]),

                        // Grupo - Subgrupo - Clase - Subclase - COG
                        Forms\Components\Select::make('clave_grupo')
                            ->label('Grupo')
                            ->columnSpanFull()
                            ->options(Grupo::pluck('nombre', 'clave'))  // Muestra nombre, guarda clave
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('clave_subgrupo', null);
                                $set('clave_clase', null);
                                $set('clave_subclase', null);
                                // Actualizar COG al cambiar Grupo
                                $set('cog', '');
                            }),

                        Forms\Components\Select::make('clave_subgrupo')
                            ->label('Subgrupo')
                            ->columnSpanFull()
                            ->options(function (callable $get) {
                                $claveGrupo = $get('clave_grupo');
                                if (!$claveGrupo) return [];

                                $idGrupo = Grupo::where('clave', $claveGrupo)->value('id_grupo');
                                return Subgrupo::where('id_grupo', $idGrupo)->pluck('nombre', 'clave'); // Muestra nombre, guarda clave
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $set('clave_clase', null);
                                $set('clave_subclase', null);

                                // Actualizar COG
                                $grupo = $get('clave_grupo') ?? '';
                                $subgrupo = $get('clave_subgrupo') ?? '';
                                $clase = $get('clave_clase') ?? '';
                                $subclase = $get('clave_subclase') ?? '';
                                $set('cog', $grupo . $subgrupo . $clase . $subclase);
                            }),

                        Forms\Components\Select::make('clave_clase')
                            ->label('Clase')
                            ->columnSpanFull()
                            ->options(function (callable $get) {
                                $claveSubgrupo = $get('clave_subgrupo');
                                if (!$claveSubgrupo) return [];

                                $idSubgrupo = Subgrupo::where('clave', $claveSubgrupo)->value('id_subgrupo');
                                return Clase::where('id_subgrupo', $idSubgrupo)->pluck('nombre', 'clave'); // Muestra nombre, guarda clave
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $set('clave_subclase', null);

                                // Actualizar COG
                                $grupo = $get('clave_grupo') ?? '';
                                $subgrupo = $get('clave_subgrupo') ?? '';
                                $clase = $get('clave_clase') ?? '';
                                $subclase = $get('clave_subclase') ?? '';
                                $set('cog', $grupo . $subgrupo . $clase . $subclase);
                            }),

                        Forms\Components\Select::make('id_subclase')
                            ->label('Subclase')
                            ->columnSpanFull()
                            ->options(function (callable $get) {
                                $claveClase = $get('clave_clase');
                                if (!$claveClase) return [];

                                $idClase = Clase::where('clave', $claveClase)->value('id_clase');
                                return Subclase::where('id_clase', $idClase)->pluck('nombre', 'clave'); // Muestra nombre, guarda clave
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Guardar la clave de la subclase
                                $claveSubclase = Subclase::where('clave', $state)->value('clave');
                                $set('clave_subclase', $claveSubclase);

                                // Actualizar COG
                                $grupo = $get('clave_grupo') ?? '';
                                $subgrupo = $get('clave_subgrupo') ?? '';
                                $clase = $get('clave_clase') ?? '';
                                $subclase = $claveSubclase ?? '';
                                $set('cog', $grupo . $subgrupo . $clase . $subclase);
                            }),

                        Forms\Components\Hidden::make('clave_subclase')
                            ->dehydrated(false), 

                     



                        Forms\Components\TextInput::make('cog')
                            ->label(__('COG'))
                            ->disabled()
                            ->dehydrated(),

                        // Información adicional
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('ur')
                                    ->label(__('UR'))
                                    ->nullable()
                                    ->default('ITJMM'),

                                Forms\Components\TextInput::make('ua')
                                    ->label(__('UA'))
                                    ->nullable()
                                    ->default('ZA'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('anno')
                                    ->label(__('Año'))
                                    ->nullable(),

                                Forms\Components\TextInput::make('numero_consecutivo')
                                    ->label(__('Número Consecutivo'))
                                    ->numeric()
                                    ->nullable(),
                            ]),



                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('id_factura')
                                    ->label(__('Número de Factura'))
                                    ->nullable()
                                    ->relationship('factura', 'no_factura')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(2),

                                Forms\Components\Group::make([
                                    Forms\Components\Actions::make([
                                        FormAction::make('crearFactura')
                                            ->label('Nueva Factura')
                                            ->icon('heroicon-o-plus')
                                            ->button()
                                            ->modalHeading('Crear Nueva Factura')
                                            ->form([
                                                TextInput::make('no_factura')
                                                    ->required()
                                                    ->maxLength(100)
                                                    ->columnSpanFull()
                                                    ->label('Número de Factura'),
                                                TextInput::make('razon_social')
                                                    ->required()
                                                    ->maxLength(100)
                                                    ->columnSpanFull()
                                                    ->label('Razón Social'),
                                                FileUpload::make('documento')
                                                    ->label('Archivo PDF')
                                                    ->columnSpanFull()
                                                    ->required()
                                                    ->directory('pdfs')
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
                                                    ->maxLength(100)
                                                    ->columnSpanFull()
                                                    ->label('Observaciones'),
                                                DatePicker::make('fecha')
                                                    ->label('Fecha')
                                                    ->required(),
                                            ])
                                            ->action(function (array $data, callable $set) {
                                                $factura = Factura::create($data);
                                                $set('id_factura', $factura->id_factura);
                                            })
                                            ->successNotificationTitle('Factura creada correctamente'),
                                    ]),
                                ])
                                    ->columnSpan(1)
                                    ->extraAttributes(['class' => 'flex items-end justify-start']),
                            ]),


                    ]),


                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('proveedor')
                            ->label(__('Proveedor'))
                            ->nullable(),

                        Forms\Components\TextInput::make('modelo')
                            ->label(__('Modelo'))
                            ->nullable(),
                    ]),

                Forms\Components\TextInput::make('num_serie')
                    ->label(__('Número de Serie'))
                    ->nullable(),

                Forms\Components\Textarea::make('otras_especificaciones')
                    ->label(__('Otras Especificaciones'))
                    ->columnSpanFull()
                    ->rows(3),

                Forms\Components\TextInput::make('fuente_financiamiento')
                    ->label(__('Fuente de Financiamiento'))
                    ->nullable(),

                // Responsables
                Forms\Components\Select::make('id_responsable')
                    ->label('Responsable')
                    ->options(Empleado::pluck('nombre', 'id_empleado'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('id_resguardante')
                    ->label('Resguardante')
                    ->options(Empleado::pluck('nombre', 'id_empleado'))
                    ->searchable()
                    ->required(),

                // Número de inventario y fechas
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('num_inventario')
                            ->label(__('Número de Inventario'))
                            ->numeric()
                            ->nullable(),

                        Forms\Components\DatePicker::make('fecha_validacion')
                            ->label(__('Fecha de Validación'))
                            ->nullable(),
                    ]),

                Forms\Components\DatePicker::make('fecha_actualizacion')
                    ->label(__('Fecha de Actualización'))
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('producto.nombre')->label('Producto')->searchable(),
                TextColumn::make('cantidad')->searchable(),
                TextColumn::make('fecha_actualizacion')->dateTime()->label('Fecha de Creacion')->searchable(),
                TextColumn::make('ubicacion.nombre')->searchable(),
                TextColumn::make('estado.nombre')->searchable(),
                TextColumn::make('responsable.nombre')
                    ->label('Responsable Actual')
                    ->searchable(),


                TextColumn::make('cog')->label('COG')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                TableAction::make('generarResponsiva')
                    ->label('Generar Responsiva')
                    ->icon('heroicon-o-document')
                    ->action(function ($record) {
                        return redirect()->route('pdf.responsiva', ['responsable' => $record->id_responsable]);
                    })
                    ->visible(fn($record) => $record->id_responsable !== null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('exportarAExcel')
                        ->label('Exportar a Excel')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            return Excel::download(new InventariosExport($records), 'inventarios_export_' . now()->format('Ymd_His') . '.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(true),
                    BulkAction::make('asignarResponsable')
                        ->label('Asignar responsable')
                        ->icon('heroicon-m-user-plus')
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['id_responsable' => $data['id_responsable']]);
                            }
                        })
                        ->form([
                            Forms\Components\Select::make('id_responsable')
                                ->relationship('responsable', 'nombre')
                                ->required()
                                ->searchable()
                                ->label('Selecciona un responsable'),
                        ])
                        ->deselectRecordsAfterCompletion(true),
                    // Acción masiva: Asignar ubicación
                    BulkAction::make('asignarUbicacion')
                        ->label('Asignar ubicación')
                        ->icon('heroicon-m-map-pin')
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['id_ubicacion' => $data['ubicacion']]);
                            }
                        })
                        ->form([
                            Forms\Components\Select::make('ubicacion')
                                ->relationship('ubicacion', 'nombre')
                                ->required()
                                ->searchable()
                                ->label('Selecciona una ubicación'),
                        ])
                        ->deselectRecordsAfterCompletion(true),

                    // Acción masiva: Asignar estado
                    BulkAction::make('asignarEstado')
                        ->label('Asignar estado')
                        ->icon('heroicon-m-tag')
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['id_estado' => $data['estado']]);
                            }
                        })
                        ->form([
                            Forms\Components\Select::make('estado')
                                ->relationship('estado', 'nombre')
                                ->required()
                                ->searchable()
                                ->label('Selecciona un estado'),
                        ])
                        ->deselectRecordsAfterCompletion(true),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }
    public function producto()
    {
        return $this->belongsTo(ProductoSku::class, 'id_producto_sku');
    }





    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventarios::route('/'),
            'create' => Pages\CreateInventario::route('/create'),
            'edit' => Pages\EditInventario::route('/{record}/edit'),
        ];
    }
}
