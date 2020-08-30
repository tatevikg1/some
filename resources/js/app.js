require('./bootstrap');

window.Vue = require('vue');


// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('follow-button', require('./components/FollowButton.vue').default);

Vue.component('like-button', require('./components/LikeButton.vue').default);

Vue.component('chat-messages', require('./components/ChatMessages.vue').default);

Vue.component('chat-form', require('./components/ChatForm.vue').default);


const app = new Vue({
    el: '#app',

    data: {
        messages: []
    },

    created() {
        this.fetchMessages();

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                this.messages.push({
                    message: e.message.message,
                    user: e.user
                });
            });



    },

    methods: {
        fetchMessages() {
            axios.get('/messages').then(response => {
                this.messages = response.data;

            });
        },

        addMessage(message) {
            this.messages.push(message);

            axios.post('/messages', message).then(response => {
              console.log(response.data);
            });
        },

        // scroll(){
        //     var scrolled = false;
        //     function updateScroll(){
        //         if(!scrolled){
        //             var element = document.getElementById("scroll");
        //             element.scrollTop = element.scrollHeight;
        //         }
        //     }
        //
        //     $("#yourDivID").on('scroll', function(){
        //         scrolled=true;
        //     });
        // }
    }
});
