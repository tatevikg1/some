<template>
    <span v-if="unreadMessageNotification > 0" class="new">
        {{ unreadMessageNotification }} 
    </span>
</template>

<script>

export default {
    props: ['userId'],

    // mounted() {
    //     console.log('unread message count component.')
    // },

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
            axios.get('/api/get-unread-message-count/' + this.userId)
                .then(response => {
                    this.unreadMessageNotification = response.data;
                    // console.log(response.data);
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
