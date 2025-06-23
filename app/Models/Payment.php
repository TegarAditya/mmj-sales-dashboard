<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'customer_id',
        'semester_id',
        'document_number',
        'payment_date',
        'payment_method',
        'paid',
        'discount',
        'amount',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'paid' => 'decimal:2',
        'discount' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Tunai',
        'bca' => 'Bank BCA',
        'bri' => 'Bank BRI',
        'bni' => 'Bank BNI',
        'dki' => 'Bank Jakarta (DKI)',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function generateDocumentNumber()
    {
        $lastDelivery = self::orderBy('id', 'desc')->withTrashed()->first();
        $lastNumber = $lastDelivery ? (int) substr($lastDelivery->document_number, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'PAY-' . date('Ymd') . '-' . $newNumber;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->document_number = $model->generateDocumentNumber();
        });
    }
}
