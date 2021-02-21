@extends('layouts.without_navbar')

@section('title', 'Post')

@section('content')
<div class="container a">
    <div class="row">
        <div class="col-8">
            <img src="/storage/{{ $post->image }}" class="w-100 ">
        </div>
        <div class="col-4 white_bckgr">
            <div class="d-flex align-items-center  mt-3 d-flex">
                <div class="row align-items-center  pl-3 " onclick="location.href=`{{ route('profile.show',  Auth::user()->id) }} `">
                    <img src="{{ $post->user->profile->profileImage() }}" class="rounded-circle" style="max-width:40px">
                    <div class="m-3 font-weight-bold"> {{ ucfirst($post->user->username) }}</div>
                </div>
                <div style="position:absolute; right:0;">
                    <div class="font-weight-bold">
                        <div class="">
                            @if ($post->user->id == Auth::user()->id)
                                <div class="mr-1">
                                    <a href="{{ route('post.destroy', $post->id) }}">Delete</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 pl-3">
                @guest
                    <like-count post-id="{{ $post->id }}"></like-count>
                @else
                    <like-button post-id="{{ $post->id }}" user-id="{{ auth()->user()->id }}"></like-button>
                @endguest
            </div>
        </div>
    </div>
</div>

@endsection
