<?php

namespace App\Filament\Admin\Resources\EducationalClassResource\Pages;

use App\Filament\Admin\Resources\EducationalClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEducationalClasses extends ManageRecords
{
    protected static string $resource = EducationalClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
