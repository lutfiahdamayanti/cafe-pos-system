<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
    'category_id',
    'name',
    'description',
    'ingredients',
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
    'is_available',
    'large_price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function options()
    {
        return $this->hasMany(MenuOption::class);
    }
}