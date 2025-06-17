<?php

namespace App\Filament\Admin\Resources\ProductResource\Widgets;

use App\Models\DeliveryItem;
use App\Models\ReturnGoodItem;
use App\Models\StockInboundItem;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

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
            ->columns([
                Tables\Columns\TextColumn::make('no.')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor Referensi'),
                Tables\Columns\TextColumn::make('actor')
                    ->label('Keterangan')
                    ->html()
                    ->getStateUsing(fn($record) => $this->formatActorColumn($record)),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn($record) => match ($record->type) {
                        'INBOUND' => 'success',
                        'RETURN' => 'warning',
                        'DELIVERY' => 'primary',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->formatStateUsing(fn($state) => str_contains($state, '-') ? $state : '+' . $state)
                    ->summarize(Sum::make()),
                Tables\Columns\TextColumn::make('saldo')
                    ->label('Saldo')
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime(timezone: 'Asia/Jakarta', format: 'l, d F Y H:i:s'),
            ])
            ->defaultSort('created_at', 'asc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Referensi')
                    ->button()
                    ->url(fn($record) => match ($record->type) {
                        'INBOUND' => route('filament.admin.resources.stock-inbounds.view', $record->document_id),
                        'RETURN' => route('filament.admin.resources.return-goods.view', $record->document_id),
                        'DELIVERY' => route('filament.admin.resources.deliveries.view', $record->document_id),
                        default => '#',
                    }),
            ]);
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        $productId = $this->record->id;

        // Query for stock inflows (INBOUND)
        $inbounds = StockInboundItem::query()
            ->where('product_id', $productId)
            ->whereHas('stockInbound', fn(Builder $query) => $query->whereNull('deleted_at'))
            ->select([
                'stock_inbounds.document_number',
                'stock_inbound_items.stock_inbound_id as document_id',
                'stock_inbound_items.product_id',
                'stock_inbound_items.quantity',
                DB::raw("'INBOUND' as type"),
                'stock_inbounds.created_at',
                'suppliers.name as actor',
            ])
            ->join('stock_inbounds', 'stock_inbound_items.stock_inbound_id', '=', 'stock_inbounds.id')
            ->leftJoin('suppliers', 'stock_inbounds.supplier_id', '=', 'suppliers.id');

        // Query for stock inflows (RETURN)
        $returns = ReturnGoodItem::query()
            ->where('product_id', $productId)
            ->whereHas('returnGood', fn(Builder $query) => $query->whereNull('deleted_at'))
            ->select([
                'return_goods.document_number',
                'return_good_items.return_good_id as document_id',
                'return_good_items.product_id',
                'return_good_items.quantity',
                DB::raw("'RETURN' as type"),
                'return_goods.created_at',
                'customers.name as actor',
            ])
            ->join('return_goods', 'return_good_items.return_good_id', '=', 'return_goods.id')
            ->leftJoin('customers', 'return_goods.customer_id', '=', 'customers.id');

        // Query for stock outflows (DELIVERY)
        $deliveries = DeliveryItem::query()
            ->where('product_id', $productId)
            ->whereHas('delivery', fn(Builder $query) => $query->whereNull('deleted_at'))
            ->select([
                'deliveries.document_number',
                'delivery_items.delivery_id as document_id',
                'delivery_items.product_id',
                DB::raw('-ABS(delivery_items.quantity) as quantity'), // Quantity is negative
                DB::raw("'DELIVERY' as type"),
                'deliveries.created_at',
                'customers.name as actor',
            ])
            ->join('deliveries', 'delivery_items.delivery_id', '=', 'deliveries.id')
            ->leftJoin('customers', 'deliveries.customer_id', '=', 'customers.id');

        // Combine all queries using UNION
        $unionQuery = $inbounds
            ->union($returns)
            ->union($deliveries);

        // Wrap the UNION query and calculate the running total (saldo)
        // We can use any base model to start the query builder.
        return \App\Models\Product::query()
            ->withoutGlobalScope(SoftDeletingScope::class) // <-- This is the key
            ->fromSub($unionQuery, 'movements')
            ->selectRaw('*, SUM(quantity) OVER (ORDER BY created_at, document_id) as saldo')
            ->orderBy('created_at', 'asc')
            ->orderBy('document_id', 'asc');
    }

    protected function formatActorColumn($record): string
    {
        return new HtmlString(($record->type === 'DELIVERY' ? 'Untuk: ' : 'Dari: ') . "<span class=\"font-semibold\"/>{$record->actor}</span>");
    }
}
