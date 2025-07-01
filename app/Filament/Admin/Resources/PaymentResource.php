<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Admin\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Billing';

    protected static ?string $recordTitleAttribute = 'document_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pembayaran')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->default(now())
                            ->timezone('Asia/Jakarta')
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options(Payment::PAYMENT_METHODS)
                            ->required()
                            ->preload()
                            ->native(false),
                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Rincian Pembayaran')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('paid')
                            ->label('Nominal Bayar')
                            ->prefix('IDR')
                            ->required()
                            ->numeric()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $set('amount', $state + ($get('discount') * ($state / 100) ?? 0));
                            })
                            ->live()
                            ->debounce(500)
                            ->default(0.00),
                        Forms\Components\TextInput::make('discount')
                            ->label('Diskon Pembayaran')
                            ->prefix('%')
                            ->required()
                            ->numeric()
                            ->debounce(500)
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $set('amount', $get('paid') + ($get('paid') * ($state / 100)));
                            })
                            ->live()
                            ->default(0.00),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Masuk')
                            ->prefix('IDR')
                            ->required()
                            ->numeric()
                            ->readOnly()
                            ->default(0.00),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Pembayaran')
                    ->columns(2)
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Infolists\Components\TextEntry::make('customer.name')
                            ->label('Customer'),
                        Infolists\Components\TextEntry::make('semester.name')
                            ->label('Semester'),
                        Infolists\Components\TextEntry::make('document_number')
                            ->label('Nomor Dokumen'),
                        Infolists\Components\TextEntry::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->dateTime(format: 'D, d M Y'),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->formatStateUsing(fn($state) => Payment::PAYMENT_METHODS[$state] ?? strtoupper($state)),
                        Infolists\Components\TextEntry::make('paid')
                            ->label('Nominal Bayar')
                            ->formatStateUsing(fn($state) => format_currency($state)),
                        Infolists\Components\TextEntry::make('discount')
                            ->label('Bonus Pembayaran')
                            ->formatStateUsing(fn($state, $record) => $state . '%' . ' (' . format_currency($record->paid * $state / 100) . ')'),
                        Infolists\Components\TextEntry::make('amount')
                            ->label('Jumlah Masuk')
                            ->formatStateUsing(fn($state) => format_currency($state)),
                    ]),
                Infolists\Components\Section::make('Data Audit')
                    ->columns(2)
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Infolists\Components\TextEntry::make('createdBy.name')
                            ->label('Dibuat Oleh'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updatedBy.name')
                            ->label('Diedit Oleh')
                            ->default('-')
                            ->visible(fn($record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diedit Pada')
                            ->dateTime()
                            ->visible(fn($record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('deletedBy.name')
                            ->label('Dihapus Oleh')
                            ->visible(fn($record) => $record->trashed())
                            ->default('-'),
                        Infolists\Components\TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->visible(fn($record) => $record->trashed())
                            ->dateTime(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('No. Kwitansi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->date(timezone: 'Asia/Jakarta', format: 'D, d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(fn($state) => Payment::PAYMENT_METHODS[$state] ?? strtoupper($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid')
                    ->label('Nominal Bayar')
                    ->formatStateUsing(fn($state) => format_currency($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('Bonus Pembayaran')
                    ->suffix('%')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah Masuk')
                    ->formatStateUsing(fn($state) => format_currency($state))
                    ->sortable(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
