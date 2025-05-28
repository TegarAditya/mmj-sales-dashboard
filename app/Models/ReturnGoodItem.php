<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnGoodItem extends Model
{
    protected $fillable = [
        'return_good_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function returnGood()
    {
        return $this->belongsTo(ReturnGood::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->total = $model->quantity * $model->price;
        });

        static::updating(function ($model) {
            $model->total = $model->quantity * $model->price;
        });
    }
}
