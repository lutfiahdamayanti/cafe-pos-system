<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOptionValue extends Model
{
    protected $fillable=[
        'menu_option_id',
        'value',
        'extra_price'
    ];

    public function option()
    {
        return $this->belongsTo(MenuOption::class,'menu_option_id');
    }
}
