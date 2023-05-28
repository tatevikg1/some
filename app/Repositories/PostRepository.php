<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PostRepository
{
    private ImageService $imageService;
    private const FILE_FOLDER = 'post';

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
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
        $imageData= $this->imageService->savePublicImage(auth()->id(), self::FILE_FOLDER);

        $request->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imageData['image'],
        ]);
    }
}
