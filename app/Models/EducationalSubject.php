<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;

class EducationalSubject extends Model
{
    use HasUserAuditable;

    protected $fillable = [
        'code',
        'name',
    ];
}
