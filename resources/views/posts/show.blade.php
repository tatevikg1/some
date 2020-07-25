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
                <div class="pr-3">
                    <img src="{{ $post->user->profile->profileImage() }}" class="rounded-circle" style="max-width:70px">
                </div>
                <div style="position:absolute; right:0;">
                    <div class="font-weight-bold">
                            <a href="/profile/{{ $post->user->id }}">
                                <span class="text-dark" >{{ $post->user->username }}</span>
                            </a>
                        <div class="">
                            @if ($post->user->id == Auth::user()->id)
                                <div class=" ">
                                        <a href="/delete/{{ $post->id }}">Delete</a>
                                </div>

                            @else
                            <div class="mr-4">
                                <like-button post-id="{{ $post->id }}" likes="{{ $likes }}"></like-button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <i class="fa fa-thumbs-up" style="font-size:30px;color:#444085;"></i>{{ $likesCount }}
            </div>
        </div>
    </div>
</div>

@endsection
