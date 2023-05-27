<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostsController extends Controller
{
    private PostRepository $repository;
    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): View
    {
        $title = 'Posts';
        $posts = $this->repository->index($request);
        return view('posts.index', compact('posts', 'title'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function store(PostStoreRequest $request): RedirectResponse
    {
        $this->repository->store($request);

        return redirect()->route('profile.show', [
            'user' => auth()->user()->getAuthIdentifier()
        ]);
    }


    public function show(Post $post): View
    {
        /** @var User $user */
        $user = auth()->user();
        $likes = ($user) ? $user->liking->contains($post->id) : false;

        return view('posts.show', compact('post', 'likes'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('profile.show', [
            'user' => auth()->user()->getAuthIdentifier()
        ]);
    }

    /**
     * show liked posts page
    */
    public function liked(): View
    {
        $posts = auth()->user()->liking;
        $title = 'Liked Posts';

        return view('posts.index', compact('posts', 'title'));
    }
}
