<?php

namespace App\Filament\Admin\Resources\EstimationResource\Pages;

use App\Filament\Admin\Resources\EstimationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstimations extends ListRecords
{
    protected static string $resource = EstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
