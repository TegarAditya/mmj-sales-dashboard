<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimation extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'customer_id',
        'semester_id',
        'document_number',
        'date',
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

    public function items()
    {
        return $this->hasMany(EstimationItem::class);
    }

    public function generateDocumentNumber()
    {
        $lastEstimation = self::orderBy('id', 'desc')->withTrashed()->first();
        $lastNumber = $lastEstimation ? (int) substr($lastEstimation->document_number, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'EST-' . date('Ymd') . '-' . $newNumber;
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
