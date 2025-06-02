<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReturnGoodResource\Pages;
use App\Filament\Admin\Resources\ReturnGoodResource\RelationManagers;
use App\Models\Product;
use App\Models\ReturnGood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReturnGoodResource extends Resource
{
    protected static ?string $model = ReturnGood::class;

    protected static ?string $modelLabel = 'Retur';

    protected static ?string $navigationGroup = 'Pengiriman';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Retur')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('semester_id')
                            ->label('Semester Retur')
                            ->relationship('semester', 'name')
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Retur')
                            ->timezone('Asia/Jakarta')
                            ->format('Y-m-d')
                            ->required(),
                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Item Retur')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->hiddenLabel()
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->required()
                                    ->options(function ($get) {
                                        $customerId = $get('../../customer_id');
                                        $semesterId = $get('../../semester_id');

                                        if ($customerId && $semesterId) {
                                            return Product::whereHas('deliveries', function (Builder $query) use ($customerId, $semesterId) {
                                                $query
                                                    ->where('customer_id', $customerId)
                                                    ->where('semester_id', $semesterId);
                                            })->pluck('name', 'id');
                                        }

                                        return [];
                                    })
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListReturnGoods::route('/'),
            'create' => Pages\CreateReturnGood::route('/create'),
            'view' => Pages\ViewReturnGood::route('/{record}'),
            'edit' => Pages\EditReturnGood::route('/{record}/edit'),
        ];
    }

    public static function isDiscovered(): bool
    {
        return config('app.env')  !== 'production';
    }
}
