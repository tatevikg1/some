<div class="row mb-3">
    <div class="col-10 mx-5 post_background">

        <div class="row align-items-center  pl-3 mt-3">
            <img src="{{ $post->user->profile->profileImage() }}" class="rounded-circle" style="max-width:40px">
            <div class="col">
                <div class="font-weight-bold white"> {{ ucfirst($post->user->name) }}</div>
                <small class="white">{{ $post->created_at }}</small>
            </div>
        </div>

        <div class="white">
            {{ $post->caption }}
        </div>
       

        <a href="{{ route('post.show', $post) }}">
            <img src="/storage/{{ $post->image }}" class="w-100 post_img">
        </a>

        <div class="light-background">
            <div class="mx-3 row align-items-center justify-content-between p-1 mb-1">
                <like-count post-id="{{ $post->id }}"></like-count>
                <like-button post-id="{{ $post->id }}" user-id="{{ auth()->user()->id }}"></like-button>
            </div>
        </div>

    </div>
</div>