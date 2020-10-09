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

            Echo.private(`messages.${this.user.id}`)
                .listen("NewMessage", (e) => {
                    this.handleIncoming(e.message);
                });



            axios.post('/contacts')
                .then((response) =>{
                    this.contacts = response.data;
                    this.startConversationWith(response.data[0]);
                });
        },

        methods:{
            startConversationWith(contact){
                this.updateUnreadCount(contact, true);
                axios.get(`/conversation/${contact.id}`)
                    .then((response) => {
                        this.messages = response.data;
                        this.selectedContact = contact;

                    })
            },

            saveNewMassage(message){
                this.messages.push(message);

            },

            handleIncoming(message){
                if(this.selectedContact && message.from == this.selectedContact.id){
                    this.saveNewMassage(message);
                    axios.post(`/messages/${message.id}`)
                    return;
                }

                this.updateUnreadCount(message.from_contact, false);

            },

            updateUnreadCount(contact, zroyacnel){
                this.contacts = this.contacts.map((single) =>{
                    if (single.id != contact.id){
                        return single;
                    }

                    if(zroyacnel)
                        single.unread = 0;
                    else
                        single.unread += 1;

                    return single;
                }
            )}
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
