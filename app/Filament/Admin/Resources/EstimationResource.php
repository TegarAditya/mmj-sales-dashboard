<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EstimationResource\Pages;
use App\Filament\Admin\Resources\EstimationResource\RelationManagers;
use App\Models\Estimation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstimationResource extends Resource
{
    protected static ?string $model = Estimation::class;

    protected static ?string $modelLabel = 'Estimasi';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Estimasi Pesanan';

    protected static ?string $navigationGroup = 'Estimasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('customer', 'name'),
                        Forms\Components\Select::make('semester_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('semester', 'name'),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Tanggal')
                            ->timezone('Asia/Jakarta')
                            ->format('Y-m-d H:i')
                            ->seconds(false)
                            ->required()
                            ->default(now()),
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->preload()
                                    ->required()
                                    ->searchable()
                                    ->relationship('product', 'name'),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Produk')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('No. Estimasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->dateTime('D, d M Y H:i', 'Asia/Jakarta')
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
            'index' => Pages\ListEstimations::route('/'),
            'create' => Pages\CreateEstimation::route('/create'),
            'view' => Pages\ViewEstimation::route('/{record}'),
            'edit' => Pages\EditEstimation::route('/{record}/edit'),
        ];
    }
}
