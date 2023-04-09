<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
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
            'image' => ['image', 'required'],
        ]);

        $imagePath = request('image')->store('uploads', 'public');
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        $image->save();

        $request->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);

        return redirect()->route('profile.show', [
            'user' => auth()->user()->id
        ]);
    }


    public function show(Post $post)
    {
        $user = auth()->user();
        $likes = ($user) ? $user->liking->contains($post->id) : false;

        return view('posts.show', compact('post', 'likes'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('profile.show', [
            'user' => auth()->user()->id
        ]);
    }

    /**
     * show liked posts page
    */
    public function liked()
    {
        $posts = auth()->user()->liking;
        $title = 'Liked Posts';

        return view('posts.index', compact('posts', 'title'));
    }
}
