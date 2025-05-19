<?php

namespace App\Filament\Admin\Widgets;

use App\Models\DeliveryItem;
use App\Models\EstimationItem;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SummaryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Stok Produk', number_format(Product::sum('stock'), 0, ',', '.'))
                ->description('Total produk yang tersedia'),
            Stat::make('Jumlah Estimasi', number_format(EstimationItem::sum('quantity'), 0, ',', '.'))
                ->description('Total estimasi yang telah dibuat'),
            Stat::make('Jumlah Terkirim', DeliveryItem::sum('quantity'))
                ->description('Total barang yang telah terkirim'),
        ];
    }
}
