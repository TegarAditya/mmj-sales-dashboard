<?php

namespace App\Filament\Admin\Resources\StockInboundResource\Pages;

use App\Filament\Admin\Resources\StockInboundResource;
use App\Filament\Admin\Resources\StockInboundResource\Widgets;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStockInbound extends ViewRecord
{
    protected static string $resource = StockInboundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            Widgets\StockInboundItemsTable::class,
        ];
    }
}
