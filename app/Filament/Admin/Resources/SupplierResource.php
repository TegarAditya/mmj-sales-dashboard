<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SupplierResource\Pages;
use App\Filament\Admin\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $modelLabel = 'Supplier';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Supplier';

    protected static ?string $navigationLabel = 'Daftar Supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode Supplier')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('contact_person_name')
                    ->label('Nama Contact Person')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('contact_person_phone')
                    ->label('Nomor Telepon CP')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('website')
                    ->label('Website Supplier')
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Customer')
                    ->columns(2)
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('code')
                            ->label('Kode Customer'),
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama Customer'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Telepon'),
                        Infolists\Components\TextEntry::make('website')
                            ->label('Website'),
                        Infolists\Components\TextEntry::make('contact_person_name')
                            ->label('Nama CP'),
                        Infolists\Components\TextEntry::make('contact_person_phone')
                            ->label('Telepon CP'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),
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
                            ->visible(fn(Supplier $record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diedit Pada')
                            ->dateTime()
                            ->visible(fn(Supplier $record) => $record->updatedBy !== null),
                        Infolists\Components\TextEntry::make('deletedBy.name')
                            ->label('Dihapus Oleh')
                            ->visible(fn(Supplier $record) => $record->trashed())
                            ->default('-'),
                        Infolists\Components\TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->visible(fn(Supplier $record) => $record->trashed())
                            ->dateTime(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->label('Website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person_name')
                    ->label('Representatif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person_phone')
                    ->label('Nomor Telepon CP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Diedit oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deletedBy.name')
                    ->label('Dihapus oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSuppliers::route('/'),
        ];
    }
}
