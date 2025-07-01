<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StockInboundResource\Pages;
use App\Filament\Admin\Resources\StockInboundResource\RelationManagers;
use App\Models\Product;
use App\Models\StockInbound;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockInboundResource extends Resource
{
    protected static ?string $model = StockInbound::class;

    protected static ?string $modelLabel = 'Stok Masuk';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $navigationGroup = 'Stok';

    protected static ?string $recordTitleAttribute = 'document_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Supplier')
                    ->schema([
                        Forms\Components\Select::make('supplier_id')
                            ->label('Supplier')
                            ->required()
                            ->relationship('supplier', 'name')
                            ->preload()
                            ->searchable()
                            ->live(),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Tanggal')
                            ->timezone('Asia/Jakarta')
                            ->format('Y-m-d H:i')
                            ->seconds(false)
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Detail Stok Masuk')
                    ->description('Daftar produk yang masuk ke dalam stok')
                    ->hiddenOn('view')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->hiddenLabel()
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->required()
                                    ->options(function (callable $get) {
                                        return Product::query()
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->columns(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Stok Masuk')
                    ->columns(2)
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->schema([
                        Infolists\Components\TextEntry::make('supplier.name')
                            ->label('Supplier')
                            ->inlineLabel()
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('date')
                            ->label('Tanggal')
                            ->inlineLabel()
                            ->dateTime('D, d M Y H:i', 'Asia/Jakarta')
                            ->weight(FontWeight::Bold),
                    ]),
                Infolists\Components\Section::make('Data Audit')
                    ->columns(2)
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Infolists\Components\TextEntry::make('createdBy.name')
                            ->label('Dibuat Oleh')
                            ->inlineLabel(),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->inlineLabel()
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updatedBy.name')
                            ->label('Diedit Oleh')
                            ->inlineLabel()
                            ->default('-')
                            ->visible(fn($record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diedit Pada')
                            ->inlineLabel()
                            ->dateTime()
                            ->visible(fn($record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('deletedBy.name')
                            ->label('Dihapus Oleh')
                            ->inlineLabel()
                            ->visible(fn($record) => $record->trashed())
                            ->default('-'),
                        Infolists\Components\TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->inlineLabel()
                            ->visible(fn($record) => $record->trashed())
                            ->dateTime(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('No. Masuk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->dateTime('D, d M Y H:i', 'Asia/Jakarta')
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat oleh')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Diedit oleh')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deletedBy.name')
                    ->label('Dihapus oleh')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListStockInbounds::route('/'),
            'create' => Pages\CreateStockInbound::route('/create'),
            'view' => Pages\ViewStockInbound::route('/{record}'),
            'edit' => Pages\EditStockInbound::route('/{record}/edit'),
        ];
    }
}
