<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\ProductImporter;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProducts extends ManageRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Ekspor Produk')
                ->exporter(ProductExporter::class),
            Actions\ImportAction::make()
                ->importer(ProductImporter::class)
                ->label('Impor Produk'),
            Actions\CreateAction::make(),
        ];
    }
}
