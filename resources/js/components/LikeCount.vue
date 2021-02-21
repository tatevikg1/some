<template>
    <div  class="row">
        {{ likeCount }}  
        <div class=" ml-2">likes</div>
        
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    props: ['postId'],

    methods: {
        ...mapActions(['getLikeCount']),
    },

    computed: mapGetters([
        'getLikeCount',

    ]),

    // beforeMount() {
    //     getLikeCount(this.postId);
    // }

    created() {
        this.getLikeCount(this.postId);
    },

}
</script>
