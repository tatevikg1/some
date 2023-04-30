require('./bootstrap');

window.Vue = require('vue');

import  store from './store';

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('follow-button', require('./components/FollowButton.vue').default);

Vue.component('like-button', require('./components/LikeButton.vue').default);
Vue.component('like-count', require('./components/LikeCount.vue').default);

Vue.component('friend-button', require('./components/FriendButton.vue').default);
Vue.component('friend-request-notification', require('./components/FriendRequestNotification.vue').default);
Vue.component('random-4-friend', require('./components/Random4Friend.vue').default);

Vue.component('chat-app', require('./components/ChatApp.vue').default);
Vue.component('message-notification', require('./components/chat/MessageNotification.vue').default);

Vue.component('InfiniteLoading', require('vue-infinite-loading'));
Vue.component('excel-button', require('./components/ExcelButton.vue').default);

const app = new Vue({
    el: '#app',
    store,
});
