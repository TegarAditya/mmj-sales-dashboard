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
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_quantity')
                    ->label('Jumlah Estimasi')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->sum(['estimationItems'], 'quantity')
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('delivered_quantity')
                    ->label('Jumlah Terkirim')
                    ->sortable()
                    ->default(0)
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('remaining_quantity')
                    ->label('Sisa Estimasi')
                    ->sortable()
                    ->default(0)
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                    ->getStateUsing(fn(Product $record) => $record->estimated_quantity - $record->delivered_quantity),
            ]);
    }
}
