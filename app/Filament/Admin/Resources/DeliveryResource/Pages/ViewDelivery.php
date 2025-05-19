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
        $headerActions = [
            Actions\Action::make('print')
                ->label('Print Surat Jalan')
                ->icon('heroicon-o-printer')
                ->url(route('print.delivery', $this->record->id))
                ->openUrlInNewTab(),
        ];

        if ($this->record->invoice()->exists()) {
            $headerActions[] = Actions\Action::make('view_invoice')
                ->label('Lihat Invoice')
                ->icon('heroicon-o-document-text')
                ->url(route('filament.admin.resources.invoices.view', [$this->record->invoice->id]));
        } else {
            $headerActions[] = Actions\Action::make('create_invoice')
                ->label('Buat Invoice')
                ->icon('heroicon-o-document-text')
                ->url(route('filament.admin.resources.invoices.create', ['delivery_id' => $this->record->id]));
        }

        $headerActions[] = Actions\EditAction::make();
        
        return $headerActions;
    }
}
