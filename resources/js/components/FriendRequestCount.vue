<template>
    <span v-if="friendRequestCount > 0" class="new">
        {{ friendRequestCount }} 
    </span>
</template>

<script>

export default {
    props: ['userId'],

    // mounted() {
    //     console.log('friend request count component.')
    // },

    data: function () {
        return {
            friendRequestCount: 0,
        }
    },

    beforeMount()  {
        this.getFrindRequestCount();
    },

    methods: {
        getFrindRequestCount(){
            axios.get('/api/get-friend-request-count/' + this.userId)
                .then(response => {
                    this.friendRequestCount = response.data;
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
