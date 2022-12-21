<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;


class RatingsController extends Controller
{
    public function add(Request $request){

        $value = $request->input('value');
        $id = $request->input('id');
        
        $newRating = new Rating();

        $newRating->rating = $value;
        $newRating->post_id = $id;
        
        $newRating->save();
    }
}
