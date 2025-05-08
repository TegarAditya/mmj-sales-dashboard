<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockInboundItem extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'stock_inbound_id',
        'product_id',
        'quantity',
        'cost',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cost' => 'integer',
    ];

    public function stockInbound()
    {
        return $this->belongsTo(StockInbound::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalCostAttribute()
    {
        return $this->quantity * $this->cost;
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
