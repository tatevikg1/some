require('./bootstrap');

window.Vue = require('vue');


// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('follow-button', require('./components/FollowButton.vue').default);

Vue.component('like-button', require('./components/LikeButton.vue').default);

Vue.component('chat-app', require('./components/ChatApp.vue').default);



const app = new Vue({
    el: '#app'

});
