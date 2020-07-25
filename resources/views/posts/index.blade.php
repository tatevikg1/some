@extends('layouts.app')

@section('title', 'Posts')


@section('content')

@if (count($posts) > 0)
    <div class="container mt-my" style="margin-top:50px;">
        @foreach($posts as $post)
            <div class="row mb-3">
                <div class="col-6 offset-3 p-3 post_background">



                    <small class="white">{{ $post->created_at }}</small>

                    <a href="/p/{{ $post->id }}">
                        <img src="/storage/{{ $post->image }}" class="w-100 post_img">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="container a" style="margin-top:50px">
        <div class="d-flex flex-wrap">
            @if(@users)
                @foreach($allUsers as $user)
                    <div class="col-3">

                        <a href="/profile/{{ $user->id }}">
                            <img src="{{ $user->profile->profileImage() }}" class="w-100">
                        </a>

                        <div>
                            <p>
                                <span class="font-weight-bold">
                                    <a href="/profile/{{ $user->id }}">
                                        <span class="text-dark">{{ $user->name }}</span>
                                    </a>
                                </span>
                            </p>
                        </div>

                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endif

@endsection
