<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'image_path',
        'product_id',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(get: fn() => $this->image_path  ? Storage::url($this->image_path) : asset('img/img_nophoto.webp'));
    }

    // relacion uno a muchos inversa
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // relacion muchos a muchos features
    public function features()
    {
        return $this->belongsToMany(Feature::class)->withTimestamps();
    }
}
