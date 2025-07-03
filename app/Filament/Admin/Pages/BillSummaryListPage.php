<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\BillSummaryListTable;
use App\Models\Semester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;

class BillSummaryListPage extends Page
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Ringkasan Tagihan';

    protected static ?string $navigationGroup = 'Billing';

    protected static ?string $slug = 'billing';

    protected static ?string $title = 'Ringkasan Tagihan';

    protected static string $view = 'filament.admin.pages.bill-summary-list-page';

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_id')
                    ->label('Semester')
                    ->inlineLabel()
                    ->options(Semester::all()->pluck('name', 'id'))
                    ->default(function () {
                        $semester = Semester::query()->latest('start_date')->first();

                        return $semester ? $semester->id : null;
                    })
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

    protected function getWidgets(): array
    {
        return [
            BillSummaryListTable::class,
        ];
    }
}
