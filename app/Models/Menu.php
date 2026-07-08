<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
    'category_id',
    'name',
    'description',
    'price',
    'image',
    'rating',
    'stock',
    'preparation_time',
    'calories',
    'allergen',
    'promo',
    'best_seller',
    'is_new',
    'is_available'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}