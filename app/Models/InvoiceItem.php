<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
        'discount' => 'integer',
        'total_price' => 'integer',
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
        return $this->quantity * ($this->price - $this->discount);
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->total_price = $model->getTotalPrice();
        });

        static::updating(function ($model) {
            $model->total_price = $model->getTotalPrice();
        });
    }
}
