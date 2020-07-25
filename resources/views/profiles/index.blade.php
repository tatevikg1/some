@extends('layouts.app')

@section('title', 'Posts')


@section('content')
<div class="container a" style="margin-top:50px">
    <div class="d-flex flex-wrap">
        @if(@users)
            @foreach($users as $user)
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
@endsection
