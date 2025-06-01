<?php

namespace App\Filament\Admin\Resources\ReturnGoodResource\Pages;

use App\Filament\Admin\Resources\ReturnGoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturnGoods extends ListRecords
{
    protected static string $resource = ReturnGoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
