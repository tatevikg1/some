<template>
    <span v-if="friendRequestCount > 0" class="new">
        {{ friendRequestCount }}
    </span>
</template>

<script>

export default {
    props: ['userId'],

    mounted() {
        Echo.private(`friendRequests.${this.userId}`)
            .listen("NewFriendRequest",(notification) => {
                this.getFriendRequestNotification();
            });

    },

    data: function () {
        return {
            friendRequestCount: 0,
        }
    },

    beforeMount()  {
        this.getFriendRequestNotification();
    },

    methods: {
        getFriendRequestNotification(){
            if (window.location.pathname === '/friend'){
                this.markAsRead();
                return;
            }
            axios.get('/api/get-friend-request-notification/' + this.userId)
                .then(response => {
                    this.friendRequestCount = response.data;
                })
        },

        markAsRead(){
            axios.post('/chat/mark-as-read/?t=' + Date.now())
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
