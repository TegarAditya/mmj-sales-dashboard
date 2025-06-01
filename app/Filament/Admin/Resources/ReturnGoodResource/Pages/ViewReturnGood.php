<?php

namespace App\Filament\Admin\Resources\ReturnGoodResource\Pages;

use App\Filament\Admin\Resources\ReturnGoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReturnGood extends ViewRecord
{
    protected static string $resource = ReturnGoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
