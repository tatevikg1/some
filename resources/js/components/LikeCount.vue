<template>
    <div  class="row">
        {{ this.likeCount }}  
        <div class=" ml-2">likes</div>
        
    </div>
</template>

<script>
    export default {
        props: ['postId'],

        data() {
            return {
                likeCount: 0
            }
        },

        beforeMount() {
            this.getLikeCount();
        },

        methods: {
            getLikeCount() {
                axios.get('/api/get-like-count/' + this.postId)
                    .then(response => {
                        this.likeCount = response.data;
                        console.log(response.data);
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });

            }
        },

    }
</script>
