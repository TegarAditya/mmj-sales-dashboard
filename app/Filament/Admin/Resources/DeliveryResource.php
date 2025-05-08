<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DeliveryResource\Pages;
use App\Filament\Admin\Resources\DeliveryResource\RelationManagers;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Estimation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $modelLabel = 'Pengiriman';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Pengiriman';

    protected static ?string $navigationGroup = 'Pengiriman';

    protected static ?string $recordTitleAttribute = 'document_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pengiriman')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->required()
                            ->live()
                            ->relationship('customer', 'name'),
                        Forms\Components\Select::make('semester_id')
                            ->required()
                            ->live()
                            ->relationship('semester', 'name'),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Tanggal')
                            ->timezone('Asia/Jakarta')
                            ->format('Y-m-d H:i')
                            ->seconds(false)
                            ->required()
                            ->default(now()),
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->preload()
                                    ->required()
                                    ->searchable()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->options(function ($get): array {
                                        if (! $get('../../customer_id') || ! $get('../../semester_id')) return [];

                                        $items = Estimation::where('customer_id', $get('../../customer_id'))
                                            ->where('semester_id', $get('../../semester_id'))
                                            ->with('items.product')
                                            ->get()
                                            ->map(function ($estimation) {
                                                return $estimation->items->mapWithKeys(function ($item) {
                                                    return [$item->product->id => $item->product->name];
                                                });
                                            })
                                            ->toArray();

                                        return $items;
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nama Customer')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('has_invoice')
                    ->label('Status Invoice')
                    ->formatStateUsing(function ($state) {
                        if ($state === false) return 'Sudah Dibuat';

                        return 'Belum dibuat';
                    })
                    ->badge()
                    ->color(function ($state) {
                        if ($state === false) return Color::Blue;

                        return Color::Yellow;
                    }),
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
                    ->label('Dibuat Oleh')
                    ->badge()
                    ->sortable(),
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'view' => Pages\ViewDelivery::route('/{record}'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
