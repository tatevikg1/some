@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="container white_bckgr">

    <div class="row cover_img">
        <div class="col-3 " onclick="location.href='newurl.html';">
            <img src="{{ $user->profile->profileImage() }}" class="rounded-circle profile_img">
        </div>
    </div>
    <div class="d-flex">
        <div class="h4" style="margin-left:150px;">{{ Str::ucfirst($user->name) }}</div>

        <ul class="ml-auto" style="list-style-type:none;">
            <li style="display:inline; margin-right:7px;"
                class="float-right">

            </li>
            <li class="float-left" style="margin-right:7px;">
                @can('update', $user->profile)
                    <a class="btn btn-secondary ml-3" href="{{ route('profile.edit', $user->id) }}">Edit Profile</a>
                @else
                    <div class="row">
                        <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
                        <friend-button user-id="{{ $user->id }}" friendship="{{ $friendship }}"></friend-button>
                    </div>
                @endcan
            </li>
        </ul>
    </div>
</div>

<div class="container white_bckgr mt-3 p-3">
    <div class="col-12">
        <div class="col-6 d-flex">
            <div class="pr-5"><strong>{{ $postCount }}</strong> posts</div>
            <div class="pr-5"><strong>{{ $followersCount }}</strong> followers</div>
            <div class="pr-5"><strong>{{ $followingCount }}</strong> following</div>
        </div>
    </div>
</div>


<div class="container  mt-3 p-3">
    <div class="row ">
        <div class="col-5 ">
            <div class="row mb-3">
                <div class="col-10 mx-5 post_background">

                   friends

                </div>
            </div>
        </div>
        
        <div class="col-7 ">
            <div class="row mb-3">
                <div class="col-10 mx-5 post_background white">

                    <form action="/post" enctype="multipart/form-data" method="post" class="mt-3 mb-3">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="text" name="caption" class="form-control focus mb-2" placeholder="What is on your mind?" autofocus>
                        <div class="row justify-content-between mx-1">
                            <input type="file" name="image" class="btn btn-secondary" >
                            <input type="submit" value="Add post" class="btn btn-secondary">
                        </div>
                    </form>

                </div>
            </div>

            @foreach($user->posts as $post)
                @include('partials.post')
            @endforeach
        </div>
    </div>
</div>

@endsection
