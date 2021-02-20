@extends('layouts.app')
@section('title', 'Liked Posts')
@section('content')

@if ($posts)
    <div class="container mt-my" style="margin-top:50px">
        @foreach($posts as $post)
            <div class="row mb-3">
                <div class="col-6 offset-3 p-3 post_background">
                    <small class="white">{{ $post->created_at }}</small>
                    <a href="{{ route('post.show', $post) }}">
                        <img src="/storage/{{ $post->image }}" class="w-100 post_img">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div>No liked posts</div>
@endif

@endsection
