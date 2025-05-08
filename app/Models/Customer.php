<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasUserAuditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'website',
        'contact_person_name',
        'contact_person_phone'
    ];

    public function estimations()
    {
        return $this->hasMany(Estimation::class);
    }

    public function estimationItems()
    {
        return $this->hasManyThrough(EstimationItem::class, Estimation::class)
                ->select('estimation_items.*')
                ->distinct('product_id');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function deliveryItems()
    {
        return $this->hasManyThrough(DeliveryItem::class, Delivery::class)
                ->select('delivery_items.*')
                ->distinct('product_id');
    }
}
