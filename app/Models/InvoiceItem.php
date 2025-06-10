<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes, HasUserAuditable;
    
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'total_price',
        'total_discount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
        'discount' => 'integer',
        'total_price' => 'integer',
        'total_discount' => 'integer',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalPrice()
    {
        return $this->quantity * $this->price;
    }

    public function getTotalDiscount()
    {
        return $this->quantity * $this->discount;
    }

    public function updateInvoiceTotals(): void
    {
        $invoice = $this->invoice;
        if ($invoice) {
            $invoice->total_price = $invoice->items->sum('total_price');
            $invoice->total_discount = $invoice->items->sum('total_discount');
            $invoice->total_due = $invoice->total_price - $invoice->total_discount;
            $invoice->save();
        }
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->total_price = $model->getTotalPrice();
            $model->total_discount = $model->getTotalDiscount();
        });

        static::updating(function ($model) {
            $model->total_price = $model->getTotalPrice();
            $model->total_discount = $model->getTotalDiscount();
        });

        static::saved(function ($item) {
            $item->updateInvoiceTotals();
        });

        static::updated(function ($item) {
            $item->updateInvoiceTotals();
        });

        static::deleted(function ($item) {
            $item->updateInvoiceTotals();
        });

        static::restored(function ($item) {
            $item->updateInvoiceTotals();
        });

        static::forceDeleted(function ($item) {
            $item->updateInvoiceTotals();
        });
    }
}
