<template>
    <span v-if="unreadMessageCount > 0" class="new">
        {{ unreadMessageCount }} 
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
            unreadMessageCount: 0,
        }
    },

    beforeMount()  {
        this.getUnreadMessageCount();
    },

    methods: {
        getUnreadMessageCount(){
            axios.get('/api/get-unread-message-count/' + this.userId)
                .then(response => {
                    this.unreadMessageCount = response.data;
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
