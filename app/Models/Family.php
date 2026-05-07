<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // relación uno a muchos
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // relación uno a muchos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
