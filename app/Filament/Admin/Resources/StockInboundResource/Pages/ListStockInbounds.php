<?php

namespace App\Filament\Admin\Resources\StockInboundResource\Pages;

use App\Filament\Admin\Resources\StockInboundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockInbounds extends ListRecords
{
    protected static string $resource = StockInboundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
