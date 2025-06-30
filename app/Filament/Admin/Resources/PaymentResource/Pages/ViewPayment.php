<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print Kwitansi')
                ->icon('heroicon-o-printer')
                ->url(route('print.payment', $this->record->id))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
        ];
    }
}
