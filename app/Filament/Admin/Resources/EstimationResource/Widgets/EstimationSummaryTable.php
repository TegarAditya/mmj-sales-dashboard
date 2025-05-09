<?php

namespace App\Filament\Admin\Resources\EstimationResource\Widgets;

use App\Models\Customer;
use App\Models\Semester;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\Url;

class EstimationSummaryTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected ?string $semesterId;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        $this->semesterId = ! is_null($this->filters['semester_id'] ?? null) ? 
                $this->filters['semester_id'] : 
                Semester::query()->first()->id;

        return $table
            ->query(
                Customer::query()
                    ->withSum(['estimationItems as estimation_items_sum_quantity' => function ($query) {
                        $query->whereHas('estimation', function ($q) {
                            if ($this->semesterId) $q->where('semester_id', $this->semesterId);
                        });
                    }], 'quantity')
                    ->withSum(['deliveryItems as delivery_items_sum_quantity' => function ($query) {
                        $query->whereHas('delivery', function ($q) {
                            if ($this->semesterId) $q->where('semester_id', $this->semesterId);
                        });
                    }], 'quantity')
            )
            ->heading(null)
            ->columns([
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->getStateUsing(fn() => Semester::find($this->semesterId)->name),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Customer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('estimation_items_sum_quantity')
                    ->label('Total Estimasi')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight(FontWeight::Bold)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('delivery_items_sum_quantity')
                    ->label('Total Terkirim')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight(FontWeight::Bold)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('remaining')
                    ->label('Sisa Estimasi')
                    ->sortable()
                    ->numeric()
                    ->default(0)
                    ->weight(FontWeight::Bold)
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        return $record->estimation_items_sum_quantity - $record->delivery_items_sum_quantity;
                    }),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->button()
                    ->url(fn(Customer $record): string => route('filament.admin.resources.customers.estimation', [$record->id, 'semester_id' => $this->semesterId])),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->button()
                    ->openUrlInNewTab(),
            ])->bulkActions([
                // ...
            ])->headerActions([
                // ...
            ])->emptyStateActions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
