<template>
    <div class="fon" ref="fon">
        <ul v-if="contact">
            <li v-for='message in messages'
                :key="message.id"
                :class="`message${message.receiver == contact.id ? ' sent' : ' received'}`">
                <div class="text">
                    {{message.text }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script>

    export default {

        props:{

            contact:{
                type:Object
            },

            messages: {
                required:true
            }
        },

        methods:{
            scrollToBotttom(){
                setTimeout(()=>{
                    this.$refs.fon.scrollTop = this.$refs.fon.scrollHeight;
                }, 50);
            }
        },

        watch:{
            contact(contact){
                this.scrollToBotttom();
            },
            messages(){
                this.scrollToBotttom();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .fon{
        background-color:#f1f0f6;
        height:100%;
        max-height:380px;
        overflow:scroll;

        ul{
            list-style-type: none;
            padding:10px;


            .message{
                margin:10px 0;
                width:100%;
            }

            .text{
                max-width:250px;
                padding:10px;
                display:inline-block;
                color:white;

            }

            .received{
                text-align:right;


                .text{
                    border-radius:5px 0px 5px 5px;
                    background-color:gray;
                }

            }

            .sent{
                text-align:left;


                .text{
                    border-radius:0px 5px 5px 5px;
                    background-color:#a0a0b0;
                }

            }


        }
    }


</style>
