<?php

namespace App\Filament\Admin\Resources\DeliveryResource\Pages;

use App\Filament\Admin\Resources\DeliveryResource;
use App\Filament\Admin\Resources\DeliveryResource\Widgets;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
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
                ->requiresConfirmation()
                ->url(route('filament.admin.resources.invoices.create', ['delivery_id' => $this->record->id]));
        }

        $headerActions[] = Actions\EditAction::make()->visible(! $this->record->invoice()->exists());

        return $headerActions;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Infolists\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('document_number')
                            ->label('No. Pengiriman')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('customer.name')
                            ->label('Nama Pelanggan'),
                        Infolists\Components\TextEntry::make('semester.name')
                            ->label('Semester'),
                        Infolists\Components\TextEntry::make('date')
                            ->label('Tanggal Pengiriman')
                            ->date(format: 'D, d:m:Y'),
                    ]),
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return [
            Widgets\DeliveryItemTable::class,
        ];
    }
}
