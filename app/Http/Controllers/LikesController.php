<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class LikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Post $post, Request $request)
    {
        return $request->user()->liking()->toggle($post);
    }
}
