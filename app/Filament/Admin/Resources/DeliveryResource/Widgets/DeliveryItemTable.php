<?php

namespace App\Filament\Admin\Resources\DeliveryResource\Widgets;

use App\Models\DeliveryItem;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\Builder;

class DeliveryItemTable extends BaseWidget
{
    use InteractsWithRecord;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DeliveryItem::query()->where('delivery_id', $this->record->id)->with(['product'])
            )
            ->heading(null)
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('product.code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->weight(FontWeight::Bold)
                    ->numeric()
                    ->summarize(Sum::make())
            ]);
    }
}
