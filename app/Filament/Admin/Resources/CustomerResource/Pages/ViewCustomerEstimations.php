<?php

namespace App\Filament\Admin\Resources\CustomerResource\Pages;

use App\Filament\Admin\Resources\CustomerResource;
use App\Filament\Admin\Resources\CustomerResource\Widgets\CustomerEstimationItemTable;
use App\Models\Semester;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Url;

class ViewCustomerEstimations extends Page implements HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithRecord;

    #[Url]
    public $semester_id = '';

    protected ?Semester $semester;

    protected static string $resource = CustomerResource::class;

    protected static string $view = 'filament.admin.resources.customer-resource.pages.view-customer-estimations';

    protected static ?string $title = 'Detail Saldo Estimasi';

    public function mount(int | string $record): void
    {
        $this->setSemester();

        $this->record = $this->resolveRecord($record);
    }

    public function customerInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Infolists\Components\Section::make('Detail Customer')
                    ->icon('heroicon-o-user-group')
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama Customer'),
                        Infolists\Components\TextEntry::make('code')
                            ->label('Kode Customer'),
                        Infolists\Components\TextEntry::make('semester')
                            ->label('Semester Estimasi')
                            ->getStateUsing(fn() => $this->semester->name),
                        Infolists\Components\TextEntry::make('estimation_items_sum_quantity')
                            ->label('Total Estimasi')
                            ->getStateUsing(fn() => $this->getEstimationItems()->sum('quantity'))
                            ->numeric()
                            ->weight(700),
                    ]),
            ]);
    }

    public function setSemester(): void
    {
        $this->semester = Semester::query()
            ->whereId($this->semester_id)
            ->first() ?? null;
    }

    public function getEstimationItems()
    {
        return $this->record->estimationItems()
            ->where('semester_id', $this->semester_id)
            ->with(['product'])
            ->get();
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }

    protected function getWidgets(): array
    {
        return [
            CustomerEstimationItemTable::class,
        ];
    }

    public function getBreadcrumbs(): array
    {
        $breadcrumbItems = [
            'Estimasi',
            new HtmlString('<a href="' . route('filament.admin.pages.estimations.saldo') . '">Saldo</a>'),
        ];

        if ($this->record) {
            $breadcrumbItems[] = $this->record->name;
        }

        if ($this->semester) {
            $breadcrumbItems[] = $this->semester->name;
        }

        return $breadcrumbItems;
    }
}
