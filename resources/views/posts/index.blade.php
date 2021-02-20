@extends('layouts.app')
@section('title', 'Posts')
@section('content')

<div class='container mt-5'>
    <div class="row">
        <div class="col-4"> 
            @include('partials.sidenav')
        </div>

        <div class="col-8">
            @if ($posts)
                <div class="mt-my">
                    @foreach($posts as $post)
                        @include('partials.post')
                    @endforeach
                </div>
        
            @endif
        </div>
    </div>

</div>


@endsection
