<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryItem extends Model
{
    use SoftDeletes, HasUserAuditable;
    protected $fillable = [
        'delivery_id',
        'product_id',
        'quantity',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
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
