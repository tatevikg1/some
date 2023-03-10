<template>
    <div>
        <button v-if="!status" class="btn btn-my" @click="addFriend()">
            <i class='fas fa-user-plus' style='font-size:17px;color:black'></i>
            <!-- <img src="/svg/user-plus-solid.svg" alt="Add friend" width="25px"> -->
        </button>
        <div v-else>
            <button v-if="confirmed" class="btn " @click="deleteRe()">
                <i class='fas fa-user-times'></i>
                <!-- <img src="/svg/user-times-solid.svg" alt="Add friend" width="25px"> -->
            </button>
                <div v-if="pending">
                    <button v-if="toMe"     class="btn btn-secondary" @click="confirm()"> Confirm</button>
                    <button v-if="toMe"     class="btn btn-secondary" @click="deleteRe()">Delete Request</button>
                    <button v-else          class="btn btn-secondary" @click="deleteRe()">Cancel Request</button>
                </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            userId:String,
            friendship:String
        },

        data(){
            if(this.friendship != ''){
                return { obj: JSON.parse(this.friendship) }
            }else{
                return { obj: '' };
            }
        },

        methods: {
            addFriend() {
                axios.post('/add-friend/' + this.userId)
                    .then(response => {
                        this.obj = response.data;
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            },

            confirm() {
                axios.post('/confirm/' + this.obj.id)
                    .then(response => {
                        this.obj = response.data;
                    })
                    .catch(errors => {
                        if (errors.response.status == 401) {
                            window.location = '/login';
                        }
                    });
            },

            deleteRe() {
                axios.post('/delete/' + this.obj.id)
                    .then(response => {
                        this.obj = response.data;
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
                if(this.obj != ''){
                    return true;
                }
                return false;
            },

            confirmed(){
                if(this.obj.status == 'confirmed'){
                    return true;
                }
                return false;
            },

            pending(){
                if(this.obj.status == 'pending'){
                    return true;
                }
                return false;
            },

            toMe(){
                if(this.obj.acted_user == this.userId){
                    return true;
                }
                return false;
            }

        }

    }
</script>


<style scoped>

</style>
