import Vue from 'vue';
import Vuex from 'vuex';
import likeSystem from './modules/likeSystem';
import messageId from './modules/messageId';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        likeSystem,
        messageId
    },
    strict: debug,
})
