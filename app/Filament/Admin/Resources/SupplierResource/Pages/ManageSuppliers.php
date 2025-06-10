<?php

namespace App\Filament\Admin\Resources\SupplierResource\Pages;

use App\Filament\Admin\Resources\SupplierResource;
use App\Filament\Imports\SupplierImporter;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSuppliers extends ManageRecords
{
    protected static string $resource = SupplierResource::class;

    protected static ?string $title = 'Daftar Supplier';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(SupplierImporter::class)
                ->label('Impor Supplier')
                ->icon('heroicon-o-arrow-down-tray')
                ->modalHeading('Impor Supplier')
                ->modalDescription('Impor data Supplier dari file CSV.')
                ->successNotificationTitle('Supplier berhasil diimpor.'),
            Actions\ExportAction::make()
                ->exporter(SupplierImporter::class)
                ->label('Ekspor Supplier')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalHeading('Ekspor Supplier')
                ->modalDescription('Ekspor data Supplier ke file CSV.')
                ->successNotificationTitle('Supplier berhasil diekspor.'),
            Actions\CreateAction::make(),
        ];
    }
}
