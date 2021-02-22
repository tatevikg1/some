@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="container a" style="margin-top:50px">
    <div class="container col-10">
        @if($friend_requests ?? '')
            <hr><h5>Friend Requests</h5>
            @foreach($friend_requests as $f)
                <div class="row justify-content-between">
                    <div class="row align-items-center pointer pl-3" onclick="location.href=`{{ route('profile.show',  $f->creator->id) }} `">
                        <img src="{{ $f->creator->profile->profileImage() }}" class="rounded-circle" style="max-width:40px">
                        <div class="m-3 font-weight-bold"> {{ ucfirst($f->creator->name) }}</div>
                    </div>
                    <div class="row align-items-center">
                        <friend-button user-id="{{ $f->creator->id }}" friendship="{{ $f }}"></friend-button>
                    </div>
                </div>
            @endforeach
        @endif

        <hr>

        @if(@users)
            <hr><h5>All friends</h5><hr>
            @foreach($users as $friend)
                <div class="row justify-content-between">
                    <div class="row align-items-center pl-3 pointer" onclick="location.href=`{{ route('profile.show',  $friend->id) }} `">
                        <img src="{{ $friend->profile->profileImage() }}" class="rounded-circle" style="max-width:40px">
                        <div class="m-3 font-weight-bold"> {{ ucfirst($friend->name) }}</div>
                    </div>
                    <div class="row align-items-center">
                        <friend-button user-id="{{ $friend->id }}" friendship="{{ $friend->friendship }}"></friend-button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
