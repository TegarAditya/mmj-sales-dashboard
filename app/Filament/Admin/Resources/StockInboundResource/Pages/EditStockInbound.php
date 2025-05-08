<?php

namespace App\Filament\Admin\Resources\StockInboundResource\Pages;

use App\Filament\Admin\Resources\StockInboundResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockInbound extends EditRecord
{
    protected static string $resource = StockInboundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
