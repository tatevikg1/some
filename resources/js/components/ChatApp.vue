<template>
    <div class="chat-app">

        <Conversation :contact="selectedContact" :messages="messages" />
        <Contacts
            :contacts="contacts"
            @selected="startConversationWith"/>

    </div>
</template>

<script>
    import Conversation from './chat/Conversation.vue';
    import Contacts from './chat/Contacts.vue';

    export default{

        props:{
            user:{
                required: true,
            }
        },

        data(){
            return {
                selectedContact : null,
                messages: [],
                contacts: [],
            }
        },

        mounted(){
            axios.get('/contacts')
                .then((response) =>{
                    console.log(response.data[0]);
                    this.contacts = response.data;
                    this.startConversationWith(response.data[0]);
                });
        },

        methods:{
            startConversationWith(contact){
                axios.get(`/conversation/${contact.id}`)
                    .then((response) => {
                        this.messages = response.data;
                        this.selectedContact = contact;
                    })
            }
        },

        components:{ Conversation, Contacts }
    }
</script>

<style lang="scss" scoped>
.chat-app {
    display: flex;
    background-color:white;
    padding:15px;
    border-radius:5px;
}
</style>
