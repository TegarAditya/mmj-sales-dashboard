<?php

namespace App\Filament\Admin\Resources\ReturnGoodResource\Pages;

use App\Filament\Admin\Resources\ReturnGoodResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateReturnGood extends CreateRecord
{
    protected static string $resource = ReturnGoodResource::class;

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->requiresConfirmation()
            ->submit('create')
            ->keyBindings(['mod+s']);
    }
}
