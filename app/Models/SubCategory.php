<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
    ];

    // relación uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // relacion uno a muchos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
