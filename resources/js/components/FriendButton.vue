<template>
    <div>
        <button v-if="!status"      class="btn btn-my" @click="addFriend()">Add Friend</button>
        <div v-else>
            <button v-if="confirmed"    class="btn btn-my" @click="deleteRe()"> Delete Friend</button>
                <div v-if="pending"> 
                    <button v-if="toMe"     class="btn btn-my" @click="confirm()"> Confirm</button>
                    <button                 class="btn btn-my" @click="deleteRe()">Delete Request</button>
                </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            userId:String,
            friendship:Object
        },

        methods: {
            addFriend() {
                axios.post('/addfriend/' + this.userId)
                    .then(response => {
                        this.status = response.data.status;
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            },

            confirm() {
                axios.post('/confirm/' + this.friendship.id)
                    .then(response => {
                        this.status = response.data.status;
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            },

            deleteRe() {
                axios.post('/delete/' + this.friendship.id)
                    .then(response => {
                        this.status = response.data;
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            },
        },

        computed: {
            status(){
                if(this.friendship){
                    return true;
                }
                return false;
            },

            confirmed(){
                if(this.friendship.status == 'confirmed'){
                    return true;
                }
                return false;
            },

            pending(){
                if(this.friendship.status == 'pending'){
                    return true;
                }
                return false;
            },

            toMe(){
                if(this.friendship.acted_user == this.userId){
                    return true;
                }
                return false;
            }
            
        }

    }
</script>
