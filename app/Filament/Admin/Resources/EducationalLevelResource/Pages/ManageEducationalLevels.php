<?php

namespace App\Filament\Admin\Resources\EducationalLevelResource\Pages;

use App\Filament\Admin\Resources\EducationalLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEducationalLevels extends ManageRecords
{
    protected static string $resource = EducationalLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
