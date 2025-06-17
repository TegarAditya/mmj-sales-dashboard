<?php

namespace App\Models;

use App\Traits\HasUserAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasUserAuditable;

    protected $fillable = [
        'publisher_id',
        'type_id',
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

    public const PAGE_COUNT_OPTIONS = [
        64 => '64 halaman',
        80 => '80 halaman',
        96 => '96 halaman',
        128 => '128 halaman',
        144 => '144 halaman',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
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

    public function stockInboundItems(): HasMany
    {
        return $this->hasMany(StockInboundItem::class);
    }

    public function estimationItems(): HasMany
    {
        return $this->hasMany(EstimationItem::class);
    }

    public function deliveries(): BelongsToMany
    {
        return $this->belongsToMany(Delivery::class, 'delivery_items')
            ->withPivot('quantity', 'price', 'total')
            ->withTimestamps();
    }

    public function deliveryItems(): HasMany
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'invoice_items')
            ->withPivot('quantity', 'price', 'discount', 'total_price', 'total_discount', 'total_due')
            ->withTimestamps();
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function returnGoods(): BelongsToMany
    {
        return $this->belongsToMany(ReturnGood::class, 'return_good_items')
            ->withPivot('quantity', 'price', 'total')
            ->withTimestamps();
    }

    public function returnGoodItems(): HasMany
    {
        return $this->hasMany(ReturnGoodItem::class);
    }

    public function getStock(): int
    {
        $addition = (int) $this->stockInboundItems()->sum('quantity') + (int) $this->returnGoodItems()->sum('quantity');
        $subtraction = (int) $this->deliveryItems()->sum('quantity');
        return $addition - $subtraction;
    }

    public function getCode(): string
    {
        $type = $this->type->code;
        $semester = $this->semester->code;
        $curriculum = $this->curriculum->code;
        $level = $this->educationalLevel->code;
        $class = $this->educationalClass->code;
        $subject = $this->educationalSubject->code;
        $publisher = $this->publisher->code;

        return "{$type}-{$level}{$curriculum}{$subject}{$class}{$semester}/{$publisher}";
    }

    public function getName(): string
    {
        $type = $this->type->name;
        $level = $this->educationalLevel->name;
        $curriculum = $this->curriculum->name;
        $subject = $this->educationalSubject->name;
        $class = $this->educationalClass->name;
        $semester = $this->semester->name;
        $publisher = $this->publisher->name;

        return "{$type} - {$level} - {$curriculum} - {$subject} - {$class} - {$semester} - ({$publisher})";
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

        static::deleting(function ($model) {
            if ($model->stockInboundItems()->count() > 0) {
                return false;
            }

            $model->stockInboundItems()->delete();
            $model->estimationItems()->delete();
            $model->deliveryItems()->delete();
            $model->invoiceItems()->delete();
            $model->returnGoodItems()->delete();
        });

        static::restoring(function ($model) {
            $model->stockInboundItems()->withTrashed()->restore();
            $model->estimationItems()->withTrashed()->restore();
            $model->deliveryItems()->withTrashed()->restore();
            $model->invoiceItems()->withTrashed()->restore();
            $model->returnGoodItems()->withTrashed()->restore();
        });

        static::forceDeleting(function ($model) {
            $model->stockInboundItems()->forceDelete();
            $model->estimationItems()->forceDelete();
            $model->deliveryItems()->forceDelete();
            $model->invoiceItems()->forceDelete();
            $model->returnGoodItems()->forceDelete();
        });
    }
}
