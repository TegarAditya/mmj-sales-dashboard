<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'curriculum_id',
        'educational_level_id',
        'educational_class_id',
        'educational_subject_id',
        'code',
        'page_count',
        'price',
        'cost',
        'stock',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function educationalLevel(): BelongsTo
    {
        return $this->belongsTo(EducationalLevel::class);
    }

    public function educationalClass(): BelongsTo
    {
        return $this->belongsTo(EducationalClass::class);
    }

    public function educationalSubject(): BelongsTo
    {
        return $this->belongsTo(EducationalSubject::class);
    }

    public function getCode(): string
    {
        $type = $this->type->code;
        $semester = $this->semester->code;
        $curriculum = $this->curriculum->code;
        $level = $this->educationalLevel->code;
        $class = $this->educationalClass->code;
        $subject = $this->educationalSubject->code;
        $supplier = $this->supplier->code;

        return "{$type}-{$level}{$curriculum}{$subject}{$class}{$semester}/{$supplier}";
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->code = $model->getCode();
        });

        static::updating(function ($model) {
            $model->code = $model->getCode();
        });
    }
}
