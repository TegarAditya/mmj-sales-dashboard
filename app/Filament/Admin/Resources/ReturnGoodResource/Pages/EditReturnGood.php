<?php

namespace App\Filament\Admin\Resources\ReturnGoodResource\Pages;

use App\Filament\Admin\Resources\ReturnGoodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturnGood extends EditRecord
{
    protected static string $resource = ReturnGoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
