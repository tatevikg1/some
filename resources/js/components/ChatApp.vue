<template>
    <div class="chat-app">

        <Conversation :contact="selectedContact" :messages="messages" @new="saveNewMassage"/>
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

            Echo.private(`messages${this.user.id}`)
                .listen('NewMessage', (e) =>{
                    this.handleIncoming(e.message);
                });



            axios.get('/contacts')
                .then((response) =>{
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
            },

            saveNewMassage(text){
                this.messages.push(text);
            },

            handleIncoming(message){
                if(message.from == this.selectedContact.id){
                    saveNewMassage(message);
                    return;
                }

                alert(message.text);

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
