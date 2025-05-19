<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'customer_id',
        'semester_id',
        'document_number',
        'date',
        'has_invoice',
    ];

    protected $casts = [
        'date' => 'datetime',
        'has_invoice' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function generateDocumentNumber()
    {
        $lastDelivery = self::orderBy('id', 'desc')->withTrashed()->first();
        $lastNumber = $lastDelivery ? (int) substr($lastDelivery->document_number, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'DLV-' . date('Ymd') . '-' . $newNumber;
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
