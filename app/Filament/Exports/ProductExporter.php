<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Nama'),
            ExportColumn::make('description')
                ->label('Deskripsi'),
            ExportColumn::make('curriculum.code')
                ->label('Kode Kurikulum'),
            ExportColumn::make('semester.code')
                ->label('Kode Semester'),
            ExportColumn::make('EducationalLevel.code')
                ->label('Kode Jenjang'),
            ExportColumn::make('EducationalClass.code')
                ->label('Kode Kelas'),
            ExportColumn::make('EducationalSubjects.code')
                ->label('Kode Mapel'),
            ExportColumn::make('type.code')
                ->label('Kode Tipe'),
            ExportColumn::make('cost')
                ->label('Harga Pokok'),
            ExportColumn::make('price')
                ->label('Harga Jual'),
            ExportColumn::make('publisher.code'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your product export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
