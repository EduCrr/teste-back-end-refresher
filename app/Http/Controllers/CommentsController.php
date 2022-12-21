<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Comment;

class CommentsController extends Controller
{
    public function create(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if(!$validator->fails()){
            $content = $request->input('content');
            $id = $request->input('id');
            
            $newComment = new Comment();
            $newComment->content = $content;
            $newComment->post_id = $id;
            $newComment->date = date('Y-m-d H:i:s');
            $newComment->save();

        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }
}
