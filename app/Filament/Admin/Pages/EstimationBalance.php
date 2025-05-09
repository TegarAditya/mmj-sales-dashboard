<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Resources\EstimationResource\Widgets\EstimationSummaryTable;
use App\Models\Semester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;

class EstimationBalance extends Page
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Saldo Estimasi';

    protected static ?string $navigationGroup = 'Estimasi';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'estimations/saldo';

    protected static ?string $title = 'Saldo Estimasi';

    protected static string $view = 'filament.admin.pages.estimation-balance';

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_id')
                    ->label('Semester')
                    ->inlineLabel()
                    ->options(Semester::all()->pluck('name', 'id'))
                    ->default(Semester::query()->latest('start_date')->first()->id)
                    ->reactive()
                    ->selectablePlaceholder(false)
                    ->native(false)
                    ->columnSpan(2),
            ]);
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getBreadcrumbs(): array
    {
        return ['Estimasi', 'Saldo'];
    }

    protected function getWidgets(): array
    {
        return [
            EstimationSummaryTable::class,
        ];
    }
}
