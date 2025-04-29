<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationalSubject extends Model
{
    use HasUserAuditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
    ];
}
