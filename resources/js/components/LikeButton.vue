<template>
    <div>
        <i :class="classStatus" @click="likePost">{{ this.buttonText }} </i>
    </div>
</template>

<script>
    export default {
        props: ['postId', 'userId'],

        mounted() {
            console.log('Like button component.')
        },

        data: function () {
            return {
                status: false,
            }
        },

        beforeMount() {
            this.getLikes();
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
            },

            getLikes(){
                axios.get('/api/get-likes/' + this.postId + '/' + this.userId)
                    .then(response => {
                        this.status = response.data;
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