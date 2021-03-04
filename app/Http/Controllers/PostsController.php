<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
// use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $title = 'Posts';

        if(!Auth::check()){
            // for not authenticated users show posts of random users
            $users = User::inRandomOrder()->limit(5)->get('id');
        }else{
            $users = $request->user()->following()->pluck('profiles.user_id');
        }

        $posts = Post::whereIn('user_id', $users)->latest()->paginate(5);

        return view('posts.index', compact('posts', 'title'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caption' => 'required',
            'image' => ['image'],
        ]);
        // $imagePath = 'svg/profile.jpeg';
        if(!$request->image){
            $imagePath = 'svg/profile.jpeg';
        }else{
            $imagePath = request('image')->store('uploads', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
            $image->save();
        }

        $request->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);

        return redirect('/profile/' . auth()->user()->id);
    }


    public function show(Post $post)
    {
        $user = auth()->user();

        $likes = (auth()->user()) ? auth()->user()->liking->contains($post->id) : false;

        // $likesCount = Cache::remember(
        //     'count.likes.' . $post->id,
        //     now()->addSeconds(30),
        //     function () use ($post) {
        //         return $post->likers->count();
        //     });

        return view('posts.show', compact('post', 'likes'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        $user = auth()->user();

        return redirect('/');
    }

    public function liked()
    {
        $user = auth()->user();

        $posts = $user->liking;
        $title = 'Liked Posts';
        
        return view('posts.index', compact('posts', 'title'));
    }
}
