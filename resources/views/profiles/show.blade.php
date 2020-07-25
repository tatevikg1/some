@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container white_bckgr">
    <div class="row cover_img">
        <div class="col-3 ">
            <img src="{{ $user->profile->profileImage() }}" class="rounded-circle profile_img">
        </div>
    </div>
    <div class="d-flex">
        <div class="h4" style="margin-left:150px;">{{ $user->name }}</div>

        <ul class="ml-auto" style="list-style-type:none;">
            <li style="display:inline; margin-right:7px;"
                class="float-right">
                @can('update', $user->profile)

                @else
                    <a  href="/message/{{ Auth::user()->id }}/{{ $user->id }}"
                        class="btn btn-my"
                        style="float:right;">Message</a>
                @endcan
            </li>
            <li class="float-left" style="margin-right:7px;">
                @can('update', $user->profile)
                    <a class="btn btn-my ml-3"
                        href="/profile/{{ $user->id }}/edit"
                        >Edit Profile</a>
                @else
                    <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
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

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>

<div class="container mt-4 d-flex">
    @foreach($user->posts as $post)
        <div class="col-4 pt-4 pb-4 post_background" style="display: table-cell">
            <a href="/p/{{ $post->id }}">
                <img src="/storage/{{ $post->image }}" class="w-100"
                    data-toggle="tooltip" title="{{$post->created_at}}">
            </a>
        </div>
    @endforeach
</div>

@endsection
