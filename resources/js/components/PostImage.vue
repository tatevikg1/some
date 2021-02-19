<template>
<div>
    <div class="container a">
        <div class="row">
            <div class="col-8">
                <img src="/storage/{{ $post->image }}" class="w-100 ">
            </div>
            <div class="col-4 white_bckgr">
                <div class="d-flex align-items-center  mt-3 d-flex">
                    <div class="pr-3">
                        <img src="{{ $post->user->profile->profileImage() }}" class="rounded-circle" style="max-width:70px">
                    </div>
                    <div style="position:absolute; right:0;">
                        <div class="font-weight-bold">
                                <a href="{{ route('profile.show', $post->user->id) }}">
                                    <span class="text-dark" >{{ Str::ucfirst($post->user->username) }}</span>
                                </a>
                            <div class="">
                                @if ($post->user->id == Auth::user()->id)
                                    <div class="mr-4">
                                        <a href="{{ route('post.destroy', $post->id) }}">Delete</a>
                                    </div>

                                @else
                                <div class="mr-4">
                                    <like-button post-id="{{ $post->id }}" likes="{{ $likes }}"></like-button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <i class="fa fa-thumbs-up" style="font-size:30px;color:#444085;"></i>{{ $likesCount }}
                </div>
            </div>
        </div>
    </div>    
</div>
</template>

<script>
    export default {
        props: ['postId', 'likes'],

        mounted() {
            console.log('Component mounted.')
        },

        data: function () {
            return {
                status: this.likes,
            }
        },

        methods: {
            likePost() {
                axios.post('/like/' + this.postId)
                    .then(response => {
                        this.status = ! this.status;

                        console.log(response.data);
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            }
        },

        computed: {
            buttonText() {
                return (this.status) ? 'Unlike' : 'Like';
            }
        }
    }
</script>
