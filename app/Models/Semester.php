<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasUserAuditable;

    protected $fillable = [
        'code',
        'name',
        'type',
        'start_date',
        'end_date',
    ];
}
