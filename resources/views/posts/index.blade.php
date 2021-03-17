@extends('layouts.app')

@section('title', $title)

@section('content')

<div class='container mt-5'>
    <div class="row">
        <div class="col-3"> 
            @include('partials.sidenav')
        </div>

        <div class="col-7">
            @auth
                <div class="row mb-3">
                    <div class="col-10 mx-5 post_background white">
                    <!-- <small style="color:pink">This feature is not working because administration(me) has not paid for simple file upload heroku add-on.</small> -->
                        <form action="/post" enctype="multipart/form-data" method="post" class="mt-3 mb-3">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="text" name="caption" 
                                class="form-control focus mb-2 light-background" 
                                placeholder="What is on your mind?" autofocus>
                            <div class="row justify-content-between mx-1">
                                <input type="file" name="image" class="btn btn-secondary" >
                                <input type="submit" value="Add post" class="btn btn-secondary">
                            </div>
                        </form>

                    </div>
                </div>
            @endauth

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
