<div class="row mb-3">
    <div class="col-10 mx-5 post_background white">

        <form action="/post" enctype="multipart/form-data" method="post" class="mt-3 mb-3">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="text" name="caption" class="btn form-control focus mb-2" placeholder="What is on your mind?" autofocus>
            <div class="row justify-content-between mx-1">
                <input type="file" name="image" class="btn btn-secondary" >
                <input type="submit" value="Add post" class="btn btn-secondary">
            </div>
        </form>

    </div>
</div>

