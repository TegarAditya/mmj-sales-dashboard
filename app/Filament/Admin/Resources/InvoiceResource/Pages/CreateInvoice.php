<?php

namespace App\Filament\Admin\Resources\InvoiceResource\Pages;

use App\Filament\Admin\Resources\InvoiceResource;
use App\Models\Delivery;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\Url;

class CreateInvoice extends CreateRecord
{
    #[Url]
    public ?string $delivery_id = null;

    public ?array $data = [];

    public ?Delivery $delivery;

    protected static string $resource = InvoiceResource::class;

    public function mount(): void
    {
        $this->delivery = Delivery::find($this->delivery_id);

        if ($this->delivery) {
            $this->form->fill([
                'semester_id' => $this->delivery->semester_id,
                'customer_id' => $this->delivery->customer_id,
                'delivery_id' => $this->delivery->id,
                'date' => now(),
                'items' => $this->delivery->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                        'discount' => 0,
                    ];
                })->toArray(),
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pokok')
                    ->columns(2)
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->disabled($this->delivery !== null)
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->disabled($this->delivery !== null)
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('delivery_id')
                            ->label('Surat Jalan')
                            ->relationship('delivery', 'document_number')
                            ->disabled($this->delivery !== null)
                            ->dehydrated()
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->timezone('Asia/Jakarta')
                            ->default(now())
                            ->required(),
                    ]),
                Forms\Components\Section::make('Data Detail')
                    ->columns(1)
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->columns(4)
                            ->deletable(false)
                            ->addable(false)
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->relationship('product', 'name')
                                    ->disabled($this->delivery !== null)
                                    ->dehydrated()
                                    ->columnSpanFull()
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->disabled($this->delivery !== null)
                                    ->dehydrated()
                                    ->numeric()
                                    ->live()
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->live()
                                    ->required(),
                                Forms\Components\TextInput::make('discount')
                                    ->label('Diskon')
                                    ->live()
                                    ->numeric()
                                    ->prefix('Rp'),
                                Forms\Components\Placeholder::make('total_price')
                                    ->label('Total Harga')
                                    ->content(fn($get) => format_currency((int) $get('price') * (int) $get('quantity') - (int) $get('discount') * (int) $get('quantity')))
                            ]),
                    ]),
            ]);
    }
}
