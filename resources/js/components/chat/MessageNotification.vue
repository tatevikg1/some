<template>
    <span v-if="unreadMessageNotification > 0" class="new">
        {{ unreadMessageNotification }} 
    </span>
</template>

<script>

export default {
    props: ['userId'],

    mounted(){

        Echo.private(`messages.${this.userId}`)
            .listen("NewMessage", () => {
                this.getUnreadMessageNotification();
            });
    },

    data: function () {
        return {
            unreadMessageNotification: 0,
        }
    },

    beforeMount()  {
        this.getUnreadMessageNotification();
    },

    methods: {
        getUnreadMessageNotification(){
            if(window.location.pathname == '/chats'){
                this.markAsRead();
            }else{
                axios.get('/api/get-message-notification/' + this.userId)
                    .then(response => {
                        this.unreadMessageNotification = response.data;
                    })
            }
        },

        markAsRead(){
            axios.post('/chat/mark-as-read/')
                .then(response => {
                    this.unreadMessageNotification = 0;
                })
        }
    },
}
</script>

<style scoped>
span.new{
    background-color: red;
    color: white;
    padding: 0px 5px;
    border-radius: 60%;
    font-size: 10px;
    vertical-align: top;
}
</style>
