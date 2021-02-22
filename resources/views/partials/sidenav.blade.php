@auth
<div class="container sidenav">

    <div class="row align-items-center  pl-3" onclick="location.href=`{{ route('profile.show',  Auth::user()->id) }} `">
        <img src="{{ Auth()->user()->profile->profileImage() }}" class="rounded-circle" style="max-width:40px">
        <div class="m-3 font-weight-bold"> {{ ucfirst(Auth::user()->name) }}</div>
    </div>

    <div class="row align-items-center pl-3" onclick="location.href=`{{ route('profile.index') }} `">
        <i class="fas fa-users fa-2x theam-color"></i>
        <div class="m-3 font-weight-bold">Find Friends</div>
    </div>

    <div class="row align-items-center pl-3" onclick="location.href=`{{ route('post.liked') }} `">
        <i class="fas fa-heart fa-2x theam-color"></i>
        <div class="m-3 font-weight-bold">Liked Posts</div>
    </div>
</div>
@endauth