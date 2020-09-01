<template>
    <div class="composer">
        <textarea v-model="message" @keydown.enter="send" placeholder="Message..."></textarea>
    </div>
</template>

<script>

    export default{

        data(){

            return{
                message: ''
            };
        },

        methods:{
            send(){
                if(this.message == ''){
                    return;
                }

                this.$emit('send', this.message);
                this.message = '';
            }
        }
    }
</script>

<style>
    .composer textarea{
        width:96%;
        margin:10px;
        resize:none;
        border-radius:3px;
        border:2px solid #d1d0d6;
        padding:5px;
    }
</style>
