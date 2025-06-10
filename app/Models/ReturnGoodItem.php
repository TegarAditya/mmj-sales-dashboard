<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnGoodItem extends Model
{
    use SoftDeletes, HasUserAuditable;

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

    public function updateProductStock(): void
    {
        $product = $this->product;
        if ($product) {
            $product->stock = $product->getStock();
            $product->save();
        }
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->total = $model->quantity * $model->price;
        });

        static::updating(function ($model) {
            $model->total = $model->quantity * $model->price;
        });

        static::saved(function ($item) {
            $item->updateProductStock();
        });

        static::deleted(function ($item) {
            $item->updateProductStock();
        });

        static::restored(function ($item) {
            $item->updateProductStock();
        });

        static::forceDeleted(function ($item) {
            $item->updateProductStock();
        });
    }
}
