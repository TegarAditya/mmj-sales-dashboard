<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockInbound extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'supplier_id',
        'document_number',
        'quantity',
        'date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'date' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(StockInboundItem::class);
    }

    public function generateDocumentNumber()
    {
        $lastInbound = self::orderBy('id', 'desc')->withTrashed()->first();
        $lastNumber = $lastInbound ? (int) substr($lastInbound->document_number, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'INB-' . date('Ymd') . '-' . $newNumber;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->document_number = $model->generateDocumentNumber();
        });

        static::deleting(function ($model) {
            foreach ($model->items as $item) {
                $item->delete();
            }
        });

        static::restoring(function ($model) {
            foreach ($model->items()->withTrashed()->get() as $item) {
                $item->restore();
            }
        });
    }
}
