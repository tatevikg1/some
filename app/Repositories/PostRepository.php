<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PostRepository
{
    public function index(Request $request)
    {
        if(!Auth::check()){
            // for not authenticated users show posts of random users
            $users = User::inRandomOrder()->limit(5)->get('id');
        }else{
            $users = $request->user()->following()->pluck('profiles.user_id');
        }

        return Post::whereIn('user_id', $users)->latest()->paginate(5);
    }

    public function store(Request $request): void
    {
        $data = $request->validated();
        $imagePath = request('image')->store('uploads', 'public');
        try {
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
            $image->save();
        } catch (\Throwable $throwable)
        {

        }


        $request->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);
    }
}
