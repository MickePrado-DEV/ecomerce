<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    // relacion muchos a muchos productos
    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(OptionProduct::class)
            ->withPivot('features')
            ->withTimestamps();
    }

    // relacion uno a muchos features
    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}
