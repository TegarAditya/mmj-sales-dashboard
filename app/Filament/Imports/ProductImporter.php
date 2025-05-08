<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('curriculum')
                ->label('Kode Kurikulum')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('semester')
                ->label('Kode Semester')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('educationalLevel')
                ->label('Kode Jenjang')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('educationalClass')
                ->label('Kode Kelas')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('educationalSubject')
                ->label('Kode Mata Pelajaran')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('publisher')
                ->label('Kode Penerbit')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('type')
                ->label('Kode Tipe')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('supplier')
                ->label('Kode Supplier')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('cost')
                ->label('Harga Pokok')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('price')
                ->label('Harga Jual')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Product
    {
        // return Product::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Product;
    }

    public static function getCsvDelimiter(): string
    {
        return ';';
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
