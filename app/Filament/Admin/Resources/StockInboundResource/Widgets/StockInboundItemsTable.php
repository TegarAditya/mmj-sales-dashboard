<?php

namespace App\Filament\Admin\Resources\StockInboundResource\Widgets;

use App\Models\StockInboundItem;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StockInboundItemsTable extends BaseWidget
{
    use InteractsWithRecord;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                StockInboundItem::query()
                    ->where('stock_inbound_id', $this->record->id)
                    ->with('product', function ($query) {
                        $query->withTrashed();
                    })
            )
            ->heading(null)
            ->columns([
                Tables\Columns\TextColumn::make('no.')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('product.code')
                    ->label('Kode Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable()
                    ->numeric()
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('product.deleted_at')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => $record->product->trashed() ? 'danger' : 'success')
                    ->getStateUsing(fn($record) => $record->product->trashed() ? 'Dihapus' : 'Tersedia'),
            ])
            ->recordUrl(fn($record) => route('filament.admin.resources.products.view', $record->product_id));
    }
}
