<template>
    <div class="row align-items-center justify-content-between p-1 mb-1">
        <div  class="row ml-1">
            {{ likeCount }}  <div class=" ml-2">likes</div>
        </div>
        <i :class="classStatus" @click="likePost">{{ this.buttonText }} </i>
    </div>
</template>

<script>
    export default {
        props: ['postId', 'userId'],

        // mounted() {
        //     console.log('Like button component.')
        // },

        data: function () {
            return {
                status: false,
                likeCount: 0,
            }
        },

        beforeMount()  {
            this.getLikes();
            this.getLikesCount();
        },

        methods: {
            likePost() {
                axios.post('/like/' + this.postId)
                    .then(response => {
                        this.status = ! this.status;
                        if(this.status){
                            this.likeCount +=1;
                        }else{
                            this.likeCount -=1;
                        }
                        // console.log(response.data);
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            },

            getLikes(){
                axios.get('/api/get-likes/' + this.postId + '/' + this.userId)
                    .then(response => {
                        this.status = response.data;
                        // console.log(response.data);
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            },

            getLikesCount(){
                axios.get('/api/get-like-count/' + this.postId)
                    .then(response => {
                        this.likeCount = response.data;
                        // console.log(response.data);
                    })
            }
        },

        computed: {
            buttonText() {
                return  'Like';
            },

            classStatus(){
                return (this.status) ? 'fas fa-thumbs-up theam-color' : 'fas fa-thumbs-up inactive';
            }
        }
    }
</script>

<style scoped>
    i:hover{
        cursor: pointer;
    }

    .inactive{
        color:grey;
    }
</style>