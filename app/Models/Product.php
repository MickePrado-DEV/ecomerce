<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'image_path',
        'price',
        'stock',
        'sub_category_id',
    ];

    // relacion uno a muchos inversa con subcategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // relacion uno a muchos Variant
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    // relacion muchos a muchos Options
    public function options()
    {
        return $this->belongsToMany(Option::class)
            ->using(OptionProduct::class)
            ->withPivot('features')
            ->withTimestamps();
    }
}
