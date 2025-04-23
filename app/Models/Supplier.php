<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasUserAuditable;

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
}
