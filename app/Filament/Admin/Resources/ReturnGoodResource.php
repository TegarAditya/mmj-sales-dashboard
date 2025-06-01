<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReturnGoodResource\Pages;
use App\Filament\Admin\Resources\ReturnGoodResource\RelationManagers;
use App\Models\ReturnGood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReturnGoodResource extends Resource
{
    protected static ?string $model = ReturnGood::class;

    protected static ?string $modelLabel = 'Retur';

    protected static ?string $navigationGroup = 'Pengiriman';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'name')
                    ->required(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Forms\Components\TextInput::make('document_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('total_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('updated_by')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('deleted_by')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturnGoods::route('/'),
            'create' => Pages\CreateReturnGood::route('/create'),
            'view' => Pages\ViewReturnGood::route('/{record}'),
            'edit' => Pages\EditReturnGood::route('/{record}/edit'),
        ];
    }

    public static function isDiscovered(): bool
    {
        return config('app.env')  !== 'production';
    }
}
