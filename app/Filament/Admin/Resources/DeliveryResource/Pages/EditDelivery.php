<?php

namespace App\Filament\Admin\Resources\DeliveryResource\Pages;

use App\Filament\Admin\Resources\DeliveryResource;
use Filament\Actions;
use Filament\Notifications;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Mockery\Matcher\Not;

class EditDelivery extends EditRecord
{
    protected static string $resource = DeliveryResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        if ($this->record->invoice()->exists()) {
            $this->redirectRoute('filament.admin.resources.deliveries.view', [
                'record' => $this->record->id,
            ]);

            Notification::make()
                ->title('Pengiriman ini sudah memiliki Invoice')
                ->body('Anda tidak dapat mengedit pengiriman yang sudah memiliki invoice.')
                ->actions([
                    Notifications\Actions\Action::make('view_invoice')
                        ->label('Lihat Invoice')
                        ->url(route('filament.admin.resources.invoices.view', [$this->record->invoice->id])),
                ])
                ->danger()
                ->seconds(10)
                ->send();
        } else {
            $this->authorizeAccess();

            $this->fillForm();

            $this->previousUrl = url()->previous();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
