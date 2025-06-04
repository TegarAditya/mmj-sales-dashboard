<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use App\Filament\Admin\Resources\ProductResource\Widgets\StockMovementTable;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string | Htmlable
    {
        return new HtmlString(
            "<span class=\"text-2xl\">{$this->record->code}</span>"
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make([
                        Infolists\Components\TextEntry::make('code')
                            ->label('Kode Produk')
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('semester.name')
                            ->label('Semester')
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('type.name')
                            ->label('Tipe Buku')
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('publisher.name')
                            ->label('Penerbit')
                            ->formatStateUsing(fn($state, $record) => $state . " ({$record->publisher->code})")
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('curriculum.name')
                            ->label('Kurikulum')
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('educationalLevel.name')
                            ->label('Jenjang Pendidikan')
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('educationalClass.name')
                            ->label('Kelas')
                            ->size(TextEntrySize::Medium)
                            ->formatStateUsing(fn($state) => str_contains(strtolower($state), 'kelas') ? strtoupper($state) : 'KELAS-' . $state)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('educationalSubject.name')
                            ->label('Mata Pelajaran')
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('cost')
                            ->label('Harga Pokok')
                            ->visible(Auth::user()->hasRole('supe_admin'))
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn($state) => format_currency($state)),
                        Infolists\Components\TextEntry::make('price')
                            ->label('Harga Jual')
                            ->visible(Auth::user()->hasRole('super_admin'))
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn($state) => format_currency($state)),
                    ])
                        ->grow(true)
                        ->columns(2),
                    Infolists\Components\Section::make([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->inlineLabel()
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diubah Pada')
                            ->inlineLabel()
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->inlineLabel()
                            ->default('-')
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('createdBy.name')
                            ->label('Dibuat Oleh')
                            ->inlineLabel()
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('updatedBy.name')
                            ->label('Diubah Oleh')
                            ->inlineLabel()
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('deletedBy.name')
                            ->label('Dihapus Oleh')
                            ->inlineLabel()
                            ->default('-')
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),
                    ])
                        ->grow(false)
                        ->columns(2),
                ])
                    ->from('lg'),
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return [
            StockMovementTable::class,
        ];
    }
}
