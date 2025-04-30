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
        'type_id',
        'supplier_id',
        'semester_id',
        'curriculum_id',
        'educational_level_id',
        'educational_class_id',
        'educational_subject_id',
        'code',
        'name',
        'page_count',
        'price',
        'cost',
        'stock',
    ];

    protected $casts = [
        'page_count' => 'integer',
        'price' => 'integer',
        'cost' => 'integer',
        'stock' => 'integer',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
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

    public function getName(): string
    {
        $type = $this->type->name;
        $level = $this->educationalLevel->name;
        $curriculum = $this->curriculum->name;
        $subject = $this->educationalSubject->name;
        $class = $this->educationalClass->name;
        $semester = $this->semester->name;
        $supplier = $this->supplier->name;

        return "{$type} - {$level} - {$curriculum} - {$subject} - {$class} - {$semester} - ({$supplier})";
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->code = $model->getCode();
            $model->name = $model->getName();
        });

        static::updating(function ($model) {
            $model->code = $model->getCode();
            $model->name = $model->getName();
        });
    }
}
