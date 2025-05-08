<?php

namespace App\Filament\Admin\Resources\DeliveryResource\Pages;

use App\Filament\Admin\Resources\DeliveryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDelivery extends ViewRecord
{
    protected static string $resource = DeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(route('print.delivery', $this->record->id))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
        ];
    }
}
