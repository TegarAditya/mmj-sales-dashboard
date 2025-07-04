<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers;
use App\Filament\Imports\ProductImporter;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'Daftar Produk';

    protected static ?string $navigationGroup = 'Produk';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Data Produk')
                    ->schema([
                        Forms\Components\Select::make('semester_id')
                            ->label('Semester')
                            ->columnSpanFull()
                            ->required()
                            ->relationship('semester', 'name')
                            ->preload(),
                        Forms\Components\Select::make('type_id')
                            ->label('Tipe Buku')
                            ->required()
                            ->relationship('type', 'name')
                            ->preload(),
                        Forms\Components\Select::make('publisher_id')
                            ->label('Penerbit')
                            ->required()
                            ->relationship('publisher', 'name')
                            ->preload(),
                        Forms\Components\Select::make('curriculum_id')
                            ->label('Kurikulum')
                            ->required()
                            ->relationship('curriculum', 'name')
                            ->preload(),
                        Forms\Components\Select::make('educational_level_id')
                            ->label('Jenjang Pendidikan')
                            ->required()
                            ->relationship('educationalLevel', 'name')
                            ->preload(),
                        Forms\Components\Select::make('educational_class_id')
                            ->label('Kelas')
                            ->required()
                            ->relationship('educationalClass', 'name')
                            ->preload(),
                        Forms\Components\Select::make('educational_subject_id')
                            ->label('Mata Pelajaran')
                            ->required()
                            ->relationship('educationalSubject', 'name')
                            ->preload(),
                        Forms\Components\Select::make('page_count')
                            ->label('Jumlah Halaman')
                            ->required()
                            ->options(Product::PAGE_COUNT_OPTIONS),
                    ]),
                Forms\Components\Fieldset::make('Data Harga')
                    ->schema([
                        Forms\Components\TextInput::make('cost')
                            ->label('Harga Pokok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(9999999999)
                            ->default(0)
                            ->visible(fn() => static::canViewCost())
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Jual')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(9999999999)
                            ->default(0)
                            ->visible(fn() => static::canViewCost())
                            ->columnSpanFull(),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type.name')
                    ->label('Tipe')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('curriculum.name')
                    ->label('Kurikulum')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('educationalLevel.name')
                    ->label('Jenjang')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('educationalClass.name')
                    ->label('Kelas')
                    ->formatStateUsing(fn($state) => 'KELAS ' . $state)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('educationalSubject.name')
                    ->label('Mapel')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Harga Pokok')
                    ->sortable()
                    ->visible(fn() => static::canViewCost())
                    ->formatStateUsing(fn($state) => format_currency($state)),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Jual')
                    ->sortable()
                    ->formatStateUsing(fn($state) => format_currency($state)),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Diedit Oleh')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deletedBy.name')
                    ->label('Dihapus Oleh')
                    ->default('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn($record) => $record->stockInboundItems()->count() > 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewCost(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }
}
