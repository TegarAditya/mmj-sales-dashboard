<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnGood extends Model
{
    use SoftDeletes, HasUserAuditable;
    
    protected $fillable = [
        'customer_id',
        'semester_id',
        'document_number',
        'note',
        'total_quantity',
        'total_price',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnGoodItem::class);
    }

    public function getTotalQuantityAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->items->sum('total');
    }

    public function generateDocumentNumber(): string
    {
        $lastDelivery = self::orderBy('id', 'desc')->withTrashed()->first();
        $lastNumber = $lastDelivery ? (int) substr($lastDelivery->document_number, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'RTR' . date('Ymd') . '-' . $newNumber;
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->document_number = $model->generateDocumentNumber();
        });

        static::updating(function ($model) {
            $model->document_number = $model->generateDocumentNumber();
        });

        static::deleting(function ($model) {
            $model->items()->delete();
        });

        static::forceDeleting(function ($model) {
            $model->items()->forceDelete();
        });

        static::restoring(function ($model) {
            $model->items()->restore();
        });
    }
}
