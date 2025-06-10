<?php

namespace App\Filament\Admin\Resources\CustomerResource\Pages;

use App\Filament\Admin\Resources\CustomerResource;
use App\Filament\Exports\CustomerExporter;
use App\Filament\Imports\CustomerImporter;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected static ?string $title = 'Daftar Customer';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(CustomerImporter::class)
                ->label('Impor Customer')
                ->icon('heroicon-o-arrow-down-tray')
                ->modalHeading('Impor Customer')
                ->modalDescription('Impor data customer dari file CSV.')
                ->successNotificationTitle('Customer berhasil diimpor.'),
            Actions\ExportAction::make()
                ->exporter(CustomerExporter::class)
                ->label('Ekspor Customer')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalHeading('Ekspor Customer')
                ->modalDescription('Ekspor data customer ke file CSV.')
                ->successNotificationTitle('Customer berhasil diekspor.'),
            Actions\CreateAction::make(),
        ];
    }
}
