<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOption extends Model
{
    protected $fillable=[
        'menu_id',
        'name'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function values()
    {
        return $this->hasMany(MenuOptionValue::class);
    }
}
