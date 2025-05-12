<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
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
        return $this->items->sum('discount');
    }

    public function getTotalDue()
    {
        return $this->getTotalPrice() - $this->getTotalDiscount();
    }
}
