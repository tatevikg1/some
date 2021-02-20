@extends('layouts.app')

@section('title', 'Liked Posts')

@section('content')

@if ($posts)
    <div class="container mt-my" style="margin-top:50px">
        @foreach($posts as $post)
            @include('partials.post')
        @endforeach
    </div>
@else
    <div>No liked posts</div>
@endif

@endsection
