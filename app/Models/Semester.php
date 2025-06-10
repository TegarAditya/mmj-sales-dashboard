<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasUserAuditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'start_date',
        'end_date',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function returnGoods()
    {
        return $this->hasMany(ReturnGood::class);
    }
}
