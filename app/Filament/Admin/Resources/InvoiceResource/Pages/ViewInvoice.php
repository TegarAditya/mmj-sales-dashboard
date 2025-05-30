<?php

namespace App\Filament\Admin\Resources\InvoiceResource\Pages;

use App\Filament\Admin\Resources\InvoiceResource;
use App\Filament\Admin\Resources\InvoiceResource\Widgets\InvoiceItemTable;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->url(route('print.invoice', $this->record->id))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
        ];
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
                            ->label('Kode Invoice')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('delivery.document_number')
                            ->label('Surat Jalan'),
                        Infolists\Components\TextEntry::make('customer.name')
                            ->label('Nama Pelanggan'),
                        Infolists\Components\TextEntry::make('semester.name')
                            ->label('Semester'),
                        Infolists\Components\TextEntry::make('date')
                            ->label('Tanggal Invoice')
                            ->date(),
                        Infolists\Components\TextEntry::make('total_due')
                            ->label('Total Tagihan')
                            ->formatStateUsing(fn($state) => format_currency($state))
                            ->weight('bold'),
                    ]),
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return [
            InvoiceItemTable::class,
        ];
    }
}
