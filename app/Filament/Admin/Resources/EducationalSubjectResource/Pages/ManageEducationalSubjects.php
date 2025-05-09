<?php

namespace App\Filament\Admin\Resources\EducationalSubjectResource\Pages;

use App\Filament\Admin\Resources\EducationalSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEducationalSubjects extends ManageRecords
{
    protected static string $resource = EducationalSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
