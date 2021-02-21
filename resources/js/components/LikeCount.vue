<template>
    <div  class="row mb-1">
        {{ likeCount }}   <div class=" ml-2">likes</div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    props: ['postId'],

    methods: {
        ...mapActions(['getLikeCount']),
    },

    data: function () {
        return {
            likeCount: 0,
        }
    },

    mounted: function() {

        this.getLikeCount(this.postId).then(response => {
            this.likeCount = response.data;
        }, error => {
            console.error("Got nothing from server. Try again")
        })
    }

    // created() {
    //     this.getLikeCount(this.postId);
    // },

}
</script>
