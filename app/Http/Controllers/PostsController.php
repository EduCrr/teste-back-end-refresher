<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class PostsController extends Controller
{
    public function index(Request $request){

        $array = ['error' => ''];

        $posts = Post::all();

        $array['posts'] = $posts;
        $array['path'] = url('posts/');

        return $array;
    }

    public function findOne(Request $request, $id){

        $array = ['error' => ''];

        $post = Post::find($id);
        $getNumberRatings = null;

        if($post){
            $ratings =  $post->ratings()->get();
            $relatingsCount =  $post->ratings()->count();

            
            if($relatingsCount > 0){
                foreach($ratings as $item){
                    $getNumberRatings += $item->rating;
                }
               $finalRelatings = $getNumberRatings / $relatingsCount;
            }else{
                $finalRelatings = 0;
            }

            $post->date = date('d-m-Y', strtotime($post->date));

            $array['post'] = $post;
            $array['comments'] = $post->comments()->get();
            $array['ratings'] = $finalRelatings;
            $array['path'] = url('posts/');
        }

        return $array;
    }

    public function delete($id){
        $array = ['error' => ''];

        $post = Post::find($id);

        if($post){
            File::delete(public_path("posts/".$post->imagem));
            $post->delete();
        }
        return $array;  
    }

    public function create(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'content' => 'required|max:255',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,svg',
        ]);

        if(!$validator->fails()){
            
            $title = $request->input('title');
            $author = $request->input('author');
            $content = $request->input('content');
            $imagem = $request->file('imagem');
            $extensionImg = $request->file('imagem')->extension();

            $photoNameImagem = '';
            
            if($imagem){
                $destImg = public_path('posts/');
                $photoNameImagem = md5(time().rand(0,9999)).'.'.$extensionImg;
                $imgSlide = Image::make($imagem->getRealPath());
                $imgSlide->fit(432, 432)->save($destImg.'/'.$photoNameImagem);
            }else{
                $array['error'] = 'Adicione uma imagem!';
                return $array;
            }
            
            $newPost = new Post();

            $newPost->title = $title;
            $newPost->author = $author;
            $newPost->imagem = $photoNameImagem;
            $newPost->content = $content;
            $newPost->date = date('Y-m-d H:i:s');
            $newPost->save();

        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    
    public function update(Request $request, $id){
        $array = ['error' => ''];

        $rules = [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'content' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        $title = $request->input('title');
        $author = $request->input('author');
        $content = $request->input('content');
        
        $post = Post::find($id);

        if($post){
            if($title){
               $post->title = $title;
               $post->save();
            }
            if($author){
                $post->author = $author;
                $post->save();
            }
            if($content){
                $post->content = $content;
                $post->save();
            }
        }
        
        return $array;
    }

    public function updateImagem(Request $request, $id){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'imagem' =>  'image|mimes:jpeg,png,jpg,svg',
        ]);

        if(!$validator->fails()){

            $imagem = $request->file('imagem');
            $post = Post::find($id);
            $extension = $request->file('imagem')->extension();

            if($imagem){
                File::delete(public_path("posts/".$post->imagem));
                $dest = public_path('posts');
                $photoName = md5(time().rand(0,9999)).'.'.$extension;
        
                $img = Image::make($imagem->getRealPath());
                $img->fit(432, 432)->save($dest.'/'.$photoName);

                $post->imagem = $photoName;
                $post->save();
            }

        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;

    }

    public function findRatings(Request $request, $id){

        $array = ['error' => ''];

        $post = Post::where('id', '!=', $id)->inRandomOrder()
        ->limit(3)
        ->get();

        if($post){
            $array['post'] = $post;
        }

        return $array;
    }
}
