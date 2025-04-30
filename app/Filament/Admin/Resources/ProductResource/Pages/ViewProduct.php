<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make([
                        Infolists\Components\TextEntry::make('code')
                            ->label('Nama Produk')
                            ->size(TextEntrySize::Medium)
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('semester.name')
                            ->label('Semester')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('type.name')
                            ->label('Tipe Buku')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('supplier.name')
                            ->label('Penerbit')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('curriculum.name')
                            ->label('Kurikulum')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('educationalLevel.name')
                            ->label('Jenjang Pendidikan')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('educationalClass.name')
                            ->label('Kelas')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('educationalSubject.name')
                            ->label('Mata Pelajaran')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('cost')
                            ->label('Harga Pokok')
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn ($state) => format_currency($state)),
                        Infolists\Components\TextEntry::make('price')
                            ->label('Harga Jual')
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn ($state) => format_currency($state)),
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
                    ->from('md'),
            ]);
    }
}
