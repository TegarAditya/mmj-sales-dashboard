<?php

namespace App\Filament\Admin\Resources\InvoiceResource\Widgets;

use App\Models\InvoiceItem;
use App\Models\Product;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class InvoiceItemTable extends BaseWidget
{
    use InteractsWithRecord;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                InvoiceItem::query()
                    ->where('invoice_id', $this->record->id)
                    ->with(['product'])
                    ->selectRaw('*, total_price - total_discount as total_discounted_price')
            )
            ->heading(null)
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable()
                    ->numeric()
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->sortable()
                    ->numeric()
                    ->formatStateUsing(fn($state) => format_currency($state)),
                Tables\Columns\TextColumn::make('discount')
                    ->label('Diskon')
                    ->sortable()
                    ->numeric()
                    ->formatStateUsing(fn($state) => format_currency($state)),
                Tables\Columns\TextColumn::make('total_discounted_price')
                    ->label('Total Harga')
                    ->sortable()
                    ->numeric()
                    ->formatStateUsing(fn($state) => format_currency($state)),
            ]);
    }
}
