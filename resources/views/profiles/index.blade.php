@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container a" style="margin-top:50px">
    <div class="d-flex flex-wrap">
        @if(@users)
            @foreach($users as $user)
                <div class="col-3">

                    <a href="{{ route('profile.show', $user->id) }}">
                        <img src="{{ $user->profile->profileImage() }}" class="w-100">

                        <div>
                            <p>
                                <span class="font-weight-bold">
                                    <span class="text-dark">{{ Str::ucfirst($user->profile->title) }}</span>
                                </span>
                            </p>
                        </div>
                    </a>

                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
