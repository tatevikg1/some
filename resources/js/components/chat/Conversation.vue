<template>
    <div class="conversation">
        <h3>{{ contact ? contact.name : 'Select a contact.' }}</h3>
        <Messages :contact="contact" :messages='messages' />
        <MessageComposer @send="sendMessage" />

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
            }
        },

        methods:{
            sendMessage(text){
                console.log(text);
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
        paddind:10px;
        margin:0;
        border-bottom:1px dashed #d1d0d6;;
    }

}
</style>
