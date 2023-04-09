<template>
    <div  class="row white">
        <div v-for="friend in this.friends" :key="friend.id" class="col-4">
            <div class="mt-3" @click="visitFriend(friend.id)">
                <img :src="getprofileImage(friend)">
                <div class="">{{ friend.name }}</div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: ['userId'],

    methods: {
        getRandom4Friend(){
            axios.post('/api/get-random-4-friend/' + this.userId)
                .then(response => {
                    this.friends = response.data;
                })
        },

        visitFriend(friendId){
            location.href= '/profile/' + friendId;
        },

        getprofileImage(friend){
            if (friend.profile.image !== null) {
                return '/storage/' + friend.profile.image;
            }
            return 'http://via.placeholder.com/150x150';
        }
    },

    data: function () {
        return {
            friends: [],
        }
    },

    beforeMount(){
        this.getRandom4Friend();
    }
    
}
</script>

<style scoped>
img{
    max-width:150px;
    border-radius: 5px;
    width: 100%;
    height: auto;
}
.col-4:hover{
    cursor: pointer;
}
</style>
