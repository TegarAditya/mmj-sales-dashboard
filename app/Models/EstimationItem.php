<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstimationItem extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'estimation_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function estimation()
    {
        return $this->belongsTo(Estimation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
