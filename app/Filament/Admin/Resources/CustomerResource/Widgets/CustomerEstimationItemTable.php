<?php

namespace App\Filament\Admin\Resources\CustomerResource\Widgets;

use App\Models\Customer;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\Product;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Livewire\Attributes\Url;

class CustomerEstimationItemTable extends BaseWidget
{
    use InteractsWithRecord;

    #[Url]
    public $semester_id = '';

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->withSum(['estimationItems as estimated_quantity' => function ($query) {
                        $query->whereHas('estimation', function ($q) {
                            $q->where('customer_id', $this->record->id)
                                ->where('semester_id', $this->semester_id);
                        });
                    }], 'quantity')
                    ->withSum(['deliveryItems as delivered_quantity' => function ($query) {
                        $query->whereHas('delivery', function ($q) {
                            $q->where('customer_id', $this->record->id)
                                ->where('semester_id', $this->semester_id);
                        });
                    }], 'quantity')
                    ->whereHas('estimationItems', function ($query) {
                        $query->whereHas('estimation', function ($q) {
                            $q->where('customer_id', $this->record->id)
                                ->where('semester_id', $this->semester_id);
                        });
                    })
            )
            ->heading(null)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn(Product $record) => $this->getEstimationStatus($record))
                    ->badge()
                    ->color(fn($state) => $state === 'Belum Lengkap' ? 'danger' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_quantity')
                    ->label('Jumlah Estimasi')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->alignCenter()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('delivered_quantity')
                    ->label('Jumlah Terkirim')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->alignCenter()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('remaining_quantity')
                    ->label('Sisa Estimasi')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->alignCenter()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                    ->getStateUsing(fn(Product $record) => $this->getRemainingQuantity($record)),
            ]);
    }

    private function getEstimationStatus(Model $record): string
    {
        return $record->estimated_quantity > $record->delivered_quantity ? 'Belum Lengkap' : 'Lengkap';
    }

    private function getRemainingQuantity(Model $record): int
    {
        return max(0, $record->estimated_quantity - $record->delivered_quantity);
    }
}
