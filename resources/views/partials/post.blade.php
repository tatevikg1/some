<div class="row mb-3">
    <div class="col-10 mx-5 post_background">

        <div class="row align-items-center  pl-3 mt-3">
            <img src="{{ $post->user->profile->profileImage() }}" class="rounded-circle" style="max-width:40px">
            <div class="col">
                <div class="font-weight-bold white"> 
                    <a href="{{route('profile.show', $post->user->id)}}" class="white">{{ ucfirst($post->user->name) }}</a>
                </div>
                <small class="white">{{ $post->created_at }}</small>
            </div>
        </div>

        <div class="white mb-2">
            {{ $post->caption }}
        </div>
       
        <div onclick="location.href=`{{ route('post.show', $post) }}`" class="pointer">

            <img src="/storage/{{ $post->image }}" class="w-100 post_img">
        </div>

        <div class="light-background">
            <div class="mx-3 mb-3">
                @guest
                    <like-count post-id="{{ $post->id }}"></like-count>
                @else
                    <like-button post-id="{{ $post->id }}" user-id="{{ auth()->user()->id }}"></like-button>
                @endguest
            </div>
        </div>

    </div>
</div>