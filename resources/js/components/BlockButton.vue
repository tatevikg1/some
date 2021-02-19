<template>
    <div>
        <button class="btn btn-my" @click="blockUser()">Block</button>
    </div>
</template>

<script>
    export default {
        props: [
            'userId'
        ],

        methods: {
            blockUser() {
                alert('block the user');
                // axios.post('/block/' + this.userId)
                //     .then(response => {
                //         this.status = response.data.status;
                //     })
                //     .catch(errors => {
                //         if (errors.response.status == 401) {
                //             window.location = '/login';
                //         }
                //     });
            },

        },

    }
</script>
