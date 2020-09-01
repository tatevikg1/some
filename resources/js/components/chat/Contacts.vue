<template>
    <div class="contacts-list">
        <ul>
            <li v-for="contact  in contacts"
                :key="contact.id"
                @click="selectContact(index, contact)"
                :class="{'selected': index ==  selected }">
                <div class="image">
                    <img :src="contact"
                        class="rounded-circle"
                        style="max-width:50px"
                        :key="contact.id">

                </div>
                <div class="contact">
                    <p class='name'>{{ contact.name  }}</p>
                    <p>{{ contact.email }}</p>
                </div>
            </li>

        </ul>
    </div>
</template>

<script>
    export default{

        props:{
            contacts:{
                default:[]
            },
            index:{
                default:0
            }
        },

        data(){
            return{
                selected: 0
            };
        },

        methods:{
            selectContact(index, contact){
                this.selected = index;
                this.$emit('selected', contact);
            }
        }

    }
</script>

<style lang="scss" scoped>
    .contacts-list {
        flex:2;
        background-color:white;
        border-left: 1px solid #d1d0d6;
        max-height:500px;
        overflow:scroll;

        ul{
            list-style-type: none; /* Remove bullets */
            padding-left:0px;

            li{
                display:flex;
                padding:2px;
                cursor:pointer;
                border-bottom:1px solid #d1d0d6;
                height:80px;
                position: relative;

                .selected{
                    background-color:#d1d0d6;
                }

                .image{
                    flex:1;
                    display:flex;
                    align-items:center;

                    img{
                        width:35px;
                        border-radius:50%;
                        margin:0 auto;
                    }
                }
            }

            .contact{
                flex:2;
                font-size:12px;
                display:flex;
                overflow:hidden;
                flex-direction:column;
                justify-content:center;

                p{
                    margin:0;
                }
                .name{
                    font-weight:bold;
                }


            }


        }
    }


</style>
