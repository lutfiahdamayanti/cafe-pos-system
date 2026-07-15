<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle($id)
    {
        $favorite = Favorite::where('menu_id',$id)->first();

        if($favorite){
            $favorite->delete();
        }else{
            Favorite::create([
                'menu_id'=>$id
            ]);
        }

        return back();
    }
}
