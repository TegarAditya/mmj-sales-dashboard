<?php

namespace App\Filament\Admin\Resources;

use App\Enums\SemesterEnum;
use App\Filament\Admin\Resources\SemesterResource\Pages;
use App\Models\Semester;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Master Buku';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Semester';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Semester')
                    ->placeholder('Contoh: SEMESTER GANJIL 2025/2026')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('code')
                    ->label('Kode Semester')
                    ->placeholder('Contoh: 0126')
                    ->required(),
                Select::make('type')
                    ->label('Semester')
                    ->options(SemesterEnum::class)
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Tanggal Awal')
                    ->helperText('Tanggal awal digunakan untuk menentukan periode semester')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->helperText('Tanggal awal digunakan untuk menentukan periode semester')
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Semester')
                    ->columns(2)
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('code')
                            ->label('Kode Kurikulum'),
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama Kurikulum'),
                    ]),
                Infolists\Components\Section::make('Data Audit')
                    ->columns(2)
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Infolists\Components\TextEntry::make('createdBy.name')
                            ->label('Dibuat Oleh'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updatedBy.name')
                            ->label('Diedit Oleh')
                            ->default('-')
                            ->visible(fn($record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diedit Pada')
                            ->dateTime()
                            ->visible(fn($record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('deletedBy.name')
                            ->label('Dihapus Oleh')
                            ->visible(fn($record) => $record->trashed())
                            ->default('-'),
                        Infolists\Components\TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->visible(fn($record) => $record->trashed())
                            ->dateTime(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Semester')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Kode Semester')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe Semester')
                    ->badge()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Tanggal Awal')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Tanggal Akhir')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSemesters::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
