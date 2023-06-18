<template>
    <div class="contacts-list">
        <ul>
            <li v-for="contact  in sortedContacts"
                :key="contact.id"
                @click="selectContact(contact)"
                :class="{ 'selected': contact === selectedContact }">
                <div class="image">
                    <img src="http://via.placeholder.com/150x150"
                        class="rounded-circle"
                        style="max-width:50px"
                        :key="contact.id">

                </div>
                <div class="contact">
                    <p class='name'>{{ contact.name  }}</p>
                    <p>{{ contact.email }}</p>
                </div>
                <span class="unread" v-if="contact.unread">{{ contact.unread }}</span>

            </li>

        </ul>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";

    export default{

        props:{
            contacts:{
                default:[]
            }
        },

        methods:{
            ...mapMutations(['setSelectedContact']),

            selectContact(contact){
                this.setSelectedContact(contact)
                this.$emit('selected', contact);
            }
        },

        computed: {
            ...mapGetters(['selectedContact']),

            sortedContacts() {
                return _.sortBy(this.contacts, [(contact) => {
                    if (contact === this.selectedContact) {
                        return Infinity;
                    }
                    return contact.unread;
                }]).reverse();
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

        ul {
            list-style-type: none; /* Remove bullets */
            padding-left:0;

            .selected {
                background-color:#d1d0d6;
            }

            li {
                display:flex;
                padding:2px;
                cursor:pointer;
                border-bottom:1px solid #d1d0d6;
                height:80px;
                position: relative;

                .image {
                    flex:1;
                    display:flex;
                    align-items:center;

                    img {
                        width:35px;
                        border-radius:50%;
                        margin:0 auto;
                    }
                }
            }

            .contact {
                flex:2;
                font-size:12px;
                display:flex;
                overflow:hidden;
                flex-direction:column;
                justify-content:center;

                p {
                    margin:0;
                }
                .name {
                    font-weight:bold;
                }
            }

            span.unread {
                background-color: green;
                color: white;
                position: absolute;
                right: 15px;
                top: 20px;
                min-width: 20px;
                text-align: center;
                font-weight: bold;
                font-size: 12px;
                border-radius: 50%;
            }
        }
    }
</style>
