<template>
    <div class="conversation">

        <h3>{{ contact ? contact.name : 'Select a contact.' }}</h3>

        <Messages :contact="contact" :messages='messages' />

        <MessageComposer @send="sendMessage" :contact="contact" />

    </div>
</template>

<script>

    import Messages from "./conver/Messages";
    import MessageComposer from "./conver/MessageComposer";

    export default{

        props:{
            contact: {
                default:null
            },
            messages: {
                default:[]
            },
        },

        methods:{
            sendMessage(message){
                axios.post('/conversation/send',{
                    contact_id: this.contact.id,
                    text:message
                }).then((response) => {
                    this.$emit('new', response.data);
                })
            }
        },

        components:{Messages, MessageComposer}
    }
</script>

<style lang="scss" scoped>
.conversation {
    flex:5;
    display:flex;
    flex-direction:column;
    justify-content:space-between;

    h3{
        padding:10px;
        margin:0;
        border-bottom:1px dashed #d1d0d6;;
    }

}
</style>
