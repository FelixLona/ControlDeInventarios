<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResguardanteResource\Pages;
use App\Filament\Resources\ResguardanteResource\RelationManagers;
use App\Models\Resguardante;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;


class ResguardanteResource extends Resource
{
    protected static ?string $model = Resguardante::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Relacionados';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required()->columnSpanFull(),
                TextArea::make('descripcion')->columnSpanFull(),
                FileUpload::make('pdf')
                    ->label('Archivo PDF')
                    ->columnSpanFull()
                    ->required()
                    ->directory('pdfs') // Ruta donde se va a guardar storage/app/public/pdfs
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048)
                    ->downloadable()
                    ->openable(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->searchable(),
                TextColumn::make('descripcion')->searchable()->limit(50),
                TextColumn::make('pdf')
                    ->label('Archivo')
                    ->url(fn($record) => asset('storage/' . $record->pdf))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListResguardantes::route('/'),
            'create' => Pages\CreateResguardante::route('/create'),
            'edit' => Pages\EditResguardante::route('/{record}/edit'),
        ];
    }
}
