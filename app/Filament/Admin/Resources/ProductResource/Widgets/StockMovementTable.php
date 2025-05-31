<?php

namespace App\Filament\Admin\Resources\ProductResource\Widgets;

use App\Models\DeliveryItem;
use App\Models\ReturnGoodItem;
use App\Models\StockInboundItem;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class StockMovementTable extends BaseWidget
{
    use InteractsWithRecord;

    protected int | string | array $columnSpan = 2;

    public function getTableRecordKey(Model $record): string
    {
        return $record->document_number;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->heading('Riwayat Pergerakan Stok')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor Dokumen'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        $productId = $this->record->id;

        $inbounds = StockInboundItem::query()
            ->with('stockInbound')
            ->where('product_id', $productId)
            ->selectRaw("
                        stock_inbounds.document_number as document_number,
                        stock_inbound_items.product_id as product_id,
                        stock_inbound_items.quantity as quantity,
                        'INBOUND' as type,
                        stock_inbounds.created_at as created_at
                    ")
            ->join('stock_inbounds', 'stock_inbound_items.stock_inbound_id', '=', 'stock_inbounds.id');

        $returns = ReturnGoodItem::query()
            ->with('returnGood')
            ->where('product_id', $productId)
            ->selectRaw("
                        return_goods.document_number as document_number,
                        return_good_items.product_id as product_id,
                        return_good_items.quantity as quantity,
                        'RETURN' as type,
                        return_goods.created_at as created_at
                    ")
            ->join('return_goods', 'return_good_items.return_good_id', '=', 'return_goods.id');

        $deliveries = DeliveryItem::query()
            ->with('delivery')
            ->where('product_id', $productId)
            ->selectRaw("
                        deliveries.document_number as document_number,
                        delivery_items.product_id as product_id,
                        -ABS(delivery_items.quantity) as quantity,
                        'DELIVERY' as type,
                        deliveries.created_at as created_at
                    ")
            ->join('deliveries', 'delivery_items.delivery_id', '=', 'deliveries.id');

        return $inbounds
            ->union($returns)
            ->union($deliveries)
            ->orderBy('created_at', 'desc');
    }
}
