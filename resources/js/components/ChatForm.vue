<template>
    <div class="input-group mt-3">

        <input  id="btn-input"
                type="text"
                name="message"
                class="form-control input-sm"
                placeholder="Type your message here..."
                v-model="newMessage"
                @keyup.enter="sendMessage">

        <span class="input-group-btn">
            <button class="btn btn-primary" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage
                });

                this.newMessage = '';

                // var scrolled = false;
                // function updateScroll(){
                //     if(!scrolled){
                //         var element = document.getElementById("scroll");
                //         element.scrollTop = element.scrollHeight;
                //     }
                // }
                //
                // $("#yourDivID").on('scroll', function(){
                //     scrolled=true;
                // });
            }
        }
    }
</script>
