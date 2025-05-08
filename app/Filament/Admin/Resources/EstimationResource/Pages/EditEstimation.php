<?php

namespace App\Filament\Admin\Resources\EstimationResource\Pages;

use App\Filament\Admin\Resources\EstimationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstimation extends EditRecord
{
    protected static string $resource = EstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
