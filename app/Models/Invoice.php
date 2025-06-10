<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'customer_id',
        'semester_id',
        'delivery_id',
        'document_number',
        'date',
        'total_price',
        'total_discount',
        'total_due',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getTotalPrice()
    {
        return $this->items->sum('total_price');
    }

    public function getTotalDiscount()
    {
        return $this->items->sum('total_discount');
    }

    public function getTotalDue()
    {
        return $this->getTotalPrice() - $this->getTotalDiscount();
    }

    public function generateDocumentNumber()
    {
        $lastDelivery = self::orderBy('id', 'desc')->withTrashed()->first();
        $lastNumber = $lastDelivery ? (int) substr($lastDelivery->document_number, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'INV-' . date('Ymd') . '-' . $newNumber;
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

            if ($model->delivery) {
                $model->delivery->has_invoice = false;
                $model->delivery->save();
            }
        });

        static::restoring(function ($model) {
            foreach ($model->items()->withTrashed()->get() as $item) {
                $item->restore();
            }

            if ($model->delivery) {
                $model->delivery->has_invoice = true;
                $model->delivery->save();
            }
        });
    }
}
