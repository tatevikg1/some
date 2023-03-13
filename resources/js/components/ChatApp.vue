<template>
    <div class="chat-app">

        <Conversation :contact="selectedContact" :messages="messages" @new="saveNewMassage" />
        <div>
            <Contacts :contacts="contacts" @selected="startConversationWith"/>
            <ul class="pagination-sm row justify-content-around contact-pagination">
                <div class="page-item">
                    <button @click="getPrevContacts()" type="button" class="page-link" :disabled="this.contactsOffset===0">prev page</button>
                </div>
                <div class="item">
                    <button @click="getContacts()" type="button" class="page-link">next page</button>
                </div>
            </ul>
        </div>
    </div>
</template>

<script>
    import Conversation from './chat/Conversation.vue';
    import Contacts from './chat/Contacts.vue';
    import {mapGetters, mapMutations} from "vuex";

    export default{

        props:{
            user:{
                required: true,
            },
        },

        data(){
            return {
                selectedContact : null,
                messages: [],
                contacts: [],
                contactsOffset: -5,
                contactsLimit: 5,
            }
        },

        computed: {
            ...mapGetters(['messageId'])
        },

        mounted(){
            Echo.private(`messages.${this.user.id}`)
                .listen("NewMessage", (e) => {
                    this.handleIncoming(e.message);
                });

            this.getContacts();
        },

        methods:{
            ...mapMutations(['setMessageId']),

            startConversationWith(contact){
                this.updateUnreadCount(contact, true);

                axios.post(`/conversation/${contact.id}`)
                    .then((response) => {
                        this.selectedContact = contact;
                        this.setMessageId((response.data.slice(-1))[0]['id']);
                        this.messages = response.data.reverse();
                    })
            },

            saveNewMassage(message){
                this.messages.push(message);

            },

            handleIncoming(message){
                if(this.selectedContact && message.sender == this.selectedContact.id){
                    this.saveNewMassage(message);
                    axios.post(`/messages/${message.id}`)
                    return;
                }
                // from_contact is the fromContact method in message class
                this.updateUnreadCount(message.from_contact, false);
            },

            updateUnreadCount(contact, zroyacnel){
                this.contacts = this.contacts.map((single) => {
                    if (single.id != contact.id){
                        return single;
                    }

                    if(zroyacnel)
                        single.unread = 0;
                    else
                        single.unread += 1;

                    return single;
                }
            )},

            getContacts(){
                this.contactsOffset = this.contactsOffset + this.contactsLimit;
                axios.post('/contacts', {
                    offset : this.contactsOffset
                }).then((response) => {
                    this.contacts = response.data;
                    // this.startConversationWith(response.data[0]);
                });
            },
            getPrevContacts(){
                this.contactsOffset = this.contactsOffset - this.contactsLimit;
                axios.post('/contacts', {
                    offset : this.contactsOffset
                }).then((response) => {
                    this.contacts = response.data;
                    // this.startConversationWith(response.data[0]);
                });
            },
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

    .contact-pagination {
        padding-left: 3px;
        min-width:50px;
    }
}
</style>
