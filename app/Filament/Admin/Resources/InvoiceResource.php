<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InvoiceResource\Pages;
use App\Filament\Admin\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $modelLabel = 'Invoice';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Invoice';

    protected static ?string $navigationGroup = 'Billing';

    protected static ?string $recordTitleAttribute = 'document_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pokok')
                    ->columns(2)
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->required(),
                        Forms\Components\Select::make('delivery_id')
                            ->label('Surat Jalan')
                            ->relationship('delivery', 'document_number')
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->timezone('Asia/Jakarta')
                            ->default(now())
                            ->required(),
                    ]),
                Forms\Components\Section::make('Data Detail')
                    ->columns(1)
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->columns(4)
                            ->schema([
                                Forms\Components\TextInput::make('product_id')
                                    ->label('Produk')
                                    ->columnSpanFull()
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->required(),
                                Forms\Components\TextInput::make('discount')
                                    ->label('Diskon'),
                                Forms\Components\TextInput::make('total_price')
                                    ->label('Total Harga')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_discount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_due')
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
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
