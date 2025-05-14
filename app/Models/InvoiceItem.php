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
    }
}
