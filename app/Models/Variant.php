<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'image_path',
        'stock',
        'product_id',
    ];

    protected $appends = [
        'image',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_path
                ? Storage::url($this->image_path)
                : asset('img/img_nophoto.webp'),
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'feature_table')
            ->withPivot('option_id')
            ->withTimestamps();
    }
}
